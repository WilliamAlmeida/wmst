<div class="space-y-6">
    <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm bg-gradient-card border-neutral-content">
        <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6">
            <div class="leading-none font-semibold">Prefere Falar Diretamente Conosco?</div>
            <div class="text-muted-foreground text-sm">Nossa equipe de especialistas está pronta para atender você</div>
        </div>
        <div class="px-6 space-y-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                    <x-icon name="o-phone" class="h-5 w-5 text-primary" />
                </div>
                <div>
                    <p class="font-semibold">(12) 98218-4879</p>
                    <p class="text-sm text-muted-foreground">WhatsApp Business disponível 24/7</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                    <x-icon name="o-envelope" class="h-5 w-5 text-primary" />
                </div>
                <div>
                    <p class="font-semibold">contato@wmst.com.br</p>
                    <p class="text-sm text-muted-foreground">Resposta garantida em até 2 horas</p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm bg-gradient-card border-neutral-content">
        <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6">
            <div class="leading-none font-semibold">Horário de Atendimento Especializado</div>
        </div>
        <div class="px-6">
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm">Segunda - Sexta</span>
                    <span class="text-sm font-semibold text-primary">8h às 18h</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Sábado</span>
                    <span class="text-sm font-semibold text-primary">8h às 12h</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Assistente IA</span>
                    <span class="text-sm font-semibold text-primary">24/7 Sempre Online</span>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <x-button label="Fale com a W.M." class="btn-primary" 
            no-wire-navigate target="_blank" rel="noopener noreferrer" link="{{ $url_whatsapp.'&text= Olá, gostaria de falar com a W.M.!' }}"
        />
        <x-button label="Agende uma Reunião" class="btn-primary btn-outline"
            no-wire-navigate target="_blank" rel="noopener noreferrer" link="{{ $url_whatsapp.'&text= Olá, gostaria de agendar uma reunião!' }}"
        />
    </div>
</div>