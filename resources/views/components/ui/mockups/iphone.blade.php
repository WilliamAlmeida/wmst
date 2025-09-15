@props([
    'messages' => [
        // ['content' => 'Olá! Gostaria de agendar uma consulta.', 'sent' => false, 'time' => '09:00'],
    ],
    'contactName' => 'Cliente',
    'online' => true,
    'photo_url' => null,
    'side' => ''
])

<div {{ $attributes->merge(['class' => 'relative w-[320px] max-w-[320px] h-[640px] mx-auto perspective-normal select-none group animate-pause']) }}>
    <!-- iPhone frame -->
    <div
        @class([
            'mockup-iphone',
            'mockup-left' => $side === 'left',
            'mockup-right' => $side === 'right',
        ])
    >
        <!-- Notch -->
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-[40%] h-[30px] bg-black rounded-b-[20px] z-50 flex items-center justify-center">
            <div class="w-16 h-2 bg-zinc-800 rounded-full"></div>
        </div>
        
        <!-- Screen content -->
        <div class="absolute inset-0 bg-gray-100 flex flex-col">
            <!-- Status bar -->
            <div class="h-[40px] bg-gray-200 flex items-center justify-between px-4 pt-4 pb-1 z-40">
                <div class="text-xs font-semibold text-gray-600">{{ now()->format('H:i') }}</div>
                <div class="flex items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <div class="w-4 h-2 bg-gray-600 rounded-sm"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 3l-6 6m0 0V4m0 5h5M5 21l6-6m0 0v5m0-5H5" />
                    </svg>
                </div>
            </div>
            
            <!-- Chat header -->
            <div class="bg-gray-200 px-2 py-4 flex items-center border-b border-gray-300">
                <button class="mr-2">
                    <x-icon name="phosphor.arrow-left" class="h-5 w-5" />
                </button>
                <div class="relative flex-shrink-0 mr-2">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center overflow-clip pointer-events-none">
                        @if($photo_url)
                            <img src="{{ $photo_url }}" alt="Logo" class="object-contain">
                        @else
                            {{ substr($contactName, 0, 1) }}
                        @endif
                    </div>
                    @if ($online)
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-white rounded-full border-2 border-white">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h2 class="font-semibold text-gray-800">{{ $contactName }}</h2>
                    <p class="text-xs text-gray-500 text-[10px]">
                        visto por último às {{ now()->subMinutes(5)->format('H:i') }}
                    </p>
                </div>
                <div class="space-x-1 flex items-center">
                    <button>
                        <x-icon name="o-phone" class="h-5 w-5" />
                    </button>
                    <button>
                        <x-icon name="phosphor.dots-three-vertical" class="h-5 w-5" />
                    </button>
                </div>
            </div>
            
            <!-- Chat messages -->
            <div class="flex-1 py-4 pl-4 pr-[23px] group-hover:pr-2 overflow-y-hidden group-hover:overflow-y-auto space-y-3 bg-whatsapp">
                @foreach ($messages as $message)
                    <div class="flex {{ $message['sent'] ? 'justify-end' : 'justify-start' }}">
                        <div class="{{ $message['sent'] ? 'bg-[#d9fdd3]' : 'bg-white' }} text-gray-800 rounded-lg py-2 px-2 max-w-[80%] relative">
                            <p class="text-xs">{{ $message['content'] }}</p>
                            
                            <div class="flex items-center justify-end gap-x-1">
                                <span class="text-[10px] text-gray-500">
                                    {{ $message['time'] }}
                                </span>
                                <x-icon name="phosphor.checks" class="w-4 h-4 text-blue-500 {{ $message['sent'] ? '' : 'hidden' }}" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Chat input -->
            <div class="p-3 border-t border-gray-300 bg-gray-100 flex gap-x-2 items-center pointer-events-none">
                <div class="flex-1 flex items-center bg-white rounded-full px-3 py-2 gap-x-1">
                    <button class="flex items-center justify-center">
                        <x-icon name="phosphor.smiley" class="h-5 w-5" />
                    </button>
                    <input placeholder="Message..." class="bg-transparent text-gray-700 text-sm outline-none w-full">
                    <button class="flex items-center justify-center">
                        <x-icon name="phosphor.paperclip" class="h-5 w-5" />
                    </button>
                    <button class="flex items-center justify-center">
                        <x-icon name="phosphor.camera" class="h-5 w-5" />
                    </button>
                </div>
                <button class="w-8 h-8 btn btn-neutral btn-circle">
                    <x-icon name="o-arrow-right" class="h-4 w-4" />
                </button>
            </div>
            
            <!-- Home button / indicator -->
            <div class="h-1 w-1/3 bg-gray-300 mx-auto my-2 rounded-full"></div>
        </div>
    </div>
</div>
