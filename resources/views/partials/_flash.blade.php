@php
    $map = [
        'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'icon' => 'check-circle'],
        'error'   => ['bg' => 'bg-rose-50',    'border' => 'border-rose-200',    'text' => 'text-rose-800',    'icon' => 'x-mark'],
        'warning' => ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-800',   'icon' => 'information-circle'],
        'info'    => ['bg' => 'bg-sky-50',     'border' => 'border-sky-200',     'text' => 'text-sky-800',     'icon' => 'information-circle'],
        'status'  => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-800', 'icon' => 'check-circle'],
    ];
@endphp

@if(session('success') || session('error') || session('warning') || session('info') || session('status'))
    @php $type = session('error') ? 'error' : (session('warning') ? 'warning' : (session('info') ? 'info' : (session('status') ? 'status' : 'success'))); @endphp
    <div id="flash-banner"
         class="fixed left-1/2 top-4 z-[1000] -translate-x-1/2 rounded-2xl border {{ $map[$type]['border'] }} {{ $map[$type]['bg'] }} {{ $map[$type]['text'] }} px-4 py-3 shadow-[0_8px_24px_rgba(15,23,42,.08)] flex items-start gap-3 max-w-[560px]"
         role="alert">
        <x-icon :name="$map[$type]['icon']" class="h-5 w-5 mt-0.5 flex-shrink-0" />
        <div class="text-sm leading-relaxed">
            {{ session($type) ?: session('status') }}
        </div>
        <button type="button" class="ml-2 -mr-1 opacity-70 hover:opacity-100" onclick="this.closest('#flash-banner').remove()" aria-label="Tutup">
            <x-icon name="x-mark" class="h-4 w-4" />
        </button>
    </div>
@endif
