<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        #redamanChart {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Detail Pelanggan -->
    <form action="" method="post" id="form-pelanggan">
        @csrf
        <input type="hidden" name="id" value="{{ $pelanggan->id }}">
        <div class="form-group">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama" class="form-control" value="{{ $pelanggan->nama }}" readonly>
        </div>
        <div class="form-group">
            <label>Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ $pelanggan->alamat }}" readonly>
        </div>
        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" class="form-control" value="{{ $pelanggan->status }}" readonly>
        </div>
        <div class="form-group">
            <label>Paket</label>
            <input type="text" name="paket" class="form-control" value="{{ $pelanggan->paket }}" readonly>
        </div>
    </form>

    <canvas id="redamanChart" width="400" height="200"></canvas>

    <script>
    $(document).ready(function() {
        // Mengambil data dari server dan mengurutkan berdasarkan tanggal
        const pelangganChartData = @json($chartData)
            .sort((a, b) => new Date(a.tanggal) - new Date(b.tanggal));

        // Tentukan tanggal batas (5 hari terakhir dari hari ini)
        const batasTanggal = moment().subtract(5, 'days').startOf('day');

        // Filter data untuk hanya menampilkan data dalam 5 hari terakhir
        const filteredData = pelangganChartData.filter(item => 
            moment(item.tanggal).isSameOrAfter(batasTanggal)
        );

        // Tetap menggunakan semua label, tapi hanya menampilkan data 5 hari terakhir
        const labels = pelangganChartData.map(item => item.tanggal);
        const dataRedaman = pelangganChartData.map(item => parseFloat(item.redaman));

        // Ambil hanya 5 data terakhir
        const recentLabels = labels.slice(-5);
        const recentDataRedaman = dataRedaman.slice(-5);

        // Inisialisasi Chart.js
        const ctx = document.getElementById('redamanChart').getContext('2d');
        const redamanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: recentLabels, // Menampilkan 5 label terakhir
                datasets: [{
                    label: 'Data Redaman',
                    data: recentDataRedaman, // Menampilkan 5 data terakhir
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: false,
                    radius: 5, // Menampilkan titik (radius = 5)
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Warna titik
                    pointBorderColor: '#fff', // Warna border titik
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                interaction: { intersect: false },
                plugins: { legend: { display: true } },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Redaman'
                        },
                        beginAtZero: true,
                        reverse: true,
                        grid: {
                            drawBorder: true,
                            color: 'rgba(200, 200, 200, 0.3)'
                        }
                    }
                }
            }
        });
    });
</script>
    
</body>
</html>
