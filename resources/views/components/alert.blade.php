@if (session()->has('alert'))
    <div 
        x-data="{
            show: true,
            init() {
                Swal.fire({
                    icon: '{{ session('alert')['type'] ?? 'info' }}',
                    text: '{{ session('alert')['message'] ?? '' }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'bottom-end',
                });
            }
        }"
        x-init="init()"
    ></div>
@endif
