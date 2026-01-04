import "./room-alert";
import Swal from "sweetalert2";

window.Swal = Swal;

// 🔹 Toast Mixin (Reusable Top-End Toast)
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timerProgressBar: true,
    target: document.body,
    customClass: { popup: "rounded-xl shadow-lg text-white p-3" },
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

// 🔹 Fire a single toast programmatically
window.fireToast = async function ({ icon = "info", title = "", timer = 4000 }) {
    const colors = {
        success: "#16a34a",
        error: "#dc2626",
        warning: "#d97706",
        info: "#2563eb",
    };

    const background = colors[icon] || "#374151";

    return Toast.fire({
        icon,
        title,
        background,
        iconColor: "#fff",
        timer,
    });
};

// 🔹 Helper to handle batch alerts
function handleAlerts(alerts) {
    alerts.forEach((alert) => {
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
}

// 🔹 Listen for Livewire `swal` events
window.addEventListener("swal", (event) => {
    const detail = event.detail;

    if (!detail) return;

    if (Array.isArray(detail)) {
        handleAlerts(detail);
    } else {
        handleAlerts([detail]);
    }
});
