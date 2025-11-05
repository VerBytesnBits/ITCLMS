<x-layouts.app.sidebar-itclms :title="$title ?? null">
    <div
        class="bg-gradient-to-br bg-blue-100
         dark:from-slate-700 dark:via-slate-800 dark:to-slate-700
           border border-zinc-300 dark:border-zinc-600
           rounded-xl shadow-xl p-8">
        {{ $slot }}
    </div>
</x-layouts.app.sidebar-itclms>
{{-- <x-toast /> --}}

<x-alert />
<x-scroll-to-up />
