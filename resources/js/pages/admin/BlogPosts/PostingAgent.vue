<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import BlogPostAgentController from '@/actions/App/Http/Controllers/Admin/BlogPostAgentController';
import BlogPostController from '@/actions/App/Http/Controllers/Admin/BlogPostController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Locale = 'pt_BR' | 'es' | 'en';

type Suggestion = {
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    seo_title: string;
    seo_description: string;
};

type AgentResult = {
    analysis: string;
    action_plan: string;
    suggestion: Suggestion;
    draft?: {
        id: number;
        title: string;
        status: string;
    } | null;
};

type Category = {
    id: number;
    locale: Locale;
    name: string;
    slug: string;
};

type PostItem = {
    id: number;
    locale: Locale;
    title: string;
    status: string;
    published_at: string | null;
    updated_at: string;
    category?: { id: number; name: string } | null;
};

const props = defineProps<{
    posts: PostItem[];
    categories: Category[];
    activeLocale?: Locale | null;
    stats: {
        total_posts: number;
        published_posts: number;
        draft_posts: number;
        categories: number;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: BlogPostController.index(),
            },
            {
                title: 'Agente de Postagem',
                href: BlogPostAgentController.index(),
            },
        ],
    },
});

const localeFilter = ref<'' | Locale>(props.activeLocale ?? '');
const analyzeFocus = ref('Melhorar conversao e consistencia editorial entre categorias.');

const titleHint = ref('');
const objective = ref('');
const targetAudience = ref('');
const generationLocale = ref<Locale>('pt_BR');
const categoryId = ref<string>('');
const saveAsDraft = ref(true);

const isAnalyzing = ref(false);
const isGenerating = ref(false);
const requestError = ref<string | null>(null);
const analysisResult = ref<AgentResult | null>(null);
const generationResult = ref<AgentResult | null>(null);

const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null)?.content ?? '';

const postJson = async <T,>(url: string, payload: Record<string, unknown>): Promise<T> => {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(payload),
    });

    const body = (await response.json().catch(() => null)) as T | { message?: string } | null;

    if (!response.ok) {
        throw new Error((body as { message?: string } | null)?.message ?? 'Não foi possivel processar sua solicitação.');
    }

    return body as T;
};

const runAnalysis = async (): Promise<void> => {
    requestError.value = null;
    isAnalyzing.value = true;

    try {
        analysisResult.value = await postJson<AgentResult>(BlogPostAgentController.analyze.url(), {
            focus: analyzeFocus.value,
            locale: localeFilter.value || undefined,
        });
    } catch (error) {
        requestError.value = error instanceof Error ? error.message : 'Falha ao analisar base de conteúdo.';
    } finally {
        isAnalyzing.value = false;
    }
};

const runGeneration = async (): Promise<void> => {
    requestError.value = null;
    isGenerating.value = true;

    try {
        generationResult.value = await postJson<AgentResult>(BlogPostAgentController.generate.url(), {
            locale: generationLocale.value,
            title_hint: titleHint.value,
            objective: objective.value || undefined,
            target_audience: targetAudience.value || undefined,
            blog_category_id: categoryId.value ? Number(categoryId.value) : null,
            save_as_draft: saveAsDraft.value,
        });
    } catch (error) {
        requestError.value = error instanceof Error ? error.message : 'Falha ao gerar post.';
    } finally {
        isGenerating.value = false;
    }
};
</script>

