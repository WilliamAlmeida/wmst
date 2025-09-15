<footer class="py-10 bg-card/50 border-t border-neutral-content">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <x-logotipo-wmst class="w-12 h-12" />
                    <div>
                        <h3 class="font-bold text-xl">{{ config('app.name') }}</h3>
                        <p class="text-sm text-muted-foreground">Software House & Automações com IA</p>
                    </div>
                </div>
                <p class="text-muted-foreground mb-6 max-w-md">Há mais de 15 anos transformando negócios com tecnologia inteligente. Especialistas em automações de WhatsApp, IA e sistemas customizados que geram resultados reais.</p>
                <div class="space-y-3">
                    <h4 class="font-semibold text-primary">Conecte-se Conosco</h4>
                    <div class="flex gap-3">
                        {{-- <x-button label="LinkedIn" icon="phosphor.linkedin-logo" /> --}}
                        <x-button label="Instagram" icon="phosphor.instagram-logo" />
                        {{-- <x-button label="GitHub" icon="phosphor.github-logo" /> --}}
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-primary">Nossos Sistemas</h4>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#ibox-delivery">IBOX DELIVERY</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#agenda-clinic">AI CLINIC</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes">Automação WhatsApp</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes">Sistemas Customizados</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes">Consultoria em IA</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes">Integração de Sistemas</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-primary">Contato Direto</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-2">
                        <x-icon name="o-envelope" class="h-4 w-4 text-primary" />
                        <span class="text-muted-foreground">contato@wmst.com.br</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="o-phone" class="h-4 w-4 text-primary" />
                        <span class="text-muted-foreground">(12) 98218-4879</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="phosphor.whatsapp-logo" class="h-4 w-4 text-primary flex-shrink-0" />
                        <span class="text-muted-foreground">WhatsApp Business 24/7</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="o-map-pin" class="h-4 w-4 text-primary flex-shrink-0" />
                        <span class="text-muted-foreground">São Paulo, SP - Brasil</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-neutral-content pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-muted-foreground">© {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
                <div class="flex gap-4 text-sm text-muted-foreground">
                    <span class="hover:text-primary cursor-pointer transition-colors">Política de Privacidade</span>
                    <span class="hover:text-primary cursor-pointer transition-colors">Termos de Uso</span>
                    <span class="hover:text-primary cursor-pointer transition-colors">Suporte Técnico</span>
                </div>
            </div>
        </div>
    </div>
</footer>