<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { Check, Copy, Eye, EyeOff, Plug, ServerCog, ShieldCheck, Terminal, Wrench } from 'lucide-vue-next';
import McpController from '@/actions/App/Http/Controllers/Admin/McpController';
import Heading from '@/components/Heading.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

interface Tool {
    name: string;
    summary: string;
    usage: string;
}

const props = defineProps<{
    localCommand: string;
    webEndpoint: string;
    tokenConfigured: boolean;
    token: string;
    tools: Tool[];
}>();

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Integração MCP', href: McpController.index() }],
    },
});

const showToken = ref(false);
const copiedKey = ref<string | null>(null);

const tokenPlaceholder = computed(() => (props.tokenConfigured ? props.token : 'DEFINA_MCP_TOKEN_NO_ENV'));

const localConfig = computed(() =>
    JSON.stringify(
        {
            mcpServers: {
                'wmst-blog': {
                    command: 'php',
                    args: ['artisan', 'mcp:start', 'wmst-blog'],
                },
            },
        },
        null,
        2,
    ),
);

const prodConfig = computed(() =>
    JSON.stringify(
        {
            mcpServers: {
                'wmst-blog-prod': {
                    type: 'http',
                    url: props.webEndpoint,
                    headers: { Authorization: `Bearer ${tokenPlaceholder.value}` },
                },
            },
        },
        null,
        2,
    ),
);

const copy = async (key: string, value: string): Promise<void> => {
    await navigator.clipboard.writeText(value);
    copiedKey.value = key;
    setTimeout(() => {
        if (copiedKey.value === key) copiedKey.value = null;
    }, 1500);
};
</script>

