<div class="mx-auto max-w-screen-lg">
   <div class="bg-gradient-to-br from-secondary/5 to-primary/5 p-8 lg:p-12 flex items-center justify-center overflow-x-clip min-h-screen">
         <div id="agenda-clinic" class="opacity-0 delay-500 duration-1000" wire:ignore x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
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
               <x-button label="Testar grátis por 30 dias" class="btn-primary text-white" icon-right="o-arrow-right" link="https://agendaclinic.com/register" target="_blank" />
               <x-button label="Ver recursos completos" class="btn-primary btn-outline" link="https://agendaclinic.com/" target="_blank" />
            </div>
            <div class="flex flex-col sm:flex-row gap-3 mt-2">
               <x-button label="Calculadora" class="btn-success" link="{{ route('calculadora.agenda-clinic') }}" target="calculadora" />
            </div>
         </div>         
   </div>

   @teleport('body')
      <vapi-widget
         public-key="d845b589-5046-45e1-ac1f-d7aebaef456b"
         assistant-id="1a3e2034-a94a-41b9-8466-0e6d0931f389"
         mode="hybrid"
         theme="light"
         base-bg-color="#ffffff"
         accent-color="var(--color-primary)"
         cta-button-color="#3c62e9"
         cta-button-text-color="#fff"
         border-radius="large"
         size="full"
         position="bottom-center"
         title="Stephany IA – Sua Assistente Virtual"
         start-button-text="Falar com a Stephany"
         end-button-text="Encerrar Conversa"
         cta-title="Experimente a Stephany IA"
         chat-first-message="Olá! Como posso ajudar você hoje?"
         chat-placeholder="Digite sua mensagem aqui..."
         voice-show-transcript="false"
         consent-required="true"
         consent-title="Termos e Condições"
         consent-content="Ao clicar em 'Concordo' e interagir com esta assistente, você autoriza o registro, armazenamento e compartilhamento das suas comunicações conforme nossa Política de Privacidade e Termos de Uso."
         consent-storage-key="vapi_widget_consent"
         ></vapi-widget>
   @endteleport
</div>

@push('scripts')
<script src="https://unpkg.com/@vapi-ai/client-sdk-react/dist/embed/widget.umd.js" async type="text/javascript"></script>
@endpush