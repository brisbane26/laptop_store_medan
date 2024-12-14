// Tangkap flash message dari session Laravel
if (window.serviceMessage) {
    const { type, text } = window.serviceMessage;

    // Tampilkan SweetAlert berdasarkan tipe pesan
    Swal.fire({
        icon: type, // success, error, warning, info
        title: type === "success" ? "Success" : "Oops...",
        text: text,
    });
}
