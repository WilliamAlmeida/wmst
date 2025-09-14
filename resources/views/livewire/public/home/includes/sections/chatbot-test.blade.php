<div data-slot="card" class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm bg-gradient-card border-neutral-content fade-in-left">
    <div data-slot="card-header" class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center">
                <x-ui.icons.bot class="h-5 w-5 text-white" />
            </div>
            <div>
                <div data-slot="card-title" class="leading-none font-semibold">Assistente W.M. IA</div>
                <div data-slot="card-description" class="text-muted-foreground text-sm flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>Online agora - Powered by IA
                </div>
            </div>
        </div>
    </div>
    <div data-slot="card-content" class="px-6 space-y-4">
        <div class="space-y-3 h-80 overflow-y-auto bg-muted/30 rounded-lg p-4 border border-neutral-content bg-gray-50">
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center flex-shrink-0">
                    <x-ui.icons.bot class="h-5 w-5 text-white" />
                </div>
                <div class="bg-white rounded-lg p-3 border border-neutral-content/50 max-w-xs">
                    <p class="text-sm">Olá! 👋 Sou o assistente inteligente da W.M. Como posso transformar seu negócio hoje?</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center flex-shrink-0">
                    <x-ui.icons.bot class="h-5 w-5 text-white" />
                </div>
                <div class="bg-white rounded-lg p-3 border border-neutral-content/50 max-w-xs">
                    <p class="text-sm mb-2">Posso te ajudar com:</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-zap h-3 w-3 text-primary">
                                <path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path>
                            </svg>
                            <span>Automação de WhatsApp Business</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-brain h-3 w-3 text-primary">
                                <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"></path>
                                <path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"></path>
                                <path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"></path>
                                <path d="M17.599 6.5a3 3 0 0 0 .399-1.375"></path>
                                <path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"></path>
                                <path d="M3.477 10.896a4 4 0 0 1 .585-.396"></path>
                                <path d="M19.938 10.5a4 4 0 0 1 .585.396"></path>
                                <path d="M6 18a4 4 0 0 1-1.967-.516"></path>
                                <path d="M19.967 17.484A4 4 0 0 1 18 18"></path>
                            </svg>
                            <span>Sistemas com IA integrada</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-workflow h-3 w-3 text-primary">
                                <rect width="8" height="8" x="3" y="3" rx="2"></rect>
                                <path d="M7 11v4a2 2 0 0 0 2 2h4"></path>
                                <rect width="8" height="8" x="13" y="13" rx="2"></rect>
                            </svg>
                            <span>Automação de processos internos</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target h-3 w-3 text-primary">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="6"></circle>
                                <circle cx="12" cy="12" r="2"></circle>
                            </svg>
                            <span>Agendamento de demonstração</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center flex-shrink-0">
                    <x-ui.icons.bot class="h-5 w-5 text-white" />
                </div>
                <div class="bg-white rounded-lg p-3 border border-neutral-content/50 max-w-xs">
                    <p class="text-sm">Qual área do seu negócio você gostaria de automatizar primeiro? 🚀</p>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <div class="flex-1">
                <x-input placeholder="Digite sua mensagem..." />
            </div>
            <x-button icon="phosphor.paper-plane-tilt" class="btn-primary" />
        </div>
        <x-button label="Solicitar Demonstração Real" class="btn-primary w-full" icon-right="o-arrow-right" />
    </div>
</div>