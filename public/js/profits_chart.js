Chart.defaults.global.defaultFontFamily =
    '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#292b2c";

$(window).on("load", function () {
    $.ajax({
        url: "/chart/profits_chart", // Endpoint API
        method: "get",
        dataType: "json",
        success: function (response) {
            console.log("API Response:", response); // Debugging log

            // Validasi respons API
            if (!response || !response.data || !response.six_month_ago || !response.now) {
                console.error("Invalid API Response");
                alert("Data profits tidak tersedia.");
                return;
            }

            // Parsing tanggal awal dan akhir
            let begin = new Date(response["six_month_ago"]);
            let end = new Date(response["now"]);
            if (isNaN(begin) || isNaN(end)) {
                console.error("Invalid Date Format");
                alert("Tanggal dalam data API tidak valid.");
                return;
            }

            let month_list = [];
            let montly_profit = [];
            let month_iterate = new Date(begin);

            // Iterasi bulan dari awal hingga akhir
            while (month_iterate <= end) {
                let is_get = false;
                let current_month = month_iterate.getMonth();
                let current_year = month_iterate.getFullYear();

                // Mencari data profit untuk bulan tertentu
                for (let i = 0; i < response["data"].length; i++) {
                    let data_date = new Date(response["data"][i]["date"]);
                    if (
                        data_date.getMonth() === current_month &&
                        data_date.getFullYear() === current_year
                    ) {
                        montly_profit.push(
                            parseInt(response["data"][i]["profits"]) || 0
                        );
                        is_get = true;
                        break;
                    }
                }

                // Jika tidak ada data untuk bulan tersebut, tambahkan 0
                if (!is_get) {
                    montly_profit.push(0);
                }

                // Tambahkan nama bulan ke daftar
                let month_name = month_iterate.toLocaleString("default", {
                    month: "long",
                });
                month_list.push(`${month_name}`);

                // Iterasi ke bulan berikutnya
                month_iterate.setMonth(month_iterate.getMonth() + 1);
            }

            // Cek apakah ada data profit valid
            

            // Inisialisasi grafik
            let ctx = document.getElementById("profits_chart");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: month_list,
                    datasets: [
                        {
                            label: "Profit",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: montly_profit,
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
                                    min: Math.min(...montly_profit) < 0
                                        ? Math.min(...montly_profit) * 1.3
                                        : 0,
                                    max: Math.max(...montly_profit) * 1.3 || 10,
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
