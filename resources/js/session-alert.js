import Swal from 'sweetalert2';

window.Swal = Swal;


window.addEventListener('swal', e => {
    const options = e.detail || {};
    if (options.toast) {
        Swal.fire({
            icon: options.icon || 'info',
            title: options.title || '',
            timer: options.timer || 3000,
            showConfirmButton: true,
            timerProgressBar: true,
        });
    } else {
        Swal.fire(options);
    }
});

