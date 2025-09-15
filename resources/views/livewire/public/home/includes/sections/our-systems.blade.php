<section id="sistemas" class="py-20 bg-gray-50 overflow-y-clip">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16 opacity-0 duration-[1s]" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Nossos <span class="gradient-text">Sistemas Inteligentes</span></h2>
            <p class="text-xl max-w-2xl mx-auto">Cada sistema foi desenvolvido para resolver problemas específicos e gerar resultados mensuráveis</p>
        </div>

        <div class="space-y-20">
            <div id="ibox-delivery" class="opacity-0 delay-500 duration-1000" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
                <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm overflow-hidden border-neutral-content/50 hover:border-primary/30 transition-all duration-300">
                    <div class="grid lg:grid-cols-2 gap-0">
                        <div class="p-8 lg:p-12 lg:order-last">
                                <div class="flex items-center gap-3 mb-2">
                                    <x-icon name="phosphor.device-mobile-camera" class="text-primary h-8 w-8" />
                                    <span class="text-xs badge bg-primary/10 text-primary border-primary/20 font-medium py-0.5 px-2 rounded-md">SaaS para Delivery e Restaurantes</span>
                                </div>
                                <div class="font-bold text-3xl lg:text-4xl mb-2 gradient-text">IBOX DELIVERY</div>
                                <div class="text-base mb-4">Plataforma completa de delivery estilo iFood com gestão integrada, pagamentos automáticos e dashboard em tempo real. Transforme seu negócio em uma máquina de vendas 24/7.</div>
                                <div class="rounded-xl bg-primary/5 border border-primary/10 p-5 mb-6">
                                    <div class="font-semibold text-primary mb-2 flex items-center gap-2"><x-icon name="phosphor.star" class="h-5 w-5" />Vantagens exclusivas</div>
                                    <ul class="grid gap-2 text-base text-muted-foreground">
                                        <li class="flex items-center gap-2"><x-icon name="phosphor.check-circle" class="text-primary h-5 w-5" /><span><span class="font-semibold text-primary">Sem taxa por pedido:</span> o lucro é todo do estabelecimento.</span></li>
                                        <li class="flex items-center gap-2"><x-icon name="phosphor.check-circle" class="text-primary h-5 w-5" /><span><span class="font-semibold text-primary">App PWA próprio:</span> cada loja tem seu próprio aplicativo, personalizado e fácil de instalar.</span></li>
                                        <li class="flex items-center gap-2"><x-icon name="phosphor.check-circle" class="text-primary h-5 w-5" /><span><span class="font-semibold text-primary">Vitrine exclusiva:</span> destaque sua marca e produtos em uma loja digital só sua.</span></li>
                                        <li class="flex items-center gap-2"><x-icon name="phosphor.check-circle" class="text-primary h-5 w-5" /><span><span class="font-semibold text-primary">Valores fixos sem surpresa:</span> mensalidade transparente, sem cobranças inesperadas.</span></li>
                                        <li class="flex items-center gap-2"><x-icon name="phosphor.check-circle" class="text-primary h-5 w-5" /><span><span class="font-semibold text-primary">Recebimento imediato:</span> o dinheiro já cai na conta do estabelecimento ao finalizar o pedido, sem taxa extra.</span></li>
                                    </ul>
                                </div>
                                <div class="grid md:grid-cols-2 gap-3 mb-8">
                                    <div class="flex items-center gap-x-2 bg-primary/5 rounded px-2 py-1"><x-icon name="phosphor.app-window" class="text-primary h-5 w-5" /><span class="text-sm">App Mobile (iOS/Android)</span></div>
                                    <div class="flex items-center gap-x-2 bg-primary/5 rounded px-2 py-1"><x-icon name="phosphor.sliders-horizontal" class="text-primary h-5 w-5" /><span class="text-sm">Painel Administrativo</span></div>
                                    <div class="flex items-center gap-x-2 bg-primary/5 rounded px-2 py-1"><x-icon name="phosphor.credit-card" class="text-primary h-5 w-5" /><span class="text-sm">Pagamentos Integrados</span></div>
                                    <div class="flex items-center gap-x-2 bg-primary/5 rounded px-2 py-1"><x-icon name="phosphor.percent" class="text-primary h-5 w-5" /><span class="text-sm">Sistema de Comissões</span></div>
                                    <div class="flex items-center gap-x-2 bg-primary/5 rounded px-2 py-1"><x-icon name="phosphor.chart-bar" class="text-primary h-5 w-5" /><span class="text-sm">Relatórios em Tempo Real</span></div>
                                    <div class="flex items-center gap-x-2 bg-primary/5 rounded px-2 py-1"><x-icon name="phosphor.printer" class="text-primary h-5 w-5" /><span class="text-sm">Impressão Pedidos Automáticos</span></div>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-3 mt-2">
                                    <x-button label="Solicitar Demonstração" class="btn-primary" icon-right="o-arrow-right" />
                                    <x-button label="Nossos Clientes" class="btn-primary btn-outline" link="https://ibox.delivery/v2/delivery" target="_blank" no-wire-navigate />
                                    <x-button label="Registrar sua loja" class="btn-success" link="https://ibox.delivery/" target="_blank" icon-right="o-arrow-left" />
                                </div>
                        </div>
                        <div class="bg-gradient-to-br from-secondary/5 to-primary/5 p-8 lg:p-12 flex items-center justify-center lg:order-first overflow-x-clip">
                            <div class="opacity-0 delay-1000" x-data="{shown2:false}" x-intersect="shown2=true" :class="{'fade-in-right':shown2}">
                                <x-ui.mockups.iphone class="lg:animate-float" side="left"
                                    contact-name="iBox Delivery" photo_url="{{ asset('images/logotipo-iboxdelivery.jpg') }}"
                                    :messages="[
                                        ['content' => 'Olá! Gostaria de fazer um pedido.', 'sent' => false, 'time' => '12:00'],
                                        ['content' => 'Olá! Claro, posso ajudar. O que gostaria de pedir?', 'sent' => true, 'time' => '12:01'],
                                        ['content' => 'Quero uma pizza grande de calabresa e uma Coca-Cola.', 'sent' => false, 'time' => '12:02'],
                                        ['content' => 'Perfeito! Sua pizza grande de calabresa e uma Coca-Cola foram adicionadas ao carrinho. Deseja mais alguma coisa?', 'sent' => true, 'time' => '12:03'],
                                        ['content' => 'Não, é só isso. Qual o valor total?', 'sent' => false, 'time' => '12:04'],
                                        ['content' => 'O valor total é R$ 45,00. Como gostaria de pagar?', 'sent' => true, 'time' => '12:05'],
                                        ['content' => 'Vou pagar com cartão de crédito.', 'sent' => false, 'time' => '12:06'],
                                        ['content' => 'Ótimo! Pedido confirmado. A entrega será feita em aproximadamente 30 minutos. Obrigado!', 'sent' => true, 'time' => '12:07'],
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div id="agenda-clinic" class="opacity-0 delay-500 duration-1000" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
                <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm overflow-hidden border-neutral-content/50 hover:border-primary/30 transition-all duration-300">
                    <div class="grid lg:grid-cols-2 gap-0">
                        <div class="p-8 lg:p-12">
                            <div class="flex items-center gap-3 mb-2">
                                <x-ui.icons.bot class="text-primary h-8 w-8" />
                                <span class="text-xs badge bg-primary/10 text-primary border-primary/20 font-medium py-0.5 px-2 rounded-md">SaaS para Clínicas e Profissionais de Saúde</span>
                            </div>
                            <div class="font-bold text-3xl lg:text-4xl mb-2 gradient-text">Agenda Clinic</div>
                            <div class="text-base mb-4">A plataforma completa para gestão de clínicas, consultórios e profissionais autônomos. Otimize agendamentos, atendimento e relacionamento com recursos avançados, automações e inteligência artificial.</div>
                            <div class="grid md:grid-cols-2 gap-4 mb-8">
                                <div>
                                    <div class="font-semibold text-base mb-1">Gestão de Agendamentos</div>
                                    <ul class="space-y-1">
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Calendário próprio e por profissional</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Kanban de agendamentos</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Reagendamento em massa</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Controle de disponibilidade e datas bloqueadas</span></li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="font-semibold text-base mb-1">Relacionamento & Marketing</div>
                                    <ul class="space-y-1">
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">CRM completo e Kanban de leads</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Campanhas de marketing e automações</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Notificações por e-mail e WhatsApp</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Lembretes inteligentes e templates personalizados</span></li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="font-semibold text-base mb-1">Automação & IA</div>
                                    <ul class="space-y-1">
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Agentes de IA e chat inteligente</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Automações personalizáveis</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Webhooks e integrações com sistemas</span></li>
                                    </ul>
                                </div>
                                <div>
                                    <div class="font-semibold text-base mb-1">Gestão Clínica Completa</div>
                                    <ul class="space-y-1">
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Cadastro de pacientes e especialistas</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Exames personalizados e planos de saúde</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Múltiplos métodos de pagamento</span></li>
                                        <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Dashboard intuitivo e personalização de temas</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 mt-2">
                                <x-button label="Testar grátis por 30 dias" class="btn-primary" icon-right="o-arrow-right" link="https://agendaclinic.com/register" target="_blank" />
                                <x-button label="Ver recursos completos" class="btn-primary btn-outline" link="https://agendaclinic.com/" target="_blank" />
                            </div>
                        </div>
                        <div class="bg-gradient-to-br from-secondary/5 to-primary/5 p-8 lg:p-12 flex items-center justify-center overflow-x-clip">
                            <div class="opacity-0 delay-1000" x-data="{shown2:false}" x-intersect="shown2=true" :class="{'fade-in-left':shown2}">
                                <x-ui.mockups.iphone class="lg:animate-float" side="right"
                                    contact-name="Agenda Clinic" photo_url="{{ asset('images/logotipo-agendaclinic.png') }}"
                                    :messages="[
                                        ['content' => 'Olá! Gostaria de agendar uma consulta.', 'sent' => false, 'time' => '09:00'],
                                            ['content' => 'Olá! Claro, posso ajudar. Para qual especialidade?', 'sent' => true, 'time' => '09:01'],
                                            ['content' => 'Clínico geral.', 'sent' => false, 'time' => '09:01'],
                                            ['content' => 'Perfeito! Estes são os horários disponíveis para hoje:', 'sent' => true, 'time' => '09:02'],
                                            ['content' => '09:30, 10:00, 11:15, 14:00 ou 16:45. Qual prefere?', 'sent' => true, 'time' => '09:02'],
                                            ['content' => 'Pode ser às 10:00.', 'sent' => false, 'time' => '09:03'],
                                            ['content' => 'Consulta agendada para hoje às 10:00 com clínico geral. Precisa de mais alguma coisa?', 'sent' => true, 'time' => '09:03'],
                                            ['content' => 'Não, obrigado!', 'sent' => false, 'time' => '09:04'],
                                            ['content' => 'De nada! Até logo.', 'sent' => true, 'time' => '09:04'],
                                    ]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>