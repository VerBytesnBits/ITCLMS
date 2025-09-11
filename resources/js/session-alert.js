import Swal from 'sweetalert2';
window.Swal = Swal;

// Mixin for top-end toast
window.Notification = Swal.mixin({
    toast: true,
    position: 'top-end', // top-right stacking
    showConfirmButton: false,
    timerProgressBar: true,
    target: document.body,
    customClass: {
        popup: 'rounded-xl shadow-lg text-white p-3'
    },
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Fire a single toast
function fireToast(options) {
    if (!options) return Promise.resolve();

    let bgColor = '#374151';
    switch (options.icon) {
        case 'success': bgColor = '#16a34a'; break;
        case 'error': bgColor = '#dc2626'; break;
        case 'warning': bgColor = '#d97706'; break;
        case 'info': bgColor = '#2563eb'; break;
    }

    return new Promise(resolve => {
        window.Notification.fire({
            icon: options.icon || 'info',
            title: options.title || '',
            background: bgColor,
            iconColor: '#fff',
            timer: options.timer ?? 4000,
            didClose: () => resolve()
        });
    });
}

// Queue multiple alerts sequentially
async function queueAlerts(alerts) {
    for (const alert of alerts) {
        await fireToast(alert);
        await new Promise(res => setTimeout(res, 150)); // small gap between toasts
    }
}

// window.addEventListener('swal-batch', async (e) => {
//     let alerts = e.detail;

//     console.log('swal-batch event:', alerts);

//     if (!Array.isArray(alerts) || alerts.length === 0) return;

//     // Flatten one level if nested
//     if (Array.isArray(alerts[0])) {
//         alerts = alerts.flat(); // now we have a flat array of objects
//     }

//     for (const alert of alerts) {
//         await fireToast(alert);
//         await new Promise(res => setTimeout(res, 150)); // small gap
//     }
// });




// Unified listener for single or batch alerts
window.addEventListener('swal', async (e) => {
    const detail = e.detail;

    if (!detail) return;

    // If detail is an array → batch
    if (Array.isArray(detail)) {
        for (const alert of detail) {
            await fireToast(alert);
            await new Promise(res => setTimeout(res, 200)); // small gap
        }
    } 
    // If detail is a single object → single toast
    else {
        await fireToast(detail);
    }
});

