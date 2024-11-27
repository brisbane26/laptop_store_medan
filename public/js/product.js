import { previewImage } from "/js/image_preview.js";

// Fungsi untuk mengatur visibility elemen
const setVisible = (elementOrSelector, visible) =>
    ((typeof elementOrSelector === "string"
        ? document.querySelector(elementOrSelector)
        : elementOrSelector
    ).style.display = visible ? "block" : "none");

// Detail Product
$("button.detail").click(function () {
    const id = $(this).attr("data-id");
    setVisible("#loading", true);

    $.ajax({
        url: `/product/data/${id}`,
        method: "GET",
        dataType: "json",
        success: function (res) {
            $("#modal-image").attr("src", `/storage/${res.image}`);
            $(".text-uppercase").html(res.product_name);
            $(".orientation").html(res.orientation);
            $(".description").html(res.description);
            $(".stock").html(
                `Available: <em>${res.stock} unit</em>`
            );

            if (res.discount === 0) {
                $(".price").html(
                    `Price: <strong>Rp. ${res.price}</strong>`
                );
                $(".discount").html(
                    "Discount: <em class='text-danger'>No discount available</em>"
                );
            } else {
                $(".price").html(
                    `Price: 
                    <strong class='me-2'>Rp. ${(100 - res.discount) / 100 * res.price}</strong>
                    <strong class='strikethrough'>Rp. ${res.price}</strong>`
                );
                $(".discount").html(
                    `Discount: <em class='text-danger'>${res.discount}%</em>`
                );
            }

            $("#ProductDetailModal").modal("show");
            setVisible("#loading", false);
        },
        error: function () {
            setVisible("#loading", false);
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Failed to fetch product details!",
            });
        },
    });
});

// Add to Cart functionality
$("button.add-to-cart").click(function (event) {
    event.preventDefault();

    const productId = $(this).attr("data-id"); // Ambil ID produk dari atribut tombol
    const quantity = 1; // Default jumlah

    $.ajax({
        url: "/cart/add", // Endpoint Laravel
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: {
            product_id: productId,
            quantity: quantity,
        },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Success",
                text: response.message,
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: xhr.responseJSON?.message || "Failed to add product to cart!",
            });
        },
    });
});

// Preview Image
$("#image").on("change", function () {
    previewImage({
        image: "image",
        image_preview: "image-preview",
        image_preview_alt: "Product Image",
    });
});

// Confirm Edit Product
$("#button_edit_product").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "After this process, product data will be changed",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonColor: "#d33",
        confirmButtonColor: "#08a10b",
    }).then((result) => {
        if (result.isConfirmed) {
            $("#form_edit_product").submit();
        } else {
            Swal.fire("Action canceled", "", "info");
        }
    });
});
