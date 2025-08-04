import Swal from 'sweetalert2';

window.addEventListener('swal', e => {
    const options = e.detail || {};
    if (options.toast) {
        Swal.fire({
            toast: true,
            icon: options.icon || 'info',
            title: options.title || '',
            timer: options.timer || 3000,
            position: options.position || 'top-end',
            showConfirmButton: false,
            timerProgressBar: true,
        });
    } else {
        Swal.fire(options);
    }
});
