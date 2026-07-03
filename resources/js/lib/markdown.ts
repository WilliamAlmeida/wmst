import DOMPurify from 'dompurify';
import { marked } from 'marked';

marked.setOptions({ gfm: true, breaks: true });

/**
 * Converte markdown em HTML seguro (sanitizado). Usado para renderizar as
 * respostas do agente no chat — a saída da IA nunca é confiável, então
 * passa sempre pelo DOMPurify antes de virar v-html.
 */
export function renderMarkdown(text: string): string {
    const raw = marked.parse(text ?? '', { async: false }) as string;

    return DOMPurify.sanitize(raw, {
        ALLOWED_TAGS: [
            'p', 'br', 'strong', 'em', 'del', 'code', 'pre', 'blockquote',
            'ul', 'ol', 'li', 'a', 'h1', 'h2', 'h3', 'h4', 'table', 'thead',
            'tbody', 'tr', 'th', 'td', 'hr', 'span',
        ],
        ALLOWED_ATTR: ['href', 'title', 'target', 'rel'],
    });
}
