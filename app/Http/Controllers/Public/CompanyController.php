<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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

        return Inertia::render('public/Sobre', [
            'locale' => $activeLocale,
            'company' => SiteCatalog::company($activeLocale),
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url('/sobre') : url('/'.$activeLocale.'/sobre'),
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

        return Inertia::render('public/Cases', [
            'locale' => $activeLocale,
            'cases' => SiteCatalog::cases($activeLocale),
            'testimonials' => SiteCatalog::testimonials($activeLocale),
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url('/cases') : url('/'.$activeLocale.'/cases'),
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

        return Inertia::render('public/Contato', [
            'locale' => $activeLocale,
            'contact' => $company['contact'],
            'services' => SiteCatalog::services($activeLocale),
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url('/contato') : url('/'.$activeLocale.'/contato'),
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
