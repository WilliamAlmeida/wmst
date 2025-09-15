<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-b-neutral-content">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-logotipo-wmst class="w-12 h-12" />
                <div>
                    <h1 class="font-bold text-lg">{{ config('app.name') }}</h1>
                    <p class="text-xs text-muted-foreground">Software House &amp; Automações IA</p>
                </div>
            </div>
            <nav class="hidden md:flex items-center gap-6 font-semibold">
                <a href="#sistemas" class="text-sm hover:text-primary transition-colors">Sistemas</a>
                <a href="#solucoes" class="text-sm hover:text-primary transition-colors">Soluções</a>
                <a href="#cases" class="text-sm hover:text-primary transition-colors">Cases</a>
                <a href="#contato" class="text-sm hover:text-primary transition-colors">Contato</a>
                <x-button label="Agende sua Consultoria" class="animate-pulse hover:animate-none bg-[#50ac43] text-white" icon="phosphor.whatsapp-logo"
                    no-wire-navigate target="_blank" rel="noopener noreferrer" link="{{ $url_whatsapp.'&text= Olá, gostaria de agendar uma consultoria!' }}"
                />
            </nav>
        </div>
    </div>
</header>