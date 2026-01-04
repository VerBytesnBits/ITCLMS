// BULK DELETE CONFIRM
window.addEventListener("bulk-delete-confirm", () => {
    Swal.fire({
        title: "Are you sure?",
        text: "Selected items will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete",
    }).then((result) => {
        if (result.isConfirmed) {
            window.Livewire.dispatch("bulk-delete-confirmed");
        }
    });
});

// BULK JUNK CONFIRM
window.addEventListener("bulk-junk-confirm", () => {
    Swal.fire({
        title: "Move to Junk?",
        text: "You can restore these items later.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#f59e0b",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, move",
    }).then((result) => {
        if (result.isConfirmed) {
            window.Livewire.dispatch("bulk-junk-confirmed");
        }
    });
});

// BULK DELETE SUCCESS
window.addEventListener("bulk-deleted-success", () => {
    Swal.fire({
        title: "Deleted!",
        text: "Selected items were permanently deleted.",
        icon: "success",
    });
});

// BULK JUNK SUCCESS
window.addEventListener("bulk-junk-success", () => {
    Swal.fire({
        title: "Moved!",
        text: "Selected items were moved to Junk.",
        icon: "success",
    });
});
