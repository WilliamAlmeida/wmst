<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import BlogPostAgentController from '@/actions/App/Http/Controllers/Admin/BlogPostAgentController';
import BlogPostController from '@/actions/App/Http/Controllers/Admin/BlogPostController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Locale = 'pt_BR' | 'es' | 'en';

type Category = {
    id: number;
    locale: Locale;
    name: string;
    slug: string;
};

type Tag = {
    id: number;
    locale: Locale;
    name: string;
    slug: string;
};

type Post = {
    id: number;
    blog_category_id: number | null;
    locale: Locale;
    title: string;
    slug: string;
    excerpt: string | null;
    content: string;
    status: 'draft' | 'scheduled' | 'published';
    featured_image_path: string | null;
    seo_title: string | null;
    seo_description: string | null;
    scheduled_for: string | null;
    tags: Array<{ id: number; name: string }>;
};

type ImprovementResponse = {
    analysis: string;
    action_plan: string;
    suggestion: {
        title: string;
        slug: string;
        excerpt: string;
        content: string;
        seo_title: string;
        seo_description: string;
    };
};

const props = defineProps<{
    post: Post;
    categories: Category[];
    tags: Tag[];
    statuses: Array<'draft' | 'scheduled' | 'published'>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: BlogPostController.index(),
            },
            {
                title: 'Editar post',
                href: BlogPostController.index(),
            },
        ],
    },
});

const form = useForm({
    title: props.post.title,
    slug: props.post.slug,
    locale: props.post.locale,
    blog_category_id: props.post.blog_category_id ? String(props.post.blog_category_id) : '',
    excerpt: props.post.excerpt ?? '',
    content: props.post.content,
    status: props.post.status,
    featured_image_path: props.post.featured_image_path ?? '',
    seo_title: props.post.seo_title ?? '',
    seo_description: props.post.seo_description ?? '',
    scheduled_for: props.post.scheduled_for ? props.post.scheduled_for.slice(0, 16) : '',
    tag_ids: props.post.tags.map((tag) => tag.id),
});

const feedback = ref('');
const improving = ref(false);
const improveError = ref<string | null>(null);
const improveAnalysis = ref('');
const improvePlan = ref('');

const csrfToken = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null)?.content ?? '';

const savePost = (): void => {
    form
        .transform((data) => ({
            ...data,
            blog_category_id: data.blog_category_id ? Number(data.blog_category_id) : null,
            scheduled_for: data.scheduled_for || null,
        }))
        .patch(BlogPostController.update.url(props.post.id), {
            preserveScroll: true,
        });
};

const toggleTag = (tagId: number): void => {
    if (form.tag_ids.includes(tagId)) {
        form.tag_ids = form.tag_ids.filter((id) => id !== tagId);

        return;
    }

    form.tag_ids = [...form.tag_ids, tagId];
};

const improvePost = async (): Promise<void> => {
    improveError.value = null;

    if (feedback.value.trim() === '') {
        improveError.value = 'Informe um feedback para orientar a melhoria do post.';

        return;
    }

    improving.value = true;

    try {
        const response = await fetch(BlogPostAgentController.improve.url(props.post.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                feedback: feedback.value,
                current_title: form.title,
                current_excerpt: form.excerpt,
                current_content: form.content,
                current_seo_title: form.seo_title,
                current_seo_description: form.seo_description,
            }),
        });

        const data = (await response.json().catch(() => null)) as ImprovementResponse | { message?: string } | null;

        if (!response.ok) {
            throw new Error((data as { message?: string } | null)?.message ?? 'Nao foi possivel melhorar o post.');
        }

        const parsed = data as ImprovementResponse;

        form.title = parsed.suggestion.title;
        form.slug = parsed.suggestion.slug;
        form.excerpt = parsed.suggestion.excerpt;
        form.content = parsed.suggestion.content;
        form.seo_title = parsed.suggestion.seo_title;
        form.seo_description = parsed.suggestion.seo_description;

        improveAnalysis.value = parsed.analysis;
        improvePlan.value = parsed.action_plan;
    } catch (error) {
        improveError.value = error instanceof Error ? error.message : 'Erro ao chamar o agente de melhoria.';
    } finally {
        improving.value = false;
    }
};
</script>

<template>
    <Head :title="`Editar: ${post.title}`" />

    <div class="space-y-8 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <Heading title="Editar post" description="Atualize conteudo manualmente e use o agente para reconstruir o texto com feedback." />
            <Link :href="BlogPostController.index()" class="inline-flex items-center rounded-md border border-zinc-300 px-3 py-2 text-sm hover:bg-zinc-100">
                Voltar para lista
            </Link>
        </div>

        <section class="rounded-xl border p-4 md:p-6">
            <form class="grid gap-4" @submit.prevent="savePost">
                <div class="grid gap-2 md:grid-cols-3">
                    <div class="grid gap-1 md:col-span-2">
                        <Label for="title">Titulo</Label>
                        <Input id="title" v-model="form.title" />
                        <InputError :message="form.errors.title" />
                    </div>
                    <div class="grid gap-1">
                        <Label for="locale">Idioma</Label>
                        <select id="locale" v-model="form.locale" class="h-10 rounded-md border px-3 text-sm">
                            <option value="pt_BR">pt_BR</option>
                            <option value="es">es</option>
                            <option value="en">en</option>
                        </select>
                        <InputError :message="form.errors.locale" />
                    </div>
                </div>

                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-1">
                        <Label for="slug">Slug</Label>
                        <Input id="slug" v-model="form.slug" />
                        <InputError :message="form.errors.slug" />
                    </div>
                    <div class="grid gap-1">
                        <Label for="category">Categoria</Label>
                        <select id="category" v-model="form.blog_category_id" class="h-10 rounded-md border px-3 text-sm">
                            <option value="">Sem categoria</option>
                            <option
                                v-for="category in categories.filter((item) => item.locale === form.locale)"
                                :key="category.id"
                                :value="String(category.id)"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.blog_category_id" />
                    </div>
                </div>

                <div class="grid gap-1">
                    <Label for="excerpt">Resumo</Label>
                    <textarea id="excerpt" v-model="form.excerpt" class="min-h-20 rounded-md border px-3 py-2 text-sm" />
                    <InputError :message="form.errors.excerpt" />
                </div>

                <div class="grid gap-1">
                    <Label for="content">Conteudo</Label>
                    <textarea id="content" v-model="form.content" class="min-h-64 rounded-md border px-3 py-2 text-sm" />
                    <InputError :message="form.errors.content" />
                </div>

                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-1">
                        <Label for="seo-title">SEO title</Label>
                        <Input id="seo-title" v-model="form.seo_title" />
                        <InputError :message="form.errors.seo_title" />
                    </div>
                    <div class="grid gap-1">
                        <Label for="seo-description">SEO description</Label>
                        <textarea id="seo-description" v-model="form.seo_description" class="min-h-20 rounded-md border px-3 py-2 text-sm" />
                        <InputError :message="form.errors.seo_description" />
                    </div>
                </div>

                <div class="grid gap-2 md:grid-cols-3">
                    <div class="grid gap-1">
                        <Label for="status">Status</Label>
                        <select id="status" v-model="form.status" class="h-10 rounded-md border px-3 text-sm">
                            <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>
                    <div class="grid gap-1">
                        <Label for="scheduled-for">Agendar para</Label>
                        <Input id="scheduled-for" v-model="form.scheduled_for" type="datetime-local" />
                        <InputError :message="form.errors.scheduled_for" />
                    </div>
                    <div class="grid gap-1">
                        <Label for="featured-image">Imagem destacada</Label>
                        <Input id="featured-image" v-model="form.featured_image_path" />
                        <InputError :message="form.errors.featured_image_path" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label>Tags</Label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="tag in tags.filter((item) => item.locale === form.locale)"
                            :key="tag.id"
                            type="button"
                            class="rounded-full border px-3 py-1 text-xs"
                            :class="form.tag_ids.includes(tag.id) ? 'border-black bg-black text-white' : ''"
                            @click="toggleTag(tag.id)"
                        >
                            {{ tag.name }}
                        </button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <Button type="submit" :disabled="form.processing">Salvar alteracoes</Button>
                </div>
            </form>
        </section>

        <section class="rounded-xl border p-4 md:p-6">
            <h2 class="text-lg font-semibold">Melhorar post com agente</h2>
            <p class="mt-1 text-sm text-zinc-600">Envie um feedback e o agente devolve uma versao reconstruida usando o conteudo atual do post.</p>

            <div class="mt-4 grid gap-2">
                <Label for="feedback">Feedback editorial</Label>
                <textarea
                    id="feedback"
                    v-model="feedback"
                    class="min-h-24 rounded-md border px-3 py-2 text-sm"
                    placeholder="Ex: deixe mais tecnico no inicio, inclua exemplo pratico no meio e CTA no final"
                />
            </div>

            <div class="mt-4 flex items-center gap-3">
                <Button type="button" :disabled="improving" @click="improvePost">
                    {{ improving ? 'Melhorando...' : 'Melhorar post' }}
                </Button>
                <p v-if="improveError" class="text-sm text-red-600">{{ improveError }}</p>
            </div>

            <div v-if="improveAnalysis || improvePlan" class="mt-4 grid gap-3">
                <article class="rounded-md border bg-zinc-50 p-3">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Analise da melhoria</p>
                    <p class="mt-1 whitespace-pre-line text-sm">{{ improveAnalysis }}</p>
                </article>
                <article class="rounded-md border bg-zinc-50 p-3">
                    <p class="text-xs uppercase tracking-wide text-zinc-500">Plano aplicado</p>
                    <p class="mt-1 whitespace-pre-line text-sm">{{ improvePlan }}</p>
                </article>
            </div>
        </section>
    </div>
</template>
