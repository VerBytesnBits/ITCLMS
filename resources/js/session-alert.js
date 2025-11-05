import "./room-alert";
import Swal from "sweetalert2";

window.Swal = Swal;

// Mixin for top-end toast
window.Notification = Swal.mixin({
    toast: true,
    position: "top-end", // top-right stacking
    showConfirmButton: false,
    timerProgressBar: true,
    target: document.body,
    customClass: {
        popup: "rounded-xl shadow-lg text-white p-3",
    },
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

// Fire a single toast
function fireToast(options) {
    if (!options) return Promise.resolve();

    let bgColor = "#374151";
    switch (options.icon) {
        case "success":
            bgColor = "#16a34a";
            break;
        case "error":
            bgColor = "#dc2626";
            break;
        case "warning":
            bgColor = "#d97706";
            break;
        case "info":
            bgColor = "#2563eb";
            break;
    }

    return new Promise((resolve) => {
        window.Notification.fire({
            icon: options.icon || "info",
            title: options.title || "",
            background: bgColor,
            iconColor: "#fff",
            timer: options.timer ?? 4000,   
            didClose: () => resolve(),
        });
    });
}   



window.addEventListener("swal", (event) => {
    const detail = event.detail;

    if (!detail) return;

    if (Array.isArray(detail)) {
        // Handle multiple alerts (batch)
        detail.forEach((alert) => {
            Swal.fire({
                toast: true,
                position: alert.position || "top-end",
                icon: alert.icon || "info",
                title: alert.title || "",
                text: alert.text || "",
                showConfirmButton: false,
                timer: alert.timer || 4000,
                timerProgressBar: true,
            });
        });
    } else {
        // Handle single alert
        Swal.fire({
            toast: true,
            position: detail.position || "top-end",
            icon: detail.icon || "info",
            title: detail.title || "",
            text: detail.text || "",
            showConfirmButton: false,
            timer: detail.timer || 4000,
            timerProgressBar: true,
        });
    }
});

        
