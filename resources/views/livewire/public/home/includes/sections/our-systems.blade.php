<section id="sistemas" class="py-20 bg-gray-50 overflow-y-clip">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16 opacity-0 duration-[1s]" x-data="{shown:false}" x-intersect="shown=true" :class="{'fade-in':shown}">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">Nossos <span class="gradient-text">Sistemas Inteligentes</span></h2>
            <p class="text-xl max-w-2xl mx-auto">Cada sistema foi desenvolvido para resolver problemas específicos e gerar resultados mensuráveis</p>
        </div>

        <div class="space-y-20">
            @include('livewire.public.home.includes.systems.ibox-delivery')
    
            @include('livewire.public.home.includes.systems.agenda-clinic')

            @include('livewire.public.home.includes.systems.conecta-instagram')
        </div>
    </div>
</section>