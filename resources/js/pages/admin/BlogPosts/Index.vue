<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Eye, FolderTree, Plus, SquarePen, Tag as TagIcon, Trash2, Undo2, Upload, Zap } from 'lucide-vue-next';
import BlogCategoryController from '@/actions/App/Http/Controllers/Admin/BlogCategoryController';
import BlogPostAgentController from '@/actions/App/Http/Controllers/Admin/BlogPostAgentController';
import BlogPostController from '@/actions/App/Http/Controllers/Admin/BlogPostController';
import BlogTagController from '@/actions/App/Http/Controllers/Admin/BlogTagController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
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

interface BlogPostItem {
    id: number;
    title: string;
    slug: string;
    locale: 'pt_BR' | 'es' | 'en';
    status: 'draft' | 'scheduled' | 'published';
    scheduled_for?: string | null;
    published_at: string | null;
    category?: { id: number; name: string } | null;
    author?: { id: number; name: string } | null;
    tags?: Array<{ id: number; name: string }>;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
}

const props = defineProps<{
    posts: Paginated<BlogPostItem>;
    categories: Category[];
    tags: Tag[];
    statuses: Array<'draft' | 'scheduled' | 'published'>;
    filters: {
        locale?: string | null;
        status?: string | null;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Blog',
                href: BlogPostController.index(),
            },
        ],
    },
});

const showCategories = ref(false);
const showTags = ref(false);

const categoryForm = useForm({
    locale: 'pt_BR' as 'pt_BR' | 'es' | 'en',
    name: '',
    slug: '',
});

const tagForm = useForm({
    locale: 'pt_BR' as 'pt_BR' | 'es' | 'en',
    name: '',
    slug: '',
});

const editingCategoryId = ref<number | null>(null);
const editingTagId = ref<number | null>(null);

const editCategoryForm = useForm({
    locale: 'pt_BR' as 'pt_BR' | 'es' | 'en',
    name: '',
    slug: '',
});

const editTagForm = useForm({
    locale: 'pt_BR' as 'pt_BR' | 'es' | 'en',
    name: '',
    slug: '',
});

const editingPostId = ref<number | null>(null);

const editForm = useForm({
    title: '',
    slug: '',
    locale: 'pt_BR' as 'pt_BR' | 'es' | 'en',
    status: 'draft' as 'draft' | 'scheduled' | 'published',
    blog_category_id: '',
    scheduled_for: '',
});

const filterForm = useForm({
    locale: props.filters.locale ?? '',
    status: props.filters.status ?? '',
});

const applyFilters = (): void => {
    router.get(
        BlogPostController.index.url({
            query: {
                locale: filterForm.locale || undefined,
                status: filterForm.status || undefined,
            },
        }),
        {},
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
        },
    );
};

const submitCategory = (): void => {
    categoryForm.post(BlogCategoryController.store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            categoryForm.reset('name', 'slug');
        },
    });
};

const submitTag = (): void => {
    tagForm.post(BlogTagController.store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            tagForm.reset('name', 'slug');
        },
    });
};

const startEditCategory = (category: Category): void => {
    editingCategoryId.value = category.id;
    editCategoryForm.locale = category.locale;
    editCategoryForm.name = category.name;
    editCategoryForm.slug = category.slug;
};

const saveEditCategory = (categoryId: number): void => {
    editCategoryForm.patch(BlogCategoryController.update.url(categoryId), {
        preserveScroll: true,
        onSuccess: () => {
            editingCategoryId.value = null;
        },
    });
};

const removeCategory = (categoryId: number): void => {
    if (!window.confirm('Deseja excluir esta categoria?')) {
        return;
    }

    router.delete(BlogCategoryController.destroy.url(categoryId), {
        preserveScroll: true,
    });
};

const startEditTag = (tag: Tag): void => {
    editingTagId.value = tag.id;
    editTagForm.locale = tag.locale;
    editTagForm.name = tag.name;
    editTagForm.slug = tag.slug;
};

const saveEditTag = (tagId: number): void => {
    editTagForm.patch(BlogTagController.update.url(tagId), {
        preserveScroll: true,
        onSuccess: () => {
            editingTagId.value = null;
        },
    });
};

const removeTag = (tagId: number): void => {
    if (!window.confirm('Deseja excluir esta tag?')) {
        return;
    }

    router.delete(BlogTagController.destroy.url(tagId), {
        preserveScroll: true,
    });
};

