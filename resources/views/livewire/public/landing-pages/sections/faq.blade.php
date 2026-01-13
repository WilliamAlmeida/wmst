{{-- FAQ Section --}}
<div class="w-full py-20 px-4 relative bg-gray-50" id="faq">
    <div class="max-w-4xl mx-auto z-10 relative">
        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 text-center">Perguntas Frequentes</h2>
        <p class="text-xl text-gray-600 mb-16 text-center">Tire suas dúvidas sobre a Chat iBox</p>
        
        <div class="space-y-4" x-data="{ active: null }">
            {{-- Item 1 --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 1 ? null : 1" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    Como funciona a conexão com o WhatsApp?
                    <span x-show="active !== 1">▼</span><span x-show="active === 1">▲</span>
                </button>
                <div x-show="active === 1" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    Você conecta seu WhatsApp lendo um QR Code, exatamente como no WhatsApp Web. O processo leva menos de 2 minutos e funciona tanto para WhatsApp pessoal quanto Business.
                </div>
            </div>

            {{-- Item 2 --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 2 ? null : 2" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    Preciso saber programar para usar?
                    <span x-show="active !== 2">▼</span><span x-show="active === 2">▲</span>
                </button>
                <div x-show="active === 2" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    Não! A plataforma é 100% No-Code. Você configura o comportamento do agente através de instruções em linguagem natural e configurações visuais simples.
                </div>
            </div>

            {{-- Item 3 --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 3 ? null : 3" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    Posso personalizar as respostas do agente?
                    <span x-show="active !== 3">▼</span><span x-show="active === 3">▲</span>
                </button>
                <div x-show="active === 3" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    Sim, você define a "persona" do agente, o tom de voz, e fornece uma base de conhecimento (como PDFs, sites, textos) que ele usará para responder.
                </div>
            </div>

             {{-- Item 4 --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 4 ? null : 4" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    O agente consegue enviar imagens e áudios?
                    <span x-show="active !== 4">▼</span><span x-show="active === 4">▲</span>
                </button>
                <div x-show="active === 4" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    Sim! O agente pode enviar imagens (catálogos, comprovantes), áudios (transcritos ou gerados por IA com voz natural) e documentos.
                </div>
            </div>

             {{-- Item 5 --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 5 ? null : 5" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    Como funciona o agendamento automático?
                    <span x-show="active !== 5">▼</span><span x-show="active === 5">▲</span>
                </button>
                <div x-show="active === 5" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    O agente tem acesso em tempo real ao seu Google Calendar. Ele verifica disponibilidade, oferece horários livres ao cliente e cria o evento na agenda automaticamente.
                </div>
            </div>
            
             {{-- Item 6 --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 6 ? null : 6" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    Posso usar minha própria chave de IA?
                    <span x-show="active !== 6">▼</span><span x-show="active === 6">▲</span>
                </button>
                <div x-show="active === 6" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    Sim, você pode conectar sua própria chave da OpenAI, Anthropic ou outras, pagando apenas pelo que consumir diretamente ao fornecedor da IA.
                </div>
            </div>

             {{-- Item 7 --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <button @click="active = active === 7 ? null : 7" class="w-full text-left px-6 py-4 font-bold text-gray-900 flex justify-between items-center hover:bg-gray-50 transition-colors">
                    Posso testar antes de contratar?
                    <span x-show="active !== 7">▼</span><span x-show="active === 7">▲</span>
                </button>
                <div x-show="active === 7" x-collapse class="px-6 py-4 text-gray-600 bg-gray-50 border-t border-gray-100">
                    Com certeza! Oferecemos um período de teste gratuito com todos os recursos liberados para você validar a ferramenta no seu negócio.
                </div>
            </div>

        </div>

        <div class="text-center mt-12">
            <p class="text-gray-600 mb-4">Ainda tem dúvidas?</p>
            <a href="https://wa.me/5512982184879" class="inline-flex items-center justify-center gap-2 text-blue-600 font-bold hover:underline">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                Fale conosco pelo WhatsApp
            </a>
        </div>
    </div>
</div>