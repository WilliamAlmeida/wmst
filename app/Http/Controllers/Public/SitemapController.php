<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Support\SiteCatalog;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $locales = ['pt_BR', 'es', 'en'];
        $now = Carbon::now()->toAtomString();

        /** @var array<int, array{loc: string, lastmod: string, changefreq: string, priority: string, alternates: array<string, string>}> $urls */
        $urls = [];

        $staticPaths = [
            ['path' => '', 'priority' => '1.0', 'changefreq' => 'daily'],
            ['path' => '/produtos', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/solucoes', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => '/cases', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['path' => '/sobre', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => '/contato', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['path' => '/blog', 'priority' => '0.9', 'changefreq' => 'daily'],
        ];

        foreach ($staticPaths as $entry) {
            $urls[] = $this->buildLocalizedEntry($entry['path'], $now, $entry['changefreq'], $entry['priority'], $locales);
        }

        foreach (SiteCatalog::products() as $product) {
            $urls[] = $this->buildLocalizedEntry('/produtos/'.$product['slug'], $now, 'monthly', '0.85', $locales);
        }

        foreach (SiteCatalog::services() as $service) {
            $urls[] = $this->buildLocalizedEntry('/solucoes/'.$service['slug'], $now, 'monthly', '0.8', $locales);
        }

        BlogPost::query()
            ->published()
            ->orderByDesc('published_at')
            ->limit(500)
            ->get(['slug', 'updated_at', 'published_at'])
            ->each(function (BlogPost $post) use (&$urls, $locales): void {
                $lastmod = ($post->updated_at ?? $post->published_at ?? Carbon::now())->toAtomString();
                $urls[] = $this->buildLocalizedEntry('/blog/'.$post->slug, $lastmod, 'weekly', '0.7', $locales);
            });

        $xml = $this->renderXml($urls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * @param  array<int, string>  $locales
     * @return array{loc: string, lastmod: string, changefreq: string, priority: string, alternates: array<string, string>}
     */
    private function buildLocalizedEntry(string $path, string $lastmod, string $changefreq, string $priority, array $locales): array
    {
        $alternates = [];

        foreach ($locales as $locale) {
            $alternates[$locale] = $locale === 'pt_BR'
                ? url($path === '' ? '/' : $path)
                : url('/'.$locale.($path === '' ? '' : $path));
        }

        return [
            'loc' => $alternates['pt_BR'],
            'lastmod' => $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
            'alternates' => $alternates,
        ];
    }

    /**
     * @param  array<int, array{loc: string, lastmod: string, changefreq: string, priority: string, alternates: array<string, string>}>  $urls
     */
    private function renderXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($urls as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($entry['loc'], ENT_XML1).'</loc>'."\n";
            $xml .= '    <lastmod>'.$entry['lastmod'].'</lastmod>'."\n";
            $xml .= '    <changefreq>'.$entry['changefreq'].'</changefreq>'."\n";
            $xml .= '    <priority>'.$entry['priority'].'</priority>'."\n";
            foreach ($entry['alternates'] as $locale => $href) {
                $hreflang = $locale === 'pt_BR' ? 'pt-BR' : $locale;
                $xml .= '    <xhtml:link rel="alternate" hreflang="'.$hreflang.'" href="'.htmlspecialchars($href, ENT_XML1).'" />'."\n";
            }
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>'."\n";

        return $xml;
    }
}
