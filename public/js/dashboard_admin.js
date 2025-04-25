const ctx = document.getElementById('pengirimanChart').getContext('2d');
let dataPengiriman = [600, 500, 623, 450, 700, 850, 500, 670, 550, 780, 690, 720];
let wilayah = ['Batam Center', 'Sekupang', 'Batu Aji', 'Nongsa', 'Tanjung Uncang', 'Lubuk Baja',
               'Batu Besar', 'Bintan', 'Belakang Padang', 'Kabil', 'Sungai Panas', 'Mangsang'];
const pengirimanChart = new Chart(ctx, {
    type: 'line', 
    data: {
        labels: wilayah, 
        datasets: [{
            label: 'Jumlah Pengiriman',
            data: dataPengiriman,
            borderColor: '#facc15',
            backgroundColor: 'rgba(250, 204, 21, 0.2)', 
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#facc15',
            pointBorderColor: '#facc15'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 100
                }
            }
        }
    }
});


function updateChartData() {
    dataPengiriman = dataPengiriman.map(val => {
        let change = Math.floor(Math.random() * 201) - 100; 
        return Math.max(0, val + change); 
    });

    pengirimanChart.data.datasets[0].data = dataPengiriman;
    pengirimanChart.update();
}

setInterval(updateChartData, 3000);
