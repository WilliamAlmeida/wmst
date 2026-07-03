<?php

namespace App\Support;

/**
 * Monta o payload de SEO renderizado server-side no blade (app.blade.php).
 *
 * As meta tags de Open Graph/Twitter precisam existir no HTML servido, pois os
 * crawlers de redes sociais (WhatsApp/Facebook/X) não executam JavaScript — as
 * tags do <Head> do Inertia só aparecem quando há SSR. Este helper garante um
 * baseline correto e permite override por página nos controllers.
 */
class Seo
{
    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    public static function make(array $overrides = [], ?string $url = null): array
    {
        // url() respeita o esquema/host da requisição (https), evitando o http
        // estático do config('app.url') que quebra a prévia em alguns crawlers.
        $siteUrl = rtrim(url('/'), '/');
        $defaultImage = url('/images/og-default.png');

        $defaults = [
            'siteUrl' => $siteUrl,
            'siteName' => 'WMST',
            'defaultOgImage' => $defaultImage,
            'title' => 'WMST — Software House & Automações IA',
            'description' => 'Especialistas em IA aplicada, automações de WhatsApp e Instagram e sistemas sob medida para PMEs.',
            'image' => $defaultImage,
            'imageWidth' => 1200,
            'imageHeight' => 630,
            'url' => $url ?? url()->current(),
            'type' => 'website',
            'publishedTime' => null,
            // Lista de schemas JSON-LD renderizados server-side (App\Support\Seo
            // + app.blade.php). Vazio por padrão.
            'schemas' => [],
        ];

        // Ignora overrides nulos/vazios para não apagar os defaults (mas mantém
        // arrays vazios explícitos, como 'schemas' => []).
        $clean = array_filter($overrides, static fn ($value) => $value !== null && $value !== '');

        $seo = array_merge($defaults, $clean);

        // Garante que a imagem seja sempre URL absoluta.
        $image = (string) $seo['image'];
        if (! str_starts_with($image, 'http://') && ! str_starts_with($image, 'https://')) {
            $seo['image'] = $siteUrl.'/'.ltrim($image, '/');
        }

        return $seo;
    }
}
