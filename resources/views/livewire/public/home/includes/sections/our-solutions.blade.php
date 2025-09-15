@props([
    'cards' => [
        [
            'icon' => 'message-circle',
            'title' => 'Automação WhatsApp Business',
            'highlight' => '300% mais conversões',
            'description' => 'Atendimento 24/7, qualificação de leads automática, agendamentos inteligentes e vendas sem intervenção humana',
            'badge' => 'WhatsApp API + IA + N8N'
        ],
        [
            'icon' => 'instagram',
            'title' => 'Automação Instagram',
            'highlight' => '500% mais leads qualificados',
            'description' => 'Captura de leads, respostas automáticas inteligentes, direcionamento personalizado e engajamento automatizado',
            'badge' => 'Instagram API + AI + IA'
        ],
        [
            'icon' => 'workflow',
            'title' => 'Automação de Processos Internos',
            'highlight' => '40h economizadas por semana',
            'description' => 'Fluxos de aprovação automáticos, integração entre sistemas, relatórios em tempo real e eliminação total de tarefas manuais',
            'badge' => 'N8N + APIs + Webhooks + IA'
        ],
        [
            'icon' => 'database',
            'title' => 'Integração de Sistemas Legados',
            'highlight' => 'Zero downtime garantido',
            'description' => 'Conectamos sistemas antigos com novas tecnologias, migrações seguras e modernização gradual sem parar operações',
            'badge' => 'Laravel + APIs REST + Microserviços'
        ],
        [
            'icon' => 'brain',
            'title' => 'IA para Análise Preditiva',
            'highlight' => '85% precisão em previsões',
            'description' => 'Insights automáticos, previsões de vendas precisas, análise de comportamento do cliente e decisões baseadas em dados',
            'badge' => 'OpenAI / Claude / Gemini + Analytics'
        ],
        [
            'icon' => 'globe',
            'title' => 'Sistemas Web Sob Medida',
            'highlight' => '100% adequação ao negócio',
            'description' => 'Plataformas customizadas, dashboards interativos em tempo real, portais de cliente e sistemas de gestão completos',
            'badge' => 'Laravel + Livewire + IA'
        ]
    ],
])

<section id="solucoes" class="py-20 bg-card/30">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Soluções que Transformam Negócios</h2>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto">Não vendemos apenas tecnologia, entregamos
                soluções completas que resolvem problemas reais e geram resultados mensuráveis</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($cards as $card)
                <div class="opacity-0 delay-500 duration-1000" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
                    <div class="select-none flex flex-col gap-6 rounded-xl border py-6 shadow-sm border-neutral-content hover:border-primary/30 transition-all duration-300 bg-gradient-card group hover:shadow-lg hover:scale-[1.02] overflow-clip">
                        <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-lg flex items-center justify-center mb-2 group-hover:-rotate-12 transition-transform group-hover:scale-125">
                                @php
                                    $icon = $card['icon'] ?? null;
                                    $iconComponent = $icon ? 'ui.icons.' . $icon : null;
                                @endphp
                                @if ($iconComponent)
                                    <x-dynamic-component :component="$iconComponent" class="h-6 w-6 text-primary" />
                                @endif
                            </div>
                            <div class="font-semibold text-lg">{{ $card['title'] }}</div>
                            <div class="text-sm font-semibold text-primary">{{ $card['highlight'] }}</div>
                        </div>
                        <div class="px-6 space-y-4 relative">
                            <p class="text-muted-foreground text-sm">{{ $card['description'] }}</p>
                            <div class="pt-2 border-t border-neutral-content group-hover:opacity-0 transition-opacity duration-200">
                                <span class="font-medium text-xs rounded-md px-2 py-0.5 border border-primary/20 text-primary">{{ $card['badge'] }}</span>
                            </div>
                            <x-button label="Agende uma Reunião" class="btn-primary translate-y-10 opacity-0 group-hover:opacity-100 group-hover:-translate-y-10 transition-all absolute ease-in-out" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>