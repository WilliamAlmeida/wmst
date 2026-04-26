<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { X, Sparkles, CheckCircle2 } from 'lucide-vue-next';
import TrialSignupController from '@/actions/App/Http/Controllers/Public/TrialSignupController';

type Locale = 'pt_BR' | 'es' | 'en';

interface AgentItem {
    id: number;
    name: string;
    slug: string;
    description: string | null;
}

const props = defineProps<{
    open: boolean;
    locale: Locale;
    activeAgents: AgentItem[];
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const page = usePage<{ flash?: { trialSignup?: { status: string; url: string; agent: string } } }>();

const trialForm = useForm({
    name: '',
    email: '',
    company: '',
    phone: '',
    product: 'custom',
    ai_agent_slug: '',
    message: '',
    consent_accepted: false,
});

const created = ref<{ status: string; url: string; agent: string } | null>(null);

watch(
    () => page.props.flash?.trialSignup,
    (val) => {
        if (val?.status === 'created') {
            created.value = val;
            trialForm.reset();
        }
    },
    { immediate: true },
);

const submit = (): void => {
    const action =
        props.locale === 'pt_BR'
            ? TrialSignupController.store['/trial-signups']()
            : TrialSignupController.store['/{locale}/trial-signups']({ locale: props.locale });

    trialForm.post(action.url, { preserveScroll: true });
};

const close = (): void => {
    emit('close');
};
</script>

<template>
    <Teleport to="body">
        <transition name="fade">
            <div
                v-if="open"
                class="fixed inset-0 z-50 flex items-end justify-center bg-zinc-950/60 p-0 backdrop-blur-sm md:items-center md:p-6"
                @click.self="close"
            >
                <div class="relative w-full max-w-2xl overflow-hidden rounded-t-3xl bg-white shadow-2xl md:rounded-3xl">
                    <div class="bg-gradient-to-r from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] px-6 py-5 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <Sparkles class="h-5 w-5" />
                                <h2 class="font-display text-lg font-semibold">Solicite seu link de teste</h2>
                            </div>
                            <button type="button" class="rounded-md p-1 hover:bg-white/10" @click="close" aria-label="Fechar">
                                <X class="h-5 w-5" />
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-white/85">Receba um link de teste para conversar com um agente IA em produção.</p>
                    </div>

                    <div class="max-h-[70vh] overflow-y-auto px-6 py-6">
                        <div
                            v-if="created"
                            class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900"
                        >
                            <CheckCircle2 class="mt-0.5 h-5 w-5 shrink-0" />
                            <div class="space-y-1">
                                <p class="font-medium">Link gerado para {{ created.agent }}.</p>
                                <a :href="created.url" target="_blank" class="inline-block break-all underline">
                                    {{ created.url }}
                                </a>
                            </div>
                        </div>

                        <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit">
                            <div class="grid gap-1">
                                <label for="t-name" class="text-sm font-medium text-zinc-700">Nome</label>
                                <input id="t-name" v-model="trialForm.name" class="h-10 rounded-lg border border-zinc-300 px-3 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20" required />
                                <p v-if="trialForm.errors.name" class="text-xs text-red-600">{{ trialForm.errors.name }}</p>
                            </div>
                            <div class="grid gap-1">
                                <label for="t-email" class="text-sm font-medium text-zinc-700">Email</label>
                                <input id="t-email" v-model="trialForm.email" type="email" class="h-10 rounded-lg border border-zinc-300 px-3 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20" required />
                                <p v-if="trialForm.errors.email" class="text-xs text-red-600">{{ trialForm.errors.email }}</p>
                            </div>
                            <div class="grid gap-1">
                                <label for="t-company" class="text-sm font-medium text-zinc-700">Empresa</label>
                                <input id="t-company" v-model="trialForm.company" class="h-10 rounded-lg border border-zinc-300 px-3 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20" />
                            </div>
                            <div class="grid gap-1">
                                <label for="t-phone" class="text-sm font-medium text-zinc-700">Telefone</label>
                                <input id="t-phone" v-model="trialForm.phone" class="h-10 rounded-lg border border-zinc-300 px-3 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20" />
                            </div>
                            <div class="grid gap-1">
                                <label for="t-product" class="text-sm font-medium text-zinc-700">Interesse principal</label>
                                <select id="t-product" v-model="trialForm.product" class="h-10 rounded-lg border border-zinc-300 px-3 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20">
                                    <option value="agenda_clinic">Agenda Clinic</option>
                                    <option value="ibox_delivery">IBOX Delivery</option>
                                    <option value="conecta">Conecta</option>
                                    <option value="custom">Projeto customizado</option>
                                </select>
                            </div>
                            <div class="grid gap-1">
                                <label for="t-agent" class="text-sm font-medium text-zinc-700">Agente</label>
                                <select id="t-agent" v-model="trialForm.ai_agent_slug" class="h-10 rounded-lg border border-zinc-300 px-3 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20">
                                    <option value="">Auto (mais recente)</option>
                                    <option v-for="agent in activeAgents" :key="agent.id" :value="agent.slug">
                                        {{ agent.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="grid gap-1 md:col-span-2">
                                <label for="t-msg" class="text-sm font-medium text-zinc-700">Contexto do teste</label>
                                <textarea id="t-msg" v-model="trialForm.message" class="min-h-24 rounded-lg border border-zinc-300 px-3 py-2 text-sm focus:border-[color:var(--color-brand)] focus:outline-none focus:ring-2 focus:ring-[color:var(--color-brand)]/20" />
                            </div>
                            <label class="flex items-start gap-2 text-sm text-zinc-700 md:col-span-2">
                                <input v-model="trialForm.consent_accepted" type="checkbox" class="mt-0.5 h-4 w-4 rounded border-zinc-300 text-[color:var(--color-brand)] focus:ring-[color:var(--color-brand)]" />
                                <span>Concordo com o processamento dos dados para contato comercial e demonstração.</span>
                            </label>
                            <p v-if="trialForm.errors.consent_accepted" class="text-xs text-red-600 md:col-span-2">
                                {{ trialForm.errors.consent_accepted }}
                            </p>
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[color:var(--color-brand)] to-[color:var(--color-brand-2)] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:opacity-90 disabled:opacity-50 md:col-span-2"
                                :disabled="trialForm.processing"
                            >
                                <Sparkles class="h-4 w-4" />
                                {{ trialForm.processing ? 'Gerando link...' : 'Gerar link de teste' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </transition>
    </Teleport>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 200ms ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
