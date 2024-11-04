<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    const pelangganChartData = @json($chartData);

    const labels = pelangganChartData.map(item => item.tanggal);
    const dataRedaman = pelangganChartData.map(item => parseFloat(item.redaman));

    const ctx = document.getElementById('redamanChart').getContext('2d');
    const redamanChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Data Redaman',
                data: dataRedaman,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
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
                    grid: {
                    drawBorder: true,
                    color: 'rgba(200, 200, 200, 0.3)',
                },
                ticks: {
                    callback: function(value) {
                        return value < 0 ? -value : value; // Mengubah nilai negatif ke positif di label
                    }
                }
                }
            }
        }
    });
});

    </script>
</body>
</html>
