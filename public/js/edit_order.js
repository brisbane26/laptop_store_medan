const setVisible = (elementOrSelector, visible) =>
    ((typeof elementOrSelector === "string"
        ? document.querySelector(elementOrSelector)
        : elementOrSelector
    ).style.display = visible ? "block" : "none");

var isUseCoupon = false;
var couponTotal;
var currentNum = 0;
var couponUsed = 0;

var quantities = [];
var prices = [];
var shipping = 0; // Ensure shipping cost is retained

$(document).ready(function () {
    updateOrderSummary();

    getLokasi();

    $("#province").on("change", function (e) {
        e.preventDefault();
        var option = $("option:selected", this).val();
        $("#city option:gt(0)").remove();
        $("#kurir").val("");

        if (option === "") {
            alert("Please select a valid province.");
            $("#city").prop("disabled", true);
        } else {
            $("#city").prop("disabled", false);
            getCity(option);
        }
    });

    $("#city").on("change", function () {
        setCity();
    });
});

function changeStatesCoupon() {
    isUseCoupon = !isUseCoupon;
}

// ================== Order Summary ====================
var sub_total;
var total;

// Update the order summary based on all products
function updateOrderSummary() {
    sub_total = 0;

    // Iterate through all products and calculate subtotal
    $("input[id^='quantity_']").each(function (index) {
        var quantity = parseInt($(this).val()) || 0;
        // Get price per piece from the associated span
        var priceElement = $(this).closest(".row").find("span[data-price]");
        var price = parseFloat(priceElement.data("price")) || 0;

        quantities[index] = quantity;
        prices[index] = price;

        sub_total += quantity * price;
    });

    // Apply coupon logic
    if (isUseCoupon && couponUsed > 0) {
        sub_total -= couponUsed * prices[0];
    }

    total = sub_total + shipping; // Keep shipping cost in the total calculation

    refresh_data({ sub_total, total, shipping });
}


function refresh_data({ sub_total = 0, shipping = 0, total = 0 }) {
    $("#sub-total").html(sub_total);
    $("#total_price").val(total);
    $("#total").html(total);

    if (shipping >= 0) {
        $("#shipping").attr("data-shippingCost", shipping);
        $("#shipping").html(shipping);
    }
}

// =================================== Ongkir =======================================
var destinasi;

function getLokasi() {
    var $op = $("#province");

    $.getJSON("/shipping/province", function (data) {
        $.each(data, function (i, field) {
            $op.append(
                '<option value="' +
                    field.province_id +
                    '">' +
                    field.province +
                    "</option>"
            );
        });
    });
}

function getCity(province_id) {
    var op = $("#city");

    $.getJSON("/shipping/city/" + province_id, function (data) {
        $.each(data, function (i, field) {
            op.append(
                '<option value="' +
                    field.city_id +
                    '">' +
                    field.type +
                    " " +
                    field.city_name +
                    "</option>"
            );
        });
    });
}

function setCity() {
    destinasi = $("#city").val();

    setOngkir({
        destination: destinasi,
        quantity: quantities.reduce((a, b) => a + b, 0), // Sum of all quantities
    });
}

function setOngkir({
    origin = 278, // Kota Medan
    destination,
    quantity,
    courier = "jne",
}) {
    if (quantity == 0) {
        refresh_data({
            shipping: 0,
            sub_total: 0,
            total: 0,
        });
        return;
    }

    destination = parseInt(destination);

    setVisible("#transaction", false);
    setVisible("#loading_transaction", true);

    $.ajax({
        url: `/shipping/cost/${origin}/${destination}/${quantity}/${courier}`,
        method: "get",
        dataType: "json",
        success: function (data) {
            var city = $("#city option:selected");
            var province = $("#province option:selected");

            // Ambil alamat lengkap dari input address
            var addressDetail = $("#address").val(); 

            // Gabungkan alamat lengkap dengan kota dan provinsi
            $("#shipping_address").val(addressDetail + ", " + city.html() + ", " + province.html());

            shipping = data[0]["costs"][0]["cost"][0]["value"]; // Update shipping cost
            total = sub_total + shipping;

            refresh_data({ shipping, sub_total, total });

            setVisible("#transaction", true);
            setVisible("#loading_transaction", false);
        },
    });
}


// ========================== Event Listeners ============================
$("input[id^='quantity_']").on("change", function () {
    updateOrderSummary();
});

$("#button_edit_order").click(function (e) {
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "Order data will be updated.",
        icon: "warning",
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        showCancelButton: true,
        confirmButtonColor: "#08a10b",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            $("#form_edit_order").submit();
        } else {
            Swal.fire("Action canceled", "", "info");
        }
    });
});