<template>
    <Head title="Agente de Postagem" />

    <div class="space-y-8 p-4">
        <Heading
            title="Agente de Postagem"
            description="Analise o conteúdo atual, entenda categorias existentes e gere rascunhos para avaliação editorial."
        />

        <section class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-xl border bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-zinc-500">Posts totais</p>
                <p class="mt-1 text-2xl font-semibold">{{ stats.total_posts }}</p>
            </article>
            <article class="rounded-xl border bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-zinc-500">Publicados</p>
                <p class="mt-1 text-2xl font-semibold">{{ stats.published_posts }}</p>
            </article>
            <article class="rounded-xl border bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-zinc-500">Rascunhos</p>
                <p class="mt-1 text-2xl font-semibold">{{ stats.draft_posts }}</p>
            </article>
            <article class="rounded-xl border bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-zinc-500">Categorias</p>
                <p class="mt-1 text-2xl font-semibold">{{ stats.categories }}</p>
            </article>
        </section>

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="text-lg font-semibold">Ferramenta 1: Analise da base atual</h2>
            <p class="mt-1 text-sm text-zinc-600">O agente avalia posts existentes e categorias para recomendar ajustes editoriais.</p>

            <div class="mt-4 grid gap-3 md:grid-cols-3">
                <div class="grid gap-1 md:col-span-2">
                    <Label for="focus">Foco da analise</Label>
                    <Input id="focus" v-model="analyzeFocus" placeholder="Ex: melhorar CTA e SEO das postagens de conversao" />
                </div>
                <div class="grid gap-1">
                    <Label for="locale-filter">Idioma (opcional)</Label>
                    <select id="locale-filter" v-model="localeFilter" class="h-10 rounded-md border px-3 text-sm">
                        <option value="">Todos</option>
                        <option value="pt_BR">pt_BR</option>
                        <option value="es">es</option>
                        <option value="en">en</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <Button type="button" :disabled="isAnalyzing" @click="runAnalysis">
                    {{ isAnalyzing ? 'Analisando...' : 'Analisar posts e categorias' }}
                </Button>
            </div>

            <div v-if="analysisResult" class="mt-4 grid gap-3">
                <article class="rounded-lg border bg-zinc-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Analise</p>
                    <p class="mt-2 whitespace-pre-line text-sm">{{ analysisResult.analysis }}</p>
                </article>
                <article class="rounded-lg border bg-zinc-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Plano de ação</p>
                    <p class="mt-2 whitespace-pre-line text-sm">{{ analysisResult.action_plan }}</p>
                </article>
            </div>
        </section>

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="text-lg font-semibold">Ferramenta 2: Gerar post para avaliação</h2>
            <p class="mt-1 text-sm text-zinc-600">Gere um rascunho com contexto editorial e opcionalmente salve direto no blog como draft.</p>

            <div class="mt-4 grid gap-3 md:grid-cols-2">
                <div class="grid gap-1">
                    <Label for="title-hint">Titulo/base do tema</Label>
                    <Input id="title-hint" v-model="titleHint" placeholder="Ex: Como reduzir no-show com automação de agenda" />
                </div>
                <div class="grid gap-1">
                    <Label for="objective">Objetivo</Label>
                    <Input id="objective" v-model="objective" placeholder="Ex: gerar leads de clinicas" />
                </div>
                <div class="grid gap-1">
                    <Label for="audience">Publico alvo</Label>
                    <Input id="audience" v-model="targetAudience" placeholder="Ex: gestores de clinicas de medio porte" />
                </div>
                <div class="grid gap-1">
                    <Label for="locale">Idioma</Label>
                    <select id="locale" v-model="generationLocale" class="h-10 rounded-md border px-3 text-sm">
                        <option value="pt_BR">pt_BR</option>
                        <option value="es">es</option>
                        <option value="en">en</option>
                    </select>
                </div>
                <div class="grid gap-1">
                    <Label for="category-id">Categoria</Label>
                    <select id="category-id" v-model="categoryId" class="h-10 rounded-md border px-3 text-sm">
                        <option value="">Sem categoria</option>
                        <option
                            v-for="category in categories.filter((item) => item.locale === generationLocale)"
                            :key="category.id"
                            :value="String(category.id)"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm md:col-span-2">
                    <input v-model="saveAsDraft" type="checkbox" class="h-4 w-4" />
                    Salvar automaticamente como rascunho para avaliação
                </label>
            </div>

            <div class="mt-4">
                <Button type="button" :disabled="isGenerating || !titleHint" @click="runGeneration">
                    {{ isGenerating ? 'Gerando...' : 'Gerar proposta de post' }}
                </Button>
            </div>

            <div v-if="generationResult" class="mt-4 grid gap-3">
                <article class="rounded-lg border bg-zinc-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Analise do agente</p>
                    <p class="mt-2 whitespace-pre-line text-sm">{{ generationResult.analysis }}</p>
                </article>

                <article class="rounded-lg border bg-zinc-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Plano de execução</p>
                    <p class="mt-2 whitespace-pre-line text-sm">{{ generationResult.action_plan }}</p>
                </article>

                <article class="rounded-lg border bg-zinc-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Titulo sugerido</p>
                    <p class="mt-1 text-sm font-medium">{{ generationResult.suggestion.title }}</p>

                    <p class="mt-3 text-xs uppercase tracking-wide text-zinc-500">Resumo sugerido</p>
                    <p class="mt-1 text-sm">{{ generationResult.suggestion.excerpt }}</p>

                    <p class="mt-3 text-xs uppercase tracking-wide text-zinc-500">Conteúdo sugerido</p>
                    <p class="mt-1 whitespace-pre-line text-sm">{{ generationResult.suggestion.content }}</p>

                    <div v-if="generationResult.draft" class="mt-4 rounded-md border border-emerald-300 bg-emerald-50 p-3 text-sm text-emerald-900">
                        <p>Rascunho criado: {{ generationResult.draft.title }}</p>
                        <Link :href="BlogPostController.edit.url(generationResult.draft.id)" class="mt-1 inline-block underline">
                            Abrir pagina de edição
                        </Link>
                    </div>
                </article>
            </div>
        </section>

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="text-lg font-semibold">Referencia rapida de conteúdo existente</h2>

            <div class="mt-4 grid gap-6 lg:grid-cols-2">
                <article>
                    <h3 class="mb-2 text-sm font-semibold uppercase tracking-wide text-zinc-500">Ultimos posts</h3>
                    <div class="max-h-80 overflow-auto rounded-lg border">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="px-3 py-2">Titulo</th>
                                    <th class="px-3 py-2">Idioma</th>
                                    <th class="px-3 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts" :key="post.id" class="border-b last:border-0">
                                    <td class="px-3 py-2">
                                        <Link :href="BlogPostController.edit.url(post.id)" class="hover:underline">
                                            {{ post.title }}
                                        </Link>
                                    </td>
                                    <td class="px-3 py-2">{{ post.locale }}</td>
                                    <td class="px-3 py-2">{{ post.status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article>
                    <h3 class="mb-2 text-sm font-semibold uppercase tracking-wide text-zinc-500">Categorias ativas</h3>
                    <ul class="grid gap-2">
                        <li v-for="category in categories" :key="category.id" class="rounded-lg border p-3 text-sm">
                            <p class="font-medium">{{ category.name }}</p>
                            <p class="text-xs text-zinc-500">{{ category.locale }} | /{{ category.slug }}</p>
                        </li>
                    </ul>
                </article>
            </div>
        </section>

        <p v-if="requestError" class="rounded-md border border-red-300 bg-red-50 p-3 text-sm text-red-700">
            {{ requestError }}
        </p>
    </div>
</template>
