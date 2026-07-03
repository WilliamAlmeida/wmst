<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Support\Seo;
use App\Support\SiteCatalog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductsController extends Controller
{
    public function index(Request $request, ?string $locale = null): InertiaResponse
    {
        $activeLocale = $this->resolveLocale($locale);

        app()->setLocale($activeLocale);

        $products = SiteCatalog::products($activeLocale);

        $canonicalUrl = $activeLocale === 'pt_BR' ? url('/produtos') : url('/'.$activeLocale.'/produtos');

        return Inertia::render('public/products/Index', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => 'Produtos WMST — SaaS para clínicas, delivery e marketing',
                'description' => 'Conheça o Agenda Clinic, IBOX Delivery, Conecta e outras soluções da WMST para automatizar e escalar o seu negócio.',
                'url' => $canonicalUrl,
            ]),
            'products' => $products,
            'canonicalUrl' => $canonicalUrl,
            'alternateUrls' => [
                'pt_BR' => url('/produtos'),
                'es' => url('/es/produtos'),
                'en' => url('/en/produtos'),
            ],
        ]);
    }

    public function show(Request $request, string $localeOrSlug, ?string $slug = null): InertiaResponse
    {
        $activeLocale = $slug === null ? 'pt_BR' : $this->resolveLocale($localeOrSlug);
        $resolvedSlug = $slug ?? $localeOrSlug;

        app()->setLocale($activeLocale);

        $product = SiteCatalog::findProduct($resolvedSlug, $activeLocale);

        if ($product === null) {
            throw new NotFoundHttpException;
        }

        $related = array_values(array_filter(
            SiteCatalog::products($activeLocale),
            fn (array $p): bool => $p['slug'] !== $resolvedSlug,
        ));

        $path = '/produtos/'.$resolvedSlug;
        $canonicalUrl = $activeLocale === 'pt_BR' ? url($path) : url('/'.$activeLocale.$path);

        return Inertia::render('public/products/Show', [
            'locale' => $activeLocale,
            'seo' => Seo::make([
                'title' => $product['name'].' — WMST',
                'description' => $product['tagline'] ?? $product['description'] ?? null,
                'url' => $canonicalUrl,
            ]),
            'product' => $product,
            'related' => $related,
            'canonicalUrl' => $canonicalUrl,
            'alternateUrls' => [
                'pt_BR' => url($path),
                'es' => url('/es'.$path),
                'en' => url('/en'.$path),
            ],
            'productsUrl' => $activeLocale === 'pt_BR' ? url('/produtos') : url('/'.$activeLocale.'/produtos'),
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
