<div>
<!-- resources/views/partials/scroll-to-up.blade.php -->
<button x-data x-show="window.scrollY > 50"
    x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
    x-cloak
    class="fixed bottom-5 right-5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full shadow-lg transition hidden md:block">
    ⬆
</button>
</div>