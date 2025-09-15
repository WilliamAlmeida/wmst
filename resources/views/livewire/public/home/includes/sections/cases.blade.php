@props([
    'cases' => [
        [
            'icon' => 'o-arrow-trending-up',
            'highlight' => '300%',
            'title' => 'Aumento em conversões',
            'description' => 'Chatbot IA qualifica leads e fecha vendas automaticamente via WhatsApp',
            'client' => 'E-commerce de Moda'
        ],
        [
            'icon' => 'o-clock',
            'highlight' => '85%',
            'title' => 'Redução no tempo de agendamentos',
            'description' => 'IA agenda consultas, envia lembretes e gerencia cancelamentos automaticamente',
            'client' => 'Clínica Médica Especializada'
        ],
        [
            'icon' => 'phosphor.target',
            'highlight' => '500%',
            'title' => 'ROI em automação de processos',
            'description' => 'Eliminação de 40h semanais de trabalho manual com automações inteligentes',
            'client' => 'Empresa de Logística'
        ]
    ],
    'testimonials' => [
        [
            'name' => 'Carlos Silva',
            'role' => 'CEO',
            'company' => 'TechStart Soluções',
            'testimonial' => 'A automação de WhatsApp da W.M. revolucionou nosso atendimento. Agora vendemos 24/7 sem precisar de uma equipe gigante. O ROI foi incrível - recuperamos o investimento em 2 meses!'
        ],
        [
            'name' => 'Dra. Ana Santos',
            'role' => 'Diretora',
            'company' => 'Clínica Vida & Saúde',
            'testimonial' => 'O AI CLINIC eliminou nossa fila de espera completamente. A IA agenda tudo automaticamente e os pacientes adoram a praticidade. Nossa eficiência operacional aumentou 300%!'
        ],
        [
            'name' => 'Roberto Lima',
            'role' => 'Proprietário',
            'company' => 'Delivery Express',
            'testimonial' => 'O IBOX DELIVERY nos colocou no mesmo nível dos grandes players do mercado. Sistema robusto, suporte excepcional e resultados desde o primeiro dia. Recomendo de olhos fechados!'
        ]
    ]
])

<section id="cases" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Resultados Reais para Clientes Reais</h2>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto">Veja como nossas automações transformaram negócios e geraram resultados mensuráveis</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            @foreach ($cases as $case)
                <div class="opacity-0 delay-500 duration-1000" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
                    <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm text-center bg-gradient-card border-neutral-content hover:border-primary/30 transition-all duration-300">
                        <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6 pb-2">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                @if($icon = $case['icon'] ?? null)
                                    <x-icon :name="$icon" class="h-8 w-8 text-primary" />
                                @endif
                            </div>
                            <div class="text-4xl font-bold gradient-text mb-2">{{ $case['highlight'] }}</div>
                            <div class="font-semibold text-lg">{{ $case['title'] }}</div>
                            <div class="text-sm font-semibold text-primary">{{ $case['client'] }}</div>
                        </div>
                        <div class="px-6">
                            <p class="text-sm text-muted-foreground">{{ $case['description'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($testimonials as $value)
                <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm bg-gradient-card border-neutral-content hover:border-primary/30 transition-all duration-300 fade-in-up">
                    <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                                <x-icon name="o-users" class="h-6 w-6 text-primary" />
                            </div>
                            <div>
                                <div class="font-semibold text-lg">{{ $value['name'] }}</div>
                                <div class="text-muted-foreground text-sm">{{ $value['role'] }}, {{ $value['company'] }}</div>
                            </div>
                        </div>
                        <div class="flex gap-1 mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <x-icon name="s-star" class="h-4 w-4 text-primary" />
                            @endfor
                        </div>
                    </div>
                    <div class="px-6">
                        <p class="text-muted-foreground italic text-sm">"{{ $value['testimonial'] }}"</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>