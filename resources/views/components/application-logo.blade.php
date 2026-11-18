@props(['class' => 'h-10 w-auto'])

<div class="inline-flex items-center gap-2.5">
    <img 
        src="{{ route('logo') }}" 
        alt="Lingga Air" 
        class="{{ $class }} object-contain"
        onerror="this.onerror=null; this.src='https://placehold.co/180x60/619DC6/FFFFFF?text=Lingga+Air';"
    >
</div>
