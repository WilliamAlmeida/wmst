<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Check, Copy, Eye, EyeOff, RefreshCw, Trash2, UserPlus } from 'lucide-vue-next';
import UserController from '@/actions/App/Http/Controllers/Admin/UserController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface UserItem {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string | null;
    is_current: boolean;
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

defineProps<{
    users: Paginated<UserItem>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Usuários', href: UserController.index() }],
    },
});

const form = useForm({
    name: '',
    email: '',
    password: '',
});

const showPassword = ref(false);
const copied = ref(false);

const generatePassword = (length = 16): void => {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%*?';
    const values = new Uint32Array(length);
    crypto.getRandomValues(values);
    let out = '';
    for (let i = 0; i < length; i++) {
        out += chars[values[i] % chars.length];
    }
    form.password = out;
    showPassword.value = true;
    copied.value = false;
};

const copyPassword = async (): Promise<void> => {
    if (!form.password) return;
    await navigator.clipboard.writeText(form.password);
    copied.value = true;
    setTimeout(() => (copied.value = false), 1500);
};

const submit = (): void => {
    form.post(UserController.store().url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showPassword.value = false;
            copied.value = false;
        },
    });
};

const destroy = (user: UserItem): void => {
    if (user.is_current) return;
    if (!confirm(`Excluir o usuário ${user.name}? Esta ação não pode ser desfeita.`)) return;
    router.delete(UserController.destroy(user.id).url, { preserveScroll: true });
};
</script>

<template>
    <Head title="Usuários" />

    <div class="flex flex-col gap-8 p-4 md:p-6">
        <Heading title="Usuários" description="Gerencie quem tem acesso ao painel." />

        <div class="grid gap-8 lg:grid-cols-[380px_1fr]">
            <!-- Criar usuário -->
            <form
                class="flex h-fit flex-col gap-4 rounded-xl border border-border bg-card p-6 shadow-sm"
                @submit.prevent="submit"
            >
                <div class="flex items-center gap-2">
                    <UserPlus class="h-5 w-5 text-[color:var(--color-brand)]" />
                    <h2 class="font-display text-lg font-semibold">Novo usuário</h2>
                </div>

                <div class="grid gap-2">
                    <Label for="u-name">Nome</Label>
                    <Input id="u-name" v-model="form.name" required autocomplete="off" placeholder="Nome completo" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="u-email">E-mail</Label>
                    <Input id="u-email" v-model="form.email" type="email" required autocomplete="off" placeholder="email@empresa.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="u-password">Senha</Label>
                    <div class="flex items-stretch gap-2">
                        <div class="relative flex-1">
                            <Input
                                id="u-password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                placeholder="Senha"
                                class="pr-9"
                            />
                            <button
                                type="button"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                                :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'"
                                @click="showPassword = !showPassword"
                            >
                                <component :is="showPassword ? EyeOff : Eye" class="h-4 w-4" />
                            </button>
                        </div>
                        <Button type="button" variant="outline" size="icon" title="Gerar senha aleatória" @click="generatePassword()">
                            <RefreshCw class="h-4 w-4" />
                        </Button>
                        <Button type="button" variant="outline" size="icon" title="Copiar senha" :disabled="!form.password" @click="copyPassword">
                            <component :is="copied ? Check : Copy" class="h-4 w-4" :class="copied ? 'text-[color:var(--wmst-green-700)]' : ''" />
                        </Button>
                    </div>
                    <p class="text-xs text-muted-foreground">Use o botão de atualizar para gerar uma senha forte automaticamente.</p>
                    <InputError :message="form.errors.password" />
                </div>

                <Button type="submit" class="mt-2" :disabled="form.processing">
                    Criar usuário
                </Button>
            </form>

            <!-- Lista de usuários -->
            <div class="overflow-hidden rounded-xl border border-border bg-card shadow-sm">
                <table class="w-full text-sm">
                    <thead class="border-b border-border bg-muted/40 text-left text-xs uppercase tracking-wide text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 font-medium">Nome</th>
                            <th class="px-4 py-3 font-medium">E-mail</th>
                            <th class="px-4 py-3 font-medium">Status</th>
                            <th class="px-4 py-3 font-medium">Criado em</th>
                            <th class="px-4 py-3 text-right font-medium">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="user in users.data" :key="user.id" class="transition hover:bg-muted/30">
                            <td class="px-4 py-3 font-medium text-foreground">
                                {{ user.name }}
                                <Badge v-if="user.is_current" variant="secondary" class="ml-2 align-middle">você</Badge>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">{{ user.email }}</td>
                            <td class="px-4 py-3">
                                <Badge v-if="user.email_verified_at" variant="secondary" class="bg-[color:var(--wmst-green-soft)] text-[color:var(--wmst-green-700)]">
                                    Verificado
                                </Badge>
                                <Badge v-else variant="outline">Pendente</Badge>
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">{{ user.created_at }}</td>
                            <td class="px-4 py-3 text-right">
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    :disabled="user.is_current"
                                    :title="user.is_current ? 'Você não pode excluir o próprio usuário' : 'Excluir usuário'"
                                    @click="destroy(user)"
                                >
                                    <Trash2 class="h-4 w-4 text-destructive" />
                                </Button>
                            </td>
                        </tr>
                        <tr v-if="users.data.length === 0">
                            <td colspan="5" class="px-4 py-10 text-center text-muted-foreground">Nenhum usuário cadastrado.</td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="users.links.length > 3" class="flex flex-wrap justify-center gap-1 border-t border-border p-3">
                    <button
                        v-for="link in users.links"
                        :key="link.label"
                        :disabled="!link.url"
                        class="min-w-8 rounded-md border px-2.5 py-1 text-xs transition disabled:cursor-not-allowed disabled:opacity-40"
                        :class="link.active ? 'border-[color:var(--color-brand)] bg-[color:var(--color-brand)] text-white' : 'border-border hover:border-[color:var(--color-brand)]'"
                        v-html="link.label"
                        @click="link.url && router.visit(link.url, { preserveScroll: true })"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
