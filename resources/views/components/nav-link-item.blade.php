@props(['href', 'active', 'icon', 'label', 'badge' => null])

<a href="{{ $href }}" 
   class="group flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 
   {{ $active ? 'bg-white/10 text-white border-l-4 border-white shadow-sm' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
    
    <div class="flex items-center">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 mr-4 {{ $active ? 'text-white' : 'text-white/30 group-hover:text-white' }}"></i>
        <span class="text-[13px] font-medium tracking-wide">{{ $label }}</span>
    </div>

    @if($badge)
        <span class="bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
            {{ $badge }}
        </span>
    @endif
</a>