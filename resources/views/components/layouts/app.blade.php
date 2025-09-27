<x-layouts.app.sidebar-itclms :title="$title ?? null">
    <div
        class="bg-gradient-to-br from-slate-200 via-slate-100 to-slate-300
         dark:from-slate-700 dark:via-slate-800 dark:to-slate-700
           border border-gray-300 dark:border-zinc-600
           rounded-xl shadow p-8">
        {{ $slot }}
    </div>


</x-layouts.app.sidebar-itclms>
<x-alert />
<x-scroll-to-up />
