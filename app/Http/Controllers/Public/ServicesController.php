<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\SiteCatalog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ServicesController extends Controller
{
    public function index(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $services = SiteCatalog::services($activeLocale);

        return Inertia::render('public/services/Index', [
            'locale' => $activeLocale,
            'services' => $services,
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url('/solucoes') : url('/'.$activeLocale.'/solucoes'),
            'alternateUrls' => [
                'pt_BR' => url('/solucoes'),
                'es' => url('/es/solucoes'),
                'en' => url('/en/solucoes'),
            ],
        ]);
    }

    public function show(Request $request, string $localeOrSlug, ?string $slug = null): InertiaResponse
    {
        $activeLocale = $slug === null ? 'pt_BR' : $this->resolveLocale($localeOrSlug);
        $resolvedSlug = $slug ?? $localeOrSlug;

        app()->setLocale($activeLocale);

        $service = SiteCatalog::findService($resolvedSlug, $activeLocale);

        if ($service === null) {
            throw new NotFoundHttpException;
        }

        $related = array_values(array_filter(
            SiteCatalog::services($activeLocale),
            fn (array $s): bool => $s['slug'] !== $resolvedSlug,
        ));

        $path = '/solucoes/'.$resolvedSlug;

        return Inertia::render('public/services/Show', [
            'locale' => $activeLocale,
            'service' => $service,
            'related' => array_slice($related, 0, 3),
            'canonicalUrl' => $activeLocale === 'pt_BR' ? url($path) : url('/'.$activeLocale.$path),
            'alternateUrls' => [
                'pt_BR' => url($path),
                'es' => url('/es'.$path),
                'en' => url('/en'.$path),
            ],
            'servicesUrl' => $activeLocale === 'pt_BR' ? url('/solucoes') : url('/'.$activeLocale.'/solucoes'),
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
