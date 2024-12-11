Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

$(window).on("load", function () {
    $.ajax({
        url: "/api/profits-chart", // Endpoint API
        method: "GET",
        dataType: "json",
        success: function (response) {
            console.log("API Response:", response);

            // Validasi data respons
            if (!response || !response.data || !response.six_month_ago || !response.now) {
                console.error("Invalid API Response");
                alert("Data profits tidak tersedia.");
                return;
            }

            const months = response.data.map(item => {
                const date = new Date(item.date);
                return date.toLocaleString("default", { month: "short", year: "numeric" }); // Format bulan singkat
            });            

            const profits = response.data.map(item => item.profits ?? 0);

            // Inisialisasi Chart
            const ctx = document.getElementById("profits_chart");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: "Profit",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: profits,
                        },
                    ],
                },
                options: {
                    scales: {
                        xAxes: [
                            {
                                time: {
                                    unit: "month",
                                },
                                gridLines: {
                                    display: false,
                                },
                                ticks: {
                                    maxTicksLimit: 6,
                                },
                            },
                        ],
                        yAxes: [
                            {
                                ticks: {
                                    min: Math.min(...profits) < 0 ? Math.min(...profits) * 1.3 : 0,
                                    max: Math.max(...profits) * 1.3 || 10,
                                    maxTicksLimit: 8,
                                },
                                gridLines: {
                                    display: true,
                                },
                            },
                        ],
                    },
                    legend: {
                        display: false,
                    },
                },
            });
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
            alert("Gagal mengambil data profits. Silakan coba lagi.");
        },
    });
});
