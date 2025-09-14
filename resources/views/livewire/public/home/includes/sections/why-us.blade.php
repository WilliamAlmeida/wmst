@props([
    'cards' => [
        [
            'icon' => 'brain',
            'title' => 'IA Aplicada',
            'highlight' => '300% ROI médio',
            'description' => 'Inteligência Artificial focada em resolver problemas reais do seu negócio',
        ],
        [
            'icon' => 'message-circle',
            'title' => 'Automação Total',
            'highlight' => '85% menos trabalho manual',
            'description' => 'WhatsApp, Telegram, Instagram - conecte-se com clientes 24/7',
        ],
        [
            'icon' => 'workflow',
            'title' => 'Processos Otimizados',
            'highlight' => '40h economizadas/semana',
            'description' => 'Fluxos de trabalho inteligentes que eliminam gargalos operacionais',
        ],
        [
            'icon' => 'shield',
            'title' => 'Tecnologia Robusta',
            'highlight' => '99.9% uptime garantido',
            'description' => 'Arquitetura escalável, segura e preparada para crescimento',
        ],
    ],
])

<section class="py-20 overflow-y-clip">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16 opacity-0" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in-up':shown}">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Por que Escolher a <span class="gradient-text">{{ config('app.name') }}</span>?</h2>
            <p class="text-xl text-muted-foreground max-w-3xl mx-auto">Há mais de 15 anos no mercado, somos especialistas em transformar ideias em soluções tecnológicas que geram resultados reais e mensuráveis para nossos clientes.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($cards as $card)
                <div class="opacity-0 delay-500" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in-up':shown}">
                    <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm border-neutral-content hover:border-primary/30 transition-all duration-300 bg-gradient-to-br from-white to-[#0066cc03]">
                        <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6 text-center pb-2">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                @php
                                    $icon = $card['icon'] ?? null;
                                    $iconComponent = $icon ? 'ui.icons.' . $icon : null;
                                @endphp
                                @if ($iconComponent)
                                    <x-dynamic-component :component="$iconComponent" class="h-8 w-8 text-primary" />
                                @endif
                            </div>
                            <div data-slot="card-title" class="font-semibold text-lg">{{ $card['title'] }}</div>
                            <div class="text-sm font-semibold text-primary">{{ $card['highlight'] }}</div>
                        </div>
                        <div data-slot="card-content" class="px-6 pt-0">
                            <p class="text-muted-foreground text-center text-sm">{{ $card['description'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>