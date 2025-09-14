<section class="pt-10 sm:pt-20 pb-20 lg:min-h-[calc(100vh_-_80px)] lg:py-32 bg-gradient-to-br from-[#0066cc08] via-transparent to-[#cc006608] relative">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left: Textual Content --}}
            <div class="fade-in-left">
                {{-- Badge --}}
                <span class="text-xs mb-6 badge bg-primary/10 text-primary border-primary/20 font-medium py-0.5 px-2 rounded-md">+15 Anos Transformando Negócios com Técnologia</span>

                {{-- Title --}}
                <h1 class="text-4xl lg:text-6xl font-bold mb-6 min-h-30 lg:min-h-48">
                    <span class="hero-typed-text"></span>
                </h1>

                {{-- Description --}}
                <p class="text-xl mb-8 text-pretty">
                    Especialistas em IA, automações de WhatsApp, Telegram, Instagram e sistemas customizados. Transformamos processos manuais em soluções automatizadas que economizam tempo e aumentam lucros
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <x-button label="Solicitar Demonstração" class="btn-primary" icon-right="o-arrow-right" />
                    <x-button label="Fale com a W.M." class="btn-outline btn-primary" />
                </div>

                {{-- Stats --}}
                <div class="mt-12 grid grid-cols-3 gap-6 pt-8 border-t border-neutral-content">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">500+</div>
                        <div class="text-sm text-muted-foreground">Projetos Entregues</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">98%</div>
                        <div class="text-sm text-muted-foreground">Satisfação Cliente</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">24/7</div>
                        <div class="text-sm text-muted-foreground">Suporte IA</div>
                    </div>
                </div>
            </div>

            {{-- Right: Image --}}
            <div class="relative">
                <img alt="{{ config('app.name') }} Logo" src="{{ asset('images/logo-wmst.png') }}"
                    class="w-full max-w-md mx-auto opacity-0 duration-[2s]"
                    x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}"
                />
                <div class="absolute -inset-4 bg-gradient-to-r from-primary/20 to-secondary/20 rounded-lg blur-xl opacity-30"></div>
            </div>
        </div>
        <span class="hidden lg:block animate-bounce absolute bottom-0 left-1/2">
            <x-icon name="phosphor.caret-down-thin" class="h-10 opacity-20" />
        </span>
    </div>
</section>