<template>
    <Head title="Integração MCP" />

    <div class="flex flex-col gap-8 p-4 md:p-6">
        <Heading
            title="Integração MCP"
            description="Conecte o Claude Code ao blog da WMST via MCP para criar, editar e publicar posts direto pelo agente."
        />

        <!-- O que é -->
        <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
            <div class="flex items-center gap-2">
                <ServerCog class="h-5 w-5 text-[color:var(--color-brand)]" />
                <h2 class="font-display text-lg font-semibold">O que é o MCP "WMST Blog"</h2>
            </div>
            <p class="mt-3 text-sm text-muted-foreground">
                MCP (Model Context Protocol) é o canal que dá ao agente do Claude Code um conjunto de ferramentas para operar o blog
                sem passar pela interface. O mesmo servidor <code class="rounded bg-muted px-1 py-0.5 text-xs">WMST Blog</code> funciona
                de dois jeitos: <strong>local</strong> (na sua máquina, sem token) e <strong>web/produção</strong> (via HTTP, protegido por token).
            </p>
            <p class="mt-2 text-sm text-muted-foreground">
                Diferente do <strong>Agente de Postagem</strong> (que gera rascunhos dentro do painel), o MCP é usado por fora, pelo
                Claude Code, e economiza tokens do plano ao concentrar a geração no agente.
            </p>
        </section>

        <div class="grid gap-6 lg:grid-cols-2">
            <!-- Local -->
            <section class="flex flex-col gap-4 rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-2">
                    <Terminal class="h-5 w-5 text-[color:var(--color-brand)]" />
                    <h2 class="font-display text-lg font-semibold">Uso local (desenvolvimento)</h2>
                </div>
                <p class="text-sm text-muted-foreground">
                    Roda via stdio, sem token. Já está configurado no arquivo <code class="rounded bg-muted px-1 py-0.5 text-xs">.mcp.json</code> do projeto.
                    Comando executado pelo Claude Code:
                </p>

                <div class="flex items-stretch gap-2">
                    <pre class="flex-1 overflow-x-auto rounded-md bg-[color:var(--wmst-navy-900)] px-3 py-2 text-xs text-zinc-100"><code>{{ localCommand }}</code></pre>
                    <Button type="button" variant="outline" size="icon" title="Copiar comando" @click="copy('cmd', localCommand)">
                        <component :is="copiedKey === 'cmd' ? Check : Copy" class="h-4 w-4" />
                    </Button>
                </div>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">Config .mcp.json (local)</p>
                    <div class="relative">
                        <pre class="overflow-x-auto rounded-md bg-[color:var(--wmst-navy-900)] p-3 text-xs leading-relaxed text-zinc-100"><code>{{ localConfig }}</code></pre>
                        <Button type="button" variant="outline" size="icon" class="absolute right-2 top-2" title="Copiar config" @click="copy('local', localConfig)">
                            <component :is="copiedKey === 'local' ? Check : Copy" class="h-4 w-4" />
                        </Button>
                    </div>
                </div>
            </section>

            <!-- Produção -->
            <section class="flex flex-col gap-4 rounded-xl border border-border bg-card p-6 shadow-sm">
                <div class="flex items-center gap-2">
                    <Plug class="h-5 w-5 text-[color:var(--color-brand)]" />
                    <h2 class="font-display text-lg font-semibold">Uso em produção (HTTP + token)</h2>
                </div>
                <p class="text-sm text-muted-foreground">
                    Endpoint web protegido por Bearer token. Aponte o Claude Code para o domínio de produção.
                </p>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">Endpoint</p>
                    <div class="flex items-stretch gap-2">
                        <pre class="flex-1 overflow-x-auto rounded-md bg-[color:var(--wmst-navy-900)] px-3 py-2 text-xs text-zinc-100"><code>{{ webEndpoint }}</code></pre>
                        <Button type="button" variant="outline" size="icon" title="Copiar endpoint" @click="copy('endpoint', webEndpoint)">
                            <component :is="copiedKey === 'endpoint' ? Check : Copy" class="h-4 w-4" />
                        </Button>
                    </div>
                </div>

                <div>
                    <div class="mb-1 flex items-center justify-between">
                        <p class="text-xs font-medium uppercase tracking-wide text-muted-foreground">Token (MCP_TOKEN)</p>
                        <Badge v-if="tokenConfigured" variant="secondary" class="bg-[color:var(--wmst-green-soft)] text-[color:var(--wmst-green-700)]">definido</Badge>
                        <Badge v-else variant="outline" class="text-destructive">não definido</Badge>
                    </div>
                    <div class="flex items-stretch gap-2">
                        <pre class="flex-1 overflow-x-auto rounded-md bg-[color:var(--wmst-navy-900)] px-3 py-2 text-xs text-zinc-100"><code>{{ showToken ? tokenPlaceholder : '•'.repeat(24) }}</code></pre>
                        <Button type="button" variant="outline" size="icon" :title="showToken ? 'Ocultar' : 'Revelar'" @click="showToken = !showToken">
                            <component :is="showToken ? EyeOff : Eye" class="h-4 w-4" />
                        </Button>
                        <Button type="button" variant="outline" size="icon" title="Copiar token" :disabled="!tokenConfigured" @click="copy('token', token)">
                            <component :is="copiedKey === 'token' ? Check : Copy" class="h-4 w-4" />
                        </Button>
                    </div>
                    <p class="mt-1 text-xs text-muted-foreground">
                        Definido em <code class="rounded bg-muted px-1 py-0.5">.env</code> (MCP_TOKEN). Para rotacionar: troque no .env e rode
                        <code class="rounded bg-muted px-1 py-0.5">php artisan config:clear</code>.
                    </p>
                </div>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">Config .mcp.json (produção)</p>
                    <div class="relative">
                        <pre class="overflow-x-auto rounded-md bg-[color:var(--wmst-navy-900)] p-3 text-xs leading-relaxed text-zinc-100"><code>{{ prodConfig }}</code></pre>
                        <Button type="button" variant="outline" size="icon" class="absolute right-2 top-2" title="Copiar config" @click="copy('prod', prodConfig)">
                            <component :is="copiedKey === 'prod' ? Check : Copy" class="h-4 w-4" />
                        </Button>
                    </div>
                </div>
            </section>
        </div>

        <!-- Tools -->
        <section class="rounded-xl border border-border bg-card p-6 shadow-sm">
            <div class="flex items-center gap-2">
                <Wrench class="h-5 w-5 text-[color:var(--color-brand)]" />
                <h2 class="font-display text-lg font-semibold">Ferramentas disponíveis</h2>
            </div>
            <p class="mt-2 text-sm text-muted-foreground">
                Fluxo recomendado: taxonomias → listar/inspecionar → criar/atualizar. Locales: pt_BR (padrão), es, en. Conteúdo aceita markdown ou HTML.
            </p>

            <div class="mt-4 grid max-h-96 gap-3 overflow-y-auto pr-1 md:grid-cols-2">
                <article v-for="tool in tools" :key="tool.name" class="rounded-lg border border-border bg-muted/20 p-4">
                    <code class="text-sm font-semibold text-[color:var(--color-brand)]">{{ tool.name }}</code>
                    <p class="mt-1 text-sm text-foreground">{{ tool.summary }}</p>
                    <p class="mt-1 text-xs text-muted-foreground">{{ tool.usage }}</p>
                </article>
            </div>
        </section>

        <!-- Segurança -->
        <section class="rounded-xl border border-[color:var(--wmst-green-700)]/30 bg-[color:var(--wmst-green-soft)] p-6">
            <div class="flex items-center gap-2">
                <ShieldCheck class="h-5 w-5 text-[color:var(--wmst-green-700)]" />
                <h2 class="font-display text-lg font-semibold">Segurança</h2>
            </div>
            <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-foreground">
                <li>Sempre use <strong>HTTPS</strong> em produção — o token viaja no header <code class="rounded bg-white/60 px-1 py-0.5">Authorization</code>.</li>
                <li>O token é um segredo de aplicação (não é login). O autor dos posts é o primeiro usuário do sistema.</li>
                <li>Se rodar <code class="rounded bg-white/60 px-1 py-0.5">php artisan route:cache</code>, rode-o <em>depois</em> de configurar o MCP.</li>
                <li>Suspeita de vazamento? Troque o <code class="rounded bg-white/60 px-1 py-0.5">MCP_TOKEN</code> no .env e limpe o cache de config.</li>
            </ul>
        </section>
    </div>
</template>
