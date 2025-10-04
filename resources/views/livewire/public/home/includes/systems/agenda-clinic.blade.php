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