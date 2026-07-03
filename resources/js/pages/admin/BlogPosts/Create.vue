<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import BlogPostController from '@/actions/App/Http/Controllers/Admin/BlogPostController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Category {
    id: number;
    locale: 'pt_BR' | 'es' | 'en';
    name: string;
    slug: string;
}

interface Tag {
    id: number;
    locale: 'pt_BR' | 'es' | 'en';
    name: string;
    slug: string;
}

const props = defineProps<{
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
                title: 'Novo Post',
                href: BlogPostController.create(),
            },
        ],
    },
});

const form = useForm({
    blog_category_id: '',
    locale: 'pt_BR' as 'pt_BR' | 'es' | 'en',
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    seo_title: '',
    seo_description: '',
    featured_image_path: '',
    status: 'draft' as 'draft' | 'scheduled' | 'published',
    scheduled_for: '',
    tag_ids: [] as number[],
});

const submit = (): void => {
    form
        .transform((data) => ({
            ...data,
            blog_category_id: data.blog_category_id ? Number(data.blog_category_id) : null,
            scheduled_for: data.scheduled_for || null,
        }))
        .post(BlogPostController.store.url());
};

const toggleTag = (tagId: number): void => {
    if (form.tag_ids.includes(tagId)) {
        form.tag_ids = form.tag_ids.filter((id) => id !== tagId);

        return;
    }

    form.tag_ids = [...form.tag_ids, tagId];
};
</script>

<template>
    <Head title="Novo Post" />

    <div class="space-y-6 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <Heading
                title="Novo Post"
                description="Crie um conteúdo para o blog público em pt_BR, es ou en."
            />

            <Link
                :href="BlogPostController.index()"
                class="inline-flex items-center gap-1.5 rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100"
            >
                <ArrowLeft class="h-4 w-4" />
                Voltar para a lista
            </Link>
        </div>

        <section class="rounded-xl border p-4 md:p-6">
            <form class="grid gap-4" @submit.prevent="submit">
                <div class="grid gap-2 md:grid-cols-3">
                    <div class="grid gap-2">
                        <Label for="locale">Idioma</Label>
                        <select id="locale" v-model="form.locale" class="h-10 rounded-md border px-3 text-sm">
                            <option value="pt_BR">pt_BR</option>
                            <option value="es">es</option>
                            <option value="en">en</option>
                        </select>
                        <InputError :message="form.errors.locale" />
                    </div>

                    <div class="grid gap-2 md:col-span-2">
                        <Label for="title">Título</Label>
                        <Input id="title" v-model="form.title" placeholder="Título do post" />
                        <InputError :message="form.errors.title" />
                    </div>
                </div>

                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="slug">Slug (opcional)</Label>
                        <Input id="slug" v-model="form.slug" placeholder="slug-do-post" />
                        <InputError :message="form.errors.slug" />
                    </div>

                    <div class="grid gap-2">
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

                <div class="grid gap-2">
                    <Label for="excerpt">Resumo</Label>
                    <textarea
                        id="excerpt"
                        v-model="form.excerpt"
                        class="min-h-20 rounded-md border px-3 py-2 text-sm"
                        placeholder="Resumo para listagens e SEO"
                    />
                    <InputError :message="form.errors.excerpt" />
                </div>

                <div class="grid gap-2">
                    <Label for="content">Conteúdo</Label>
                    <MarkdownEditor v-model="form.content" placeholder="Conteúdo completo do post em Markdown…" />
                    <InputError :message="form.errors.content" />
                </div>

                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="seo_title">SEO title</Label>
                        <Input id="seo_title" v-model="form.seo_title" placeholder="Título para SEO" />
                        <InputError :message="form.errors.seo_title" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="featured_image_path">Imagem destacada (path/url)</Label>
                        <Input id="featured_image_path" v-model="form.featured_image_path" placeholder="blog/posts/capa.webp" />
                        <InputError :message="form.errors.featured_image_path" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="seo_description">SEO description</Label>
                    <textarea
                        id="seo_description"
                        v-model="form.seo_description"
                        class="min-h-20 rounded-md border px-3 py-2 text-sm"
                        placeholder="Descrição para snippet do Google"
                    />
                    <InputError :message="form.errors.seo_description" />
                </div>

                <div class="grid gap-2 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="status">Status</Label>
                        <select id="status" v-model="form.status" class="h-10 rounded-md border px-3 text-sm">
                            <option v-for="status in statuses" :key="status" :value="status">
                                {{ status }}
                            </option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="scheduled_for">Agendado para</Label>
                        <Input id="scheduled_for" v-model="form.scheduled_for" type="datetime-local" />
                        <InputError :message="form.errors.scheduled_for" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label>Tags</Label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="tag in tags.filter((item) => item.locale === form.locale)"
                            :key="tag.id"
                            class="rounded-full border px-3 py-1 text-xs"
                            type="button"
                            :class="form.tag_ids.includes(tag.id) ? 'border-black bg-black text-white' : ''"
                            @click="toggleTag(tag.id)"
                        >
                            {{ tag.name }}
                        </button>
                        <span v-if="tags.filter((item) => item.locale === form.locale).length === 0" class="text-xs text-muted-foreground">
                            Nenhuma tag neste idioma. Crie tags na página de gestão do blog.
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <Link
                        :href="BlogPostController.index()"
                        class="inline-flex items-center rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium hover:bg-zinc-100"
                    >
                        Cancelar
                    </Link>
                    <Button :disabled="form.processing" type="submit">
                        Criar post
                    </Button>
                </div>
            </form>
        </section>
    </div>
</template>
