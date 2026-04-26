<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AiAgent;
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

        return Inertia::render('public/Home', [
            'locale' => $activeLocale,
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
                ['label' => 'Satisfacao de clientes', 'value' => '98%'],
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
