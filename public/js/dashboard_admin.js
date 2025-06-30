const ctx = document.getElementById("pengirimanChart").getContext("2d");

let pengirimanChart = new Chart(ctx, {
    type: "line",
    data: {
        labels: [],
        datasets: [
            {
                label: "Jumlah Pengiriman",
                data: [],
                borderColor: "#facc15",
                backgroundColor: "rgba(250, 204, 21, 0.2)",
                fill: true,
                tension: 0.4,
                pointBackgroundColor: "#facc15",
                pointBorderColor: "#facc15",
            },
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                },
            },
        },
    },
});

function fetchChartData() {
    fetch("/admin/api/pengiriman-per-wilayah")
        .then((response) => response.json())
        .then((data) => {
            const labels = data.map((item) => item.area_name);
            const values = data.map((item) => item.total);

            pengirimanChart.data.labels = labels;
            pengirimanChart.data.datasets[0].data = values;
            pengirimanChart.update();
        })
        .catch((error) => {
            console.error("Error fetching chart data:", error);
        });
}

fetchChartData();
setInterval(fetchChartData, 3000);
