<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\Seo;
use App\Support\SiteCatalog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class CompanyController extends Controller
{
    public function about(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $canonicalUrl = $activeLocale === 'pt_BR' ? url('/sobre') : url('/'.$activeLocale.'/sobre');

        return Inertia::render('public/Sobre', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => 'Sobre a WMST — Software House & Automações IA',
                'description' => 'Conheça a WMST: desenvolvimento sob demanda, produtos SaaS e automações com IA para PMEs brasileiras.',
                'url' => $canonicalUrl,
            ]),
            'company' => SiteCatalog::company($activeLocale),
            'canonicalUrl' => $canonicalUrl,
            'alternateUrls' => [
                'pt_BR' => url('/sobre'),
                'es' => url('/es/sobre'),
                'en' => url('/en/sobre'),
            ],
        ]);
    }

    public function cases(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $canonicalUrl = $activeLocale === 'pt_BR' ? url('/cases') : url('/'.$activeLocale.'/cases');

        return Inertia::render('public/Cases', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => 'Cases WMST — resultados reais com IA e automação',
                'description' => 'Resultados de clientes: aumento de conversões, redução de tempo e ROI com IA aplicada e automação de processos.',
                'url' => $canonicalUrl,
            ]),
            'cases' => SiteCatalog::cases($activeLocale),
            'testimonials' => SiteCatalog::testimonials($activeLocale),
            'canonicalUrl' => $canonicalUrl,
            'alternateUrls' => [
                'pt_BR' => url('/cases'),
                'es' => url('/es/cases'),
                'en' => url('/en/cases'),
            ],
        ]);
    }

    public function contact(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $company = SiteCatalog::company($activeLocale);

        $canonicalUrl = $activeLocale === 'pt_BR' ? url('/contato') : url('/'.$activeLocale.'/contato');

        return Inertia::render('public/Contato', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => 'Contato WMST — fale com a gente',
                'description' => 'Solicite um diagnóstico gratuito. Fale com a WMST sobre automação, IA e sistemas sob medida para o seu negócio.',
                'url' => $canonicalUrl,
            ]),
            'contact' => $company['contact'],
            'services' => SiteCatalog::services($activeLocale),
            'canonicalUrl' => $canonicalUrl,
            'alternateUrls' => [
                'pt_BR' => url('/contato'),
                'es' => url('/es/contato'),
                'en' => url('/en/contato'),
            ],
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
