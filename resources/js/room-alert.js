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