const toggleStatus = (post: BlogPostItem): void => {
    const nextStatus = post.status === 'published' ? 'draft' : 'published';

    router.patch(
        BlogPostController.update.url(post.id),
        {
            status: nextStatus,
        },
        {
            preserveScroll: true,
        },
    );
};

const startEdit = (post: BlogPostItem): void => {
    editingPostId.value = post.id;
    editForm.clearErrors();
    editForm.title = post.title;
    editForm.slug = post.slug;
    editForm.locale = post.locale;
    editForm.status = post.status;
    editForm.blog_category_id = post.category?.id ? String(post.category.id) : '';
    editForm.scheduled_for = post.scheduled_for ?? '';
};

const cancelEdit = (): void => {
    editingPostId.value = null;
    editForm.reset();
    editForm.clearErrors();
};

const submitEdit = (postId: number): void => {
    editForm
        .transform((data) => ({
            ...data,
            blog_category_id: data.blog_category_id ? Number(data.blog_category_id) : null,
            scheduled_for: data.scheduled_for || null,
        }))
        .patch(BlogPostController.update.url(postId), {
            preserveScroll: true,
            onSuccess: () => {
                cancelEdit();
            },
        });
};

const isEditing = (postId: number): boolean => editingPostId.value === postId;

const removePost = (post: BlogPostItem): void => {
    if (!window.confirm('Deseja remover este post?')) {
        return;
    }

    router.delete(BlogPostController.destroy.url(post.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Blog Admin" />

    <div class="space-y-6 p-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <Heading
                title="Gestão de Blog"
                description="Crie e gerencie conteúdos do site público em pt_BR, es e en."
            />

            <div class="flex flex-wrap items-center gap-2">
                <Button type="button" variant="outline" @click="showCategories = true">
                    <FolderTree class="h-4 w-4" />
                    Categorias
                </Button>
                <Button type="button" variant="outline" @click="showTags = true">
                    <TagIcon class="h-4 w-4" />
                    Tags
                </Button>
                <Link
                    :href="BlogPostAgentController.index()"
                    class="inline-flex items-center rounded-md border border-zinc-300 px-3 py-2 text-sm font-medium hover:bg-zinc-100"
                >
                    Agente de postagem
                </Link>
                <Link
                    :href="BlogPostController.create()"
                    class="inline-flex items-center gap-1.5 rounded-md bg-zinc-900 px-3 py-2 text-sm font-medium text-white hover:bg-zinc-800"
                >
                    <Plus class="h-4 w-4" />
                    Novo Post
                </Link>
            </div>
        </div>

        <section class="rounded-xl border p-4 md:p-6">
            <div class="mb-4 flex flex-wrap items-end gap-3">
                <div class="grid gap-1">
                    <Label for="filter-locale">Idioma</Label>
                    <select
                        id="filter-locale"
                        v-model="filterForm.locale"
                        class="h-10 rounded-md border px-3 text-sm"
                    >
                        <option value="">Todos</option>
                        <option value="pt_BR">pt_BR</option>
                        <option value="es">es</option>
                        <option value="en">en</option>
                    </select>
                </div>

                <div class="grid gap-1">
                    <Label for="filter-status">Status</Label>
                    <select
                        id="filter-status"
                        v-model="filterForm.status"
                        class="h-10 rounded-md border px-3 text-sm"
                    >
                        <option value="">Todos</option>
                        <option v-for="status in statuses" :key="status" :value="status">
                            {{ status }}
                        </option>
                    </select>
                </div>

                <Button type="button" variant="outline" @click="applyFilters">
                    Filtrar
                </Button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="px-2 py-2 font-medium">Título</th>
                            <th class="px-2 py-2 font-medium">Idioma</th>
                            <th class="px-2 py-2 font-medium">Status</th>
                            <th class="px-2 py-2 font-medium">Categoria</th>
                            <th class="px-2 py-2 font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="post in posts.data"
                            :key="post.id"
                            class="border-b last:border-0"
                        >
                            <td class="px-2 py-3">
                                <div v-if="isEditing(post.id)" class="grid gap-2">
                                    <Input v-model="editForm.title" placeholder="Título" />
                                    <Input v-model="editForm.slug" placeholder="slug" />
                                    <InputError :message="editForm.errors.title || editForm.errors.slug" />
                                </div>
                                <div v-else>
                                    <div class="font-medium">{{ post.title }}</div>
                                    <div class="text-xs text-muted-foreground">/{{ post.slug }}</div>
                                </div>
                            </td>
                            <td class="px-2 py-3">
                                <select
                                    v-if="isEditing(post.id)"
                                    v-model="editForm.locale"
                                    class="h-9 rounded-md border px-2 text-xs"
                                >
                                    <option value="pt_BR">pt_BR</option>
                                    <option value="es">es</option>
                                    <option value="en">en</option>
                                </select>
                                <span v-else>{{ post.locale }}</span>
                            </td>
                            <td class="px-2 py-3">
                                <select
                                    v-if="isEditing(post.id)"
                                    v-model="editForm.status"
                                    class="h-9 rounded-md border px-2 text-xs"
                                >
                                    <option v-for="status in statuses" :key="status" :value="status">
                                        {{ status }}
                                    </option>
                                </select>
                                <Badge v-else variant="outline">{{ post.status }}</Badge>
                            </td>
                            <td class="px-2 py-3">
                                <select
                                    v-if="isEditing(post.id)"
                                    v-model="editForm.blog_category_id"
                                    class="h-9 rounded-md border px-2 text-xs"
                                >
                                    <option value="">Sem categoria</option>
                                    <option
                                        v-for="category in categories.filter((item) => item.locale === editForm.locale)"
                                        :key="category.id"
                                        :value="String(category.id)"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <span v-else>{{ post.category?.name ?? '-' }}</span>
                            </td>
                            <td class="px-2 py-3">
                                <div v-if="isEditing(post.id)" class="flex flex-wrap gap-2">
                                    <Button
                                        size="sm"
                                        type="button"
                                        :disabled="editForm.processing"
                                        @click="submitEdit(post.id)"
                                    >
                                        Salvar
                                    </Button>
                                    <Button
                                        size="sm"
                                        type="button"
                                        variant="outline"
                                        @click="cancelEdit"
                                    >
                                        Cancelar
                                    </Button>
                                </div>
                                <div v-else class="flex items-center gap-1">
                                    <a
                                        :href="`/dashboard/blog-posts/${post.id}/preview`"
                                        target="_blank"
                                        rel="noopener"
                                        title="Visualizar"
                                        aria-label="Visualizar"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900"
                                    >
                                        <Eye class="h-4 w-4" />
                                    </a>
                                    <Link
                                        :href="BlogPostController.edit.url(post.id)"
                                        title="Editar página"
                                        aria-label="Editar página"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900"
                                    >
                                        <SquarePen class="h-4 w-4" />
                                    </Link>
                                    <button
                                        type="button"
                                        title="Edição rápida"
                                        aria-label="Edição rápida"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900"
                                        @click="startEdit(post)"
                                    >
                                        <Zap class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button"
                                        :title="post.status === 'published' ? 'Voltar para rascunho' : 'Publicar'"
                                        :aria-label="post.status === 'published' ? 'Voltar para rascunho' : 'Publicar'"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 hover:bg-zinc-100"
                                        :class="post.status === 'published' ? 'text-amber-600' : 'text-emerald-600'"
                                        @click="toggleStatus(post)"
                                    >
                                        <Undo2 v-if="post.status === 'published'" class="h-4 w-4" />
                                        <Upload v-else class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button"
                                        title="Excluir"
                                        aria-label="Excluir"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-red-200 text-red-600 hover:bg-red-50"
                                        @click="removePost(post)"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="posts.data.length === 0">
                            <td colspan="5" class="px-2 py-8 text-center text-sm text-muted-foreground">
                                Nenhum post ainda. Clique em “Novo Post” para começar.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <button
                    v-for="link in posts.links"
                    :key="link.label"
                    :disabled="!link.url"
                    class="rounded border px-3 py-1 text-xs"
                    :class="link.active ? 'border-black bg-black text-white' : ''"
                    v-html="link.label"
                    @click="link.url && router.visit(link.url)"
                />
            </div>
        </section>

        <!-- MODAL: CATEGORIAS -->
        <Dialog :open="showCategories" @update:open="showCategories = $event">
            <DialogContent class="max-h-[85vh] overflow-y-auto sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Categorias</DialogTitle>
                    <DialogDescription>Organize os posts por categoria (por idioma).</DialogDescription>
                </DialogHeader>

                <form class="grid gap-2 md:grid-cols-3" @submit.prevent="submitCategory">
                    <select v-model="categoryForm.locale" class="h-10 rounded-md border px-3 text-sm">
                        <option value="pt_BR">pt_BR</option>
                        <option value="es">es</option>
                        <option value="en">en</option>
                    </select>
                    <Input v-model="categoryForm.name" placeholder="Nome" />
                    <Input v-model="categoryForm.slug" placeholder="slug-opcional" />
                    <InputError class="md:col-span-3" :message="categoryForm.errors.name" />
                    <Button class="md:col-span-3" :disabled="categoryForm.processing" type="submit">Criar categoria</Button>
                </form>

                <ul class="grid gap-2">
                    <li
                        v-for="category in categories"
                        :key="category.id"
                        class="grid gap-2 rounded-lg border p-3"
                    >
                        <template v-if="editingCategoryId === category.id">
                            <select v-model="editCategoryForm.locale" class="h-9 rounded-md border px-2 text-xs">
                                <option value="pt_BR">pt_BR</option>
                                <option value="es">es</option>
                                <option value="en">en</option>
                            </select>
                            <Input v-model="editCategoryForm.name" placeholder="Nome" />
                            <Input v-model="editCategoryForm.slug" placeholder="slug" />
                            <div class="flex gap-2">
                                <Button size="sm" type="button" @click="saveEditCategory(category.id)">Salvar</Button>
                                <Button size="sm" type="button" variant="outline" @click="editingCategoryId = null">Cancelar</Button>
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium">{{ category.name }} <span class="text-xs text-zinc-500">({{ category.locale }})</span></p>
                                <div class="flex gap-2">
                                    <Button size="sm" type="button" variant="outline" @click="startEditCategory(category)">Editar</Button>
                                    <Button size="sm" type="button" variant="destructive" @click="removeCategory(category.id)">Excluir</Button>
                                </div>
                            </div>
                        </template>
                    </li>
                    <li v-if="categories.length === 0" class="rounded-lg border border-dashed p-4 text-center text-sm text-muted-foreground">
                        Nenhuma categoria ainda.
                    </li>
                </ul>
            </DialogContent>
        </Dialog>

        <!-- MODAL: TAGS -->
        <Dialog :open="showTags" @update:open="showTags = $event">
            <DialogContent class="max-h-[85vh] overflow-y-auto sm:max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Tags</DialogTitle>
                    <DialogDescription>Etiquetas para agrupar posts por tema (por idioma).</DialogDescription>
                </DialogHeader>

                <form class="grid gap-2 md:grid-cols-3" @submit.prevent="submitTag">
                    <select v-model="tagForm.locale" class="h-10 rounded-md border px-3 text-sm">
                        <option value="pt_BR">pt_BR</option>
                        <option value="es">es</option>
                        <option value="en">en</option>
                    </select>
                    <Input v-model="tagForm.name" placeholder="Nome" />
                    <Input v-model="tagForm.slug" placeholder="slug-opcional" />
                    <InputError class="md:col-span-3" :message="tagForm.errors.name" />
                    <Button class="md:col-span-3" :disabled="tagForm.processing" type="submit">Criar tag</Button>
                </form>

                <ul class="grid gap-2">
                    <li
                        v-for="tag in tags"
                        :key="tag.id"
                        class="grid gap-2 rounded-lg border p-3"
                    >
                        <template v-if="editingTagId === tag.id">
                            <select v-model="editTagForm.locale" class="h-9 rounded-md border px-2 text-xs">
                                <option value="pt_BR">pt_BR</option>
                                <option value="es">es</option>
                                <option value="en">en</option>
                            </select>
                            <Input v-model="editTagForm.name" placeholder="Nome" />
                            <Input v-model="editTagForm.slug" placeholder="slug" />
                            <div class="flex gap-2">
                                <Button size="sm" type="button" @click="saveEditTag(tag.id)">Salvar</Button>
                                <Button size="sm" type="button" variant="outline" @click="editingTagId = null">Cancelar</Button>
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-medium">{{ tag.name }} <span class="text-xs text-zinc-500">({{ tag.locale }})</span></p>
                                <div class="flex gap-2">
                                    <Button size="sm" type="button" variant="outline" @click="startEditTag(tag)">Editar</Button>
                                    <Button size="sm" type="button" variant="destructive" @click="removeTag(tag.id)">Excluir</Button>
                                </div>
                            </div>
                        </template>
                    </li>
                    <li v-if="tags.length === 0" class="rounded-lg border border-dashed p-4 text-center text-sm text-muted-foreground">
                        Nenhuma tag ainda.
                    </li>
                </ul>
            </DialogContent>
        </Dialog>
    </div>
</template>
