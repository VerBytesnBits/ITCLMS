<div 
    x-data="{ show: false }"
    x-init="
        @if (session('alert'))
            show = true;
            Swal.fire({
                toast: true,
                position: '{{ session('alert')['position'] ?? 'top-end' }}',
                icon: '{{ session('alert')['type'] ?? 'info' }}',
                title: '{{ session('alert')['title'] ?? '' }}',
                text: '{{ session('alert')['text'] ?? '' }}',
                background: '{{ session('alert')['background'] ?? '#fff' }}',
                color: '{{ session('alert')['color'] ?? '#000' }}',
                iconHtml: `{!! session('alert')['iconHtml'] ?? '' !!}`,
                showConfirmButton: {{ session('alert')['confirmButton'] ?? 'false' }},
                timer: {{ session('alert')['timer'] ?? 5000 }},
                timerProgressBar: true,
                willClose: () => show = false
            });
        @endif
    "
> </div>
{{-- <div x-show="show" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40"></div> --}}