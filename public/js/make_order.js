// ====================== Main Functions ======================

// Fungsi untuk menghitung subtotal dan total
function calculateSubtotal() {
    let subtotal = 0;

    // Loop melalui setiap baris produk
    document.querySelectorAll(".product-row").forEach(row => {
        const price = parseFloat(row.querySelector(".price").value) || 0;
        const quantity = parseInt(row.querySelector(".quantity").value) || 0;

        // Tambahkan ke subtotal
        subtotal += price * quantity;
    });

    // Perbarui nilai subtotal di DOM
    const subtotalElement = document.getElementById("sub-total");
    subtotalElement.innerText = new Intl.NumberFormat("id-ID").format(subtotal);

    // Ambil biaya pengiriman (ongkir)
    const shipping = parseInt(document.getElementById("shipping").getAttribute("data-shippingCost")) || 0;

    // Ambil nilai kupon jika digunakan
    let couponDiscount = 0;
    const couponUsedInput = document.getElementById("coupon_used");
    const couponUsed = parseInt(couponUsedInput.value) || 0;

    // Setiap kupon bernilai 10.000, jadi diskon adalah 10.000 * couponUsed
    couponDiscount = couponUsed * 50000;

    // Hitung total
    const total = Math.max(0, subtotal + shipping - couponDiscount);

    // Perbarui nilai total di DOM
    const totalElement = document.getElementById("total");
    totalElement.innerText = new Intl.NumberFormat("id-ID").format(total);

    // Perbarui nilai hidden input untuk total
    const totalPriceInput = document.getElementById("total_price");
    totalPriceInput.value = total;

    // Perbarui tampilan kupon yang digunakan
    const couponUsedShow = document.getElementById("couponUsedShow");
    if (couponUsedShow) {
        couponUsedShow.innerText = `${couponUsed} coupons used`;
    }
}

// Fungsi untuk memperbarui data pengiriman setelah lokasi berubah
function updateShipping(destination) {
    const origin = 278; // ID asal (Kota Medan)
    const quantity = parseInt(document.querySelector(".quantity").value) || 1;
    const courier = "jne";

    // Validasi destination
    if (!destination || quantity <= 0) {
        calculateSubtotal(); // Tetap hitung subtotal meskipun tidak ada destination
        return;
    }

    // Tampilkan loading
    setVisible("#loading_transaction", true);
    setVisible("#transaction", false);

    // Hitung ongkir
    $.ajax({
        url: `/shipping/cost/${origin}/${destination}/${quantity}/${courier}`,
        method: "get",
        dataType: "json",
        success: function (data) {
            const shippingCost = data[0]["costs"][0]["cost"][0]["value"] || 0;
            const shippingElement = document.getElementById("shipping");
            shippingElement.setAttribute("data-shippingCost", shippingCost);
            shippingElement.innerText = new Intl.NumberFormat("id-ID").format(shippingCost);

            // Hitung ulang subtotal dan total
            calculateSubtotal();

            // Sembunyikan loading
            setVisible("#transaction", true);
            setVisible("#loading_transaction", false);
        },
    });
}

// Fungsi untuk mengatur event listener saat lokasi berubah
function setupLocationListeners() {
    // Ambil data provinsi
    const provinceElement = document.getElementById("province");
    if (provinceElement) {
        $.getJSON("/shipping/province", function (data) {
            data.forEach(field => {
                provinceElement.insertAdjacentHTML(
                    "beforeend",
                    `<option value="${field.province_id}">${field.province}</option>`
                );
            });
        });

        // Event saat provinsi dipilih
        provinceElement.addEventListener("change", function () {
            const provinceId = this.value;
            const cityElement = document.getElementById("city");
            cityElement.innerHTML = '<option value="">Select City</option>'; // Reset pilihan kota

            if (provinceId) {
                // Ambil data kota berdasarkan provinsi
                $.getJSON(`/shipping/city/${provinceId}`, function (data) {
                    data.forEach(field => {
                        cityElement.insertAdjacentHTML(
                            "beforeend",
                            `<option value="${field.city_id}">${field.type} ${field.city_name}</option>`
                        );
                    });
                });

                cityElement.disabled = false;
            } else {
                cityElement.disabled = true;
            }
        });
    }

    // Event saat kota dipilih
    const cityElement = document.getElementById("city");
    if (cityElement) {
        cityElement.addEventListener("change", function () {
            const destination = this.value;
            updateShipping(destination);
        });
    }
}

// Fungsi untuk mengatur status kupon
function changeStatesCoupon() {
    const couponUsedInput = document.getElementById("coupon_used");
    const useCouponCheckbox = document.getElementById("use_coupon");
    if (couponUsedInput && useCouponCheckbox) {
        couponUsedInput.value = useCouponCheckbox.checked ? 1 : 0;
    }
}

// Fungsi untuk menyinkronkan nilai address ke shipping_address
function syncShippingAddress() {
    const addressInput = document.getElementById("address");
    const shippingAddressInput = document.getElementById("shipping_address");

    // Fungsi untuk memperbarui shipping_address
    function updateShippingAddress() {
        const city = document.getElementById("city").value;
        const province = document.getElementById("province").value;

        // Pastikan kota dan provinsi dipilih
        if (city && province) {
            const cityName = document.querySelector(`#city option[value="${city}"]`).textContent;
            const provinceName = document.querySelector(`#province option[value="${province}"]`).textContent;

            // Gabungkan alamat, kota, dan provinsi
            shippingAddressInput.value = `${addressInput.value}, ${cityName}, ${provinceName}`;
        }
    }

    // Perbarui shipping_address setiap kali address, kota, atau provinsi diubah
    addressInput.addEventListener("input", updateShippingAddress);
    document.getElementById("province").addEventListener("change", updateShippingAddress);
    document.getElementById("city").addEventListener("change", updateShippingAddress);
}


// ======================= Utilities ==========================

// Fungsi untuk menampilkan/menyembunyikan elemen
const setVisible = (elementOrSelector, visible) => {
    (
        typeof elementOrSelector === "string"
            ? document.querySelector(elementOrSelector)
            : elementOrSelector
    ).style.display = visible ? "block" : "none";
};

// =================== Initialize Page ========================

function setupPage() {
    // Saat halaman selesai dimuat, hitung subtotal
    window.onload = calculateSubtotal;

    // Event listener untuk checkbox kupon
    const useCouponCheckbox = document.getElementById("use_coupon");
    if (useCouponCheckbox) {
        useCouponCheckbox.addEventListener("change", function () {
            changeStatesCoupon();
            calculateSubtotal();
        });
    }

    // Atur event listener untuk lokasi
    setupLocationListeners();

    // Sinkronkan address ke shipping_address
    syncShippingAddress();
}

setupPage();
