@props([
    'name',
    'role',
    'company',
    'testimonial',
    'icon' => 'ui.icons.users',
])
<div class="flex flex-col gap-6 rounded-xl border py-6 shadow-sm bg-gradient-card border-neutral-content hover:border-primary/30 transition-all duration-300 fade-in-up">
    <div class="@container/card-header grid auto-rows-min grid-rows-[auto_auto] items-start gap-1.5 px-6 has-data-[slot=card-action]:grid-cols-[1fr_auto] [.border-b]:pb-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-secondary/10 rounded-full flex items-center justify-center">
                <x-dynamic-component :component="$icon" class="h-6 w-6 text-primary" />
            </div>
            <div>
                <div class="font-semibold text-lg">{{ $name }}</div>
                <div class="text-muted-foreground text-sm">{{ $role }}, {{ $company }}</div>
            </div>
        </div>
        <div class="flex gap-1 mb-4">
            @for ($i = 0; $i < 5; $i++)
                <x-icon name="s-star" class="h-4 w-4 text-primary" />
            @endfor
        </div>
    </div>
    <div class="px-6">
        <p class="text-muted-foreground italic text-sm">"{{ $testimonial }}"</p>
    </div>
</div>
