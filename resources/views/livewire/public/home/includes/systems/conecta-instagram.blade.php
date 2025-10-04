<div id="conecta-instagram" class="opacity-0 delay-500 duration-1000" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
    <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm overflow-hidden border-neutral-content/50 hover:border-primary/30 transition-all duration-300">
        <div class="grid lg:grid-cols-2 gap-0">
            <div class="p-8 lg:p-12">
                <div class="flex items-center gap-3 mb-2">
                    <x-ui.icons.bot class="text-primary h-8 w-8" />
                    <span class="text-xs badge bg-primary/10 text-primary border-primary/20 font-medium py-0.5 px-2 rounded-md">Automações com Instagram</span>
                </div>
                <div class="font-bold text-3xl lg:text-4xl mb-2 gradient-text">Conecta</div>
                <div class="text-base mb-4">A plataforma completa para gerenciamento de múltiplas contas do Instagram, automatização de mensagens e publicações. Otimize seu atendimento e marketing com integração avançada de API, webhooks e inteligência artificial.</div>
                <div class="grid md:grid-cols-2 gap-4 mb-8">
                    <div>
                        <div class="font-semibold text-base mb-1">Gerenciamento de Instagram</div>
                        <ul class="space-y-1">
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Multi-tenancy para múltiplas agências</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Perfis e métricas de usuários</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Gestão de mídias e publicações</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Tokens de acesso seguros</span></li>
                        </ul>
                    </div>
                    <div>
                        <div class="font-semibold text-base mb-1">Mensagens & Engajamento</div>
                        <ul class="space-y-1">
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Envio de mensagens automatizadas</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Respostas privadas a comentários</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Envio de stickers e reações</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Compartilhamento de posts publicados</span></li>
                        </ul>
                    </div>
                    <div>
                        <div class="font-semibold text-base mb-1">Publicação de Conteúdo</div>
                        <ul class="space-y-1">
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Posts de imagem e vídeo</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Carrosséis com múltiplas mídias</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Stories e Reels automatizados</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Monitoramento de limites de publicação</span></li>
                        </ul>
                    </div>
                    <div>
                        <div class="font-semibold text-base mb-1">Integrações & API</div>
                        <ul class="space-y-1">
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">API RESTful documentada</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Webhooks em tempo real</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Integração com chatbots e IA</span></li>
                            <li class="flex items-center gap-x-2"><x-icon name="phosphor.check-circle" class="text-primary" /><span class="text-sm">Dashboard personalizado e responsivo</span></li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-2">
                    <x-button label="Testar grátis por 30 dias" class="btn-primary" icon-right="o-arrow-right" link="https://conecta.wmst.com.br/register" target="_blank" no-wire-navigate />
                    <x-button label="Ver documentação da API" class="btn-primary btn-outline" link="https://conecta.wmst.com.br/docs/api" target="_blank" no-wire-navigate />
                </div>
            </div>
            <div class="bg-gradient-to-br from-secondary/5 to-primary/5 p-8 lg:p-12 flex items-center justify-center overflow-x-clip">
                <div class="opacity-0 delay-1000" x-data="{shown2:false}" x-intersect="shown2=true" :class="{'fade-in-left':shown2}">
                    <x-ui.mockups.iphone class="lg:animate-float" side="right"
                        contact-name="Conecta Instagram" photo_url="{{ asset('images/logotipo-conecta.png') }}"
                        :messages="[
                            ['content' => 'Olá! Quero automatizar as mensagens do Instagram da minha loja.', 'sent' => false, 'time' => '09:00'],
                                ['content' => 'Olá! Claro, o Conecta pode ajudar com isso. Que tipo de automação você precisa?', 'sent' => true, 'time' => '09:01'],
                                ['content' => 'Preciso responder dúvidas frequentes e agendar atendimentos.', 'sent' => false, 'time' => '09:01'],
                                ['content' => 'Perfeito! Com nossa API você pode integrar respostas automáticas e até mesmo publicar conteúdo programado.', 'sent' => true, 'time' => '09:02'],
                                ['content' => 'Também oferecemos webhooks para notificações em tempo real das mensagens recebidas.', 'sent' => true, 'time' => '09:02'],
                                ['content' => 'Isso parece ótimo! Como começar?', 'sent' => false, 'time' => '09:03'],
                                ['content' => 'Basta criar uma conta gratuita e configurar sua integração com o Instagram. Temos 30 dias de teste sem compromisso.', 'sent' => true, 'time' => '09:03'],
                                ['content' => 'Vou me cadastrar agora mesmo!', 'sent' => false, 'time' => '09:04'],
                                ['content' => 'Ótimo! Estamos à disposição para ajudar na configuração.', 'sent' => true, 'time' => '09:04'],
                        ]"
                    />
                </div>
            </div>
        </div>
    </div>
</div>