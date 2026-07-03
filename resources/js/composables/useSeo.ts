import { usePage } from '@inertiajs/vue3';

interface SeoShared {
    siteUrl: string;
    defaultOgImage: string;
}

/**
 * Helpers de SEO. og:image/twitter:image precisam ser URL absoluta para as
 * prévias de WhatsApp/Facebook/X renderizarem — este composable resolve
 * caminhos relativos usando o siteUrl compartilhado e oferece o banner padrão.
 */
export function useSeo() {
    const page = usePage<{ seo?: SeoShared }>();

    const siteUrl = (): string => page.props.seo?.siteUrl ?? '';
    const defaultOgImage = (): string => page.props.seo?.defaultOgImage ?? '';

    const absoluteImage = (path?: string | null): string => {
        if (!path) {
            return defaultOgImage();
        }

        if (/^https?:\/\//i.test(path)) {
            return path;
        }

        return `${siteUrl()}${path.startsWith('/') ? '' : '/'}${path}`;
    };

    return { siteUrl, defaultOgImage, absoluteImage };
}
