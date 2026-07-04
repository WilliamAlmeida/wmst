<?php

namespace App\Mcp\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Cliente leve para as APIs REST do Google (Search Console e Analytics Data),
 * autenticando via service account. Sem dependência do google/apiclient: o JWT
 * é montado e assinado com openssl_sign (RS256) e trocado por um access token no
 * endpoint OAuth. O token fica em cache (~50 min) para evitar reassinar a cada call.
 */
class GoogleClient
{
    public const SCOPE_SEARCH_CONSOLE = 'https://www.googleapis.com/auth/webmasters.readonly';

    public const SCOPE_ANALYTICS = 'https://www.googleapis.com/auth/analytics.readonly';

    public function __construct(private readonly string $credentialsPath) {}

    public static function make(): self
    {
        return new self((string) config('services.google.credentials'));
    }

    /**
     * Lê e valida o JSON da service account.
     *
     * @return array{client_email: string, private_key: string, token_uri: string}
     */
    private function credentials(): array
    {
        if ($this->credentialsPath === '' || ! is_file($this->credentialsPath)) {
            throw new RuntimeException(
                "Credenciais Google não encontradas em '{$this->credentialsPath}'. ".
                'Baixe a chave JSON da service account e ajuste GOOGLE_SA_CREDENTIALS no .env.'
            );
        }

        $data = json_decode((string) file_get_contents($this->credentialsPath), true);

        if (! is_array($data) || empty($data['client_email']) || empty($data['private_key'])) {
            throw new RuntimeException('Arquivo de credenciais Google inválido (esperado JSON de service account).');
        }

        return [
            'client_email' => (string) $data['client_email'],
            'private_key' => (string) $data['private_key'],
            'token_uri' => (string) ($data['token_uri'] ?? 'https://oauth2.googleapis.com/token'),
        ];
    }

    public function accessToken(string $scope): string
    {
        $creds = $this->credentials();
        $cacheKey = 'google_sa_token:'.sha1($creds['client_email'].'|'.$scope);

        return Cache::remember($cacheKey, now()->addMinutes(50), function () use ($creds, $scope): string {
            $now = time();

            $assertion = $this->signJwt([
                'iss' => $creds['client_email'],
                'scope' => $scope,
                'aud' => $creds['token_uri'],
                'iat' => $now,
                'exp' => $now + 3600,
            ], $creds['private_key']);

            $response = Http::asForm()->post($creds['token_uri'], [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $assertion,
            ]);

            if ($response->failed() || ! $response->json('access_token')) {
                throw new RuntimeException('Falha ao obter access token do Google: '.$response->body());
            }

            return (string) $response->json('access_token');
        });
    }

    /**
     * @param  array<string, mixed>  $claims
     */
    private function signJwt(array $claims, string $privateKey): string
    {
        $segments = [
            $this->base64Url((string) json_encode(['alg' => 'RS256', 'typ' => 'JWT'])),
            $this->base64Url((string) json_encode($claims)),
        ];

        $signature = '';

        if (! openssl_sign(implode('.', $segments), $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Não foi possível assinar o JWT (verifique a private_key da service account).');
        }

        $segments[] = $this->base64Url($signature);

        return implode('.', $segments);
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    /**
     * @param  array<string, mixed>  $body
     * @return array<string, mixed>
     */
    public function post(string $url, string $scope, array $body): array
    {
        $response = Http::withToken($this->accessToken($scope))
            ->acceptJson()
            ->post($url, $body);

        if ($response->failed()) {
            throw new RuntimeException("Erro na API Google ({$response->status()}): ".$response->body());
        }

        return (array) $response->json();
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $url, string $scope, array $query = []): array
    {
        $response = Http::withToken($this->accessToken($scope))
            ->acceptJson()
            ->get($url, $query);

        if ($response->failed()) {
            throw new RuntimeException("Erro na API Google ({$response->status()}): ".$response->body());
        }

        return (array) $response->json();
    }
}
