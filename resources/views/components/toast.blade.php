<!-- resources/views/components/toast.blade.php -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 🔹 If session flash toast exists (persistent across reloads)
        @if (session('toast'))

            setTimeout(() => {
                Swal.fire({
                    icon: "{{ session('toast.icon') ?? 'info' }}",
                    title: "{{ session('toast.title') ?? 'Notification' }}",
                    toast: {{ session('toast.toast') ? 'true' : 'false' }},
                    position: "{{ session('toast.position') ?? 'top-end' }}",
                    showConfirmButton: false,
                    timer: {{ session('toast.timer') ?? 3000 }},
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            }, 300); // delay ensures it appears after redirect
        @endif


        // 🔹 Livewire-powered toasts (instant, no reload)
        Livewire.on('swal', (data) => {
            Swal.fire({
                icon: data.icon ?? 'info',
                title: data.title ?? 'Notification',
                toast: data.toast ?? true,
                position: data.position ?? 'top-end',
                showConfirmButton: false,
                timer: data.timer ?? 3000,
            });
        });
    });
</script>
