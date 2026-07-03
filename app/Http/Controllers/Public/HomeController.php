<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AiAgent;
use App\Support\Seo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class HomeController extends Controller
{
    public function index(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $whatsappBase = 'https://wa.me/5512982184879?text=';

        $seoCopy = [
            'pt_BR' => ['title' => 'WMST — Automatize, escale e lucre mais com IA aplicada', 'description' => 'Especialistas em IA, automações de WhatsApp, Instagram e sistemas sob medida. Transformamos processos manuais em soluções que economizam tempo e aumentam lucros.'],
            'es' => ['title' => 'WMST — Automatiza, escala y vende más con IA aplicada', 'description' => 'Especialistas en IA, automatización de WhatsApp, Instagram y sistemas a medida. Transformamos procesos manuales en soluciones que ahorran tiempo y aumentan ganancias.'],
            'en' => ['title' => 'WMST — Automate, scale and sell more with applied AI', 'description' => 'Specialists in AI, WhatsApp and Instagram automation, and tailor-made systems. We turn manual processes into solutions that save time and increase profits.'],
        ];

        $siteOrigin = rtrim(url('/'), '/');
        $schemas = [
            [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => 'WMST',
                'legalName' => 'WMST — W.M Soluções Tecnológicas',
                'url' => $siteOrigin,
                'logo' => $siteOrigin.'/images/logo-wmst.png',
                'image' => $siteOrigin.'/images/og-default.png',
                'description' => $seoCopy[$activeLocale]['description'],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+55-12-98218-4879',
                    'contactType' => 'sales',
                    'areaServed' => 'BR',
                    'availableLanguage' => ['pt-BR', 'es', 'en'],
                ],
            ],
            [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'WMST',
                'url' => $siteOrigin,
                'inLanguage' => 'pt-BR',
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => $siteOrigin.'/blog?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ];

        return Inertia::render('public/Home', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => $seoCopy[$activeLocale]['title'],
                'description' => $seoCopy[$activeLocale]['description'],
                'url' => $request->url(),
                'schemas' => $schemas,
            ]),
            'alternateUrls' => [
                'pt_BR' => url('/'),
                'es' => url('/es'),
                'en' => url('/en'),
            ],
            'blogUrls' => [
                'pt_BR' => url('/blog'),
                'es' => url('/es/blog'),
                'en' => url('/en/blog'),
            ],
            'activeAgents' => AiAgent::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'description']),
            'metrics' => [
                ['label' => 'Projetos entregues', 'value' => '500+'],
                ['label' => 'Satisfação de clientes', 'value' => '98%'],
                ['label' => 'Disponibilidade media', 'value' => '99.9%'],
                ['label' => 'Suporte especializado', 'value' => '24/7'],
            ],
            'whatsappBase' => $whatsappBase,
            'whatsappUrl' => $whatsappBase.rawurlencode('Olá, gostaria de falar com a WMST!'),
        ]);
    }

    private function resolveLocale(?string $locale): string
    {
        if (in_array($locale, ['es', 'en'], true)) {
            return $locale;
        }

        return 'pt_BR';
    }
}
