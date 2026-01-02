window.addEventListener("delete-confirm", (event) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
           window.Livewire.dispatch("deleteRoomConfirmed");
        }
    });
});
window.addEventListener("roomDeleted", (event) => {
    Swal.fire({
        title: "Deleted!",
        text: "Room has been deleted.",
        icon: "success",
    }); 
});


// ROLE DELETE CONFIRMATION
window.addEventListener("delete-role-confirm", () => {
    Swal.fire({
        title: "Are you sure?",
        text: "This role will be permanently deleted!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            window.Livewire.dispatch("deleteRoleConfirmed");
        }
    });
});

// ROLE DELETED SUCCESS
window.addEventListener("roleDeleted", () => {
    Swal.fire({
        title: "Deleted!",
        text: "Role has been deleted.",
        icon: "success",
    });
});
