<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'locale' => app()->getLocale(),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'trialSignup' => $request->session()->get('trialSignup'),
            ],
            // Metadados de SEO compartilhados: og:image precisa ser URL absoluta.
            // url() respeita o esquema da requisição (https), evitando o http
            // estático do config('app.url').
            'seo' => [
                'siteUrl' => rtrim(url('/'), '/'),
                'defaultOgImage' => url('/images/og-default.png'),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
