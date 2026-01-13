{{-- Hero Section --}}
@include('livewire.public.landing-pages.sections.navbar')
<div class="w-full pt-32 pb-16 px-4 md:px-0 bg-white" id="inicio">
    <div class="max-w-4xl mx-auto text-center flex flex-col items-center">
        
        <h1 class="text-4xl md:text-[3.5rem] font-bold text-gray-900 mb-6 leading-[1.1] tracking-tight">
            Seu Atendimento no WhatsApp<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">100% Automatizado com IA</span>
        </h1>
        
        <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-2xl leading-relaxed">
            Seu agente de IA atende, agenda, organiza, cobra e acompanha seus clientes 24/7. 
            Configure uma vez, automatize para sempre — sem escrever código.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 mb-8 w-full justify-center">
            <a href="https://chat.ibox.delivery/login" class="bg-black text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-gray-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1 w-full sm:w-auto">
                Testar Grátis | Login
            </a>
            <a href="https://wa.me/5512982184879" class="bg-white border-2 border-gray-200 text-gray-700 px-8 py-4 rounded-xl font-bold text-lg hover:border-green-500 hover:text-green-600 transition-all hover:-translate-y-1 flex items-center justify-center gap-2 w-full sm:w-auto">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.463 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                Falar no WhatsApp
            </a>
        </div>

        {{-- <a href="https://chat.ibox.delivery/whitelabel" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition-colors mb-12">
            🚀 Quer revender? Conheça o Whitelabel
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a> --}}

        <div class="flex flex-wrap justify-center gap-4 text-sm font-medium text-gray-500 mb-16">
            <span class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-100">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                No-Code
            </span>
            <span class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-100">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                IA Avançada
            </span>
            <span class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-100">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                WhatsApp Oficial
            </span>
            <span class="flex items-center gap-2 px-3 py-1 bg-gray-50 rounded-full border border-gray-100">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                100% Automatizado
            </span>
        </div>

        <div class="w-full max-w-5xl rounded-2xl overflow-hidden shadow-2xl border border-gray-200">
             <img src="{{ asset('images/mockup-dashboard.png') }}" class="w-full h-auto" alt="Chat iBox Dashboard" onerror="this.src='https://placehold.co/1200x800/f3f4f6/3b82f6?text=Dashboard+Preview'">
        </div>
    </div>
</div>
