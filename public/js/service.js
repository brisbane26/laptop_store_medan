// Tangkap flash message dari session Laravel
if (typeof Swal === 'undefined') {
    console.error("SweetAlert not loaded");
}

document.addEventListener("DOMContentLoaded", function () {
    // Cari semua form yang memiliki tombol "Cancel"
    const cancelForms = document.querySelectorAll("form[action*='/cancel']");

    cancelForms.forEach((form) => {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Cegah pengiriman form langsung

            // Tampilkan dialog konfirmasi
            Swal.fire({
                title: "Are you sure?",
                text: "This action will cancel and delete the service request.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, keep it",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna mengkonfirmasi, kirim form
                    form.submit();
                }
            });
        });
    });
});

