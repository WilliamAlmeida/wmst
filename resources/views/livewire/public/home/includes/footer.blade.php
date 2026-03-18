<footer class="py-10 bg-card/50 border-t border-neutral-content">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-12">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <x-logotipo-wmst class="w-12 h-12" />
                    <div>
                        <div class="font-bold text-xl">{{ config('app.name') }}</div>
                        <p class="text-sm text-muted-foreground">Software House & Automações com IA</p>
                    </div>
                </div>
                <p class="text-muted-foreground mb-6 max-w-md">Há mais de 15 anos transformando negócios com tecnologia inteligente. Especialistas em automações de WhatsApp, IA e sistemas customizados que geram resultados reais.</p>
                <div class="space-y-3">
                    <h2 class="font-semibold text-primary">Conecte-se Conosco</h2>
                    <div class="flex gap-3">
                        {{-- <x-button label="LinkedIn" icon="phosphor.linkedin-logo" /> --}}
                        <x-button label="Instagram" icon="phosphor.instagram-logo" link="https://www.instagram.com/wmst.sistemas" no-wire-navigate />
                        {{-- <x-button label="GitHub" icon="phosphor.github-logo" /> --}}
                    </div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-primary">Nossos Sistemas</h3>
                <ul class="space-y-2 text-sm text-muted-foreground">
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#ibox-delivery" alt="IBOX DELIVERY" title="IBOX DELIVERY">IBOX DELIVERY</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#agenda-clinic" alt="Agenda Clinic" title="Agenda Clinic">Agenda Clinic</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes" alt="Automação WhatsApp" title="Automação WhatsApp">Automação WhatsApp</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes" alt="Sistemas Customizados" title="Sistemas Customizados">Sistemas Customizados</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes" alt="Consultoria em IA" title="Consultoria em IA">Consultoria em IA</a>
                    </li>
                    <li class="hover:text-primary cursor-pointer transition-colors">
                        <a href="#solucoes" alt="Integração de Sistemas" title="Integração de Sistemas">Integração de Sistemas</a>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-primary">Contato Direto</h3>
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
                    <a href="{{ route('privacy-policy') }}" alt="Política de Privacidade" title="Política de Privacidade" class="hover:text-primary cursor-pointer transition-colors">Política de Privacidade</a>
                    <a href="{{ route('terms-use') }}" alt="Termos de Uso" title="Termos de Uso" class="hover:text-primary cursor-pointer transition-colors">Termos de Uso</a>
                    <span class="hover:text-primary cursor-pointer transition-colors">Suporte Técnico</span>
                </div>
            </div>
        </div>
    </div>
</footer>