<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resi Pengiriman</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 10px 16px;
        }

        .container {
            border: 1px solid #000;
            padding: 16px;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .header img {
            height: 25px;
        }

        .center {
            text-align: center;
        }

        .qrcode {
            margin: 8px 0;
        }

        .resi-no {
            font-weight: bold;
            font-size: 13px;
            margin-top: 2px;
            margin-bottom: 5px; /* Tambah margin bawah untuk QR dan Resi */
        }

        /* --- CSS for Pengirim and Penerima (using table) --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px; /* Sesuaikan margin atas */
            margin-bottom: 10px; /* Sesuaikan margin bawah */
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px; /* Tambah padding horizontal di sel */
            line-height: 1.4;
        }

        .info-table strong {
            display: block;
            margin-bottom: 2px;
        }

        /* --- CSS for City Boxes (MODIFIED TO USE TABLE) --- */
        .city-table-wrapper {
            width: 100%;
            margin: 10px 0 15px 0; /* Sesuaikan margin: atas, kanan, bawah, kiri */
            text-align: center; /* Untuk memusatkan tabel */
        }

        .city-table {
            width: calc(100% - 32px); /* Mengambil 100% dari container dikurangi padding container */
            max-width: 400px; /* Batasan lebar maksimum */
            border-collapse: collapse; /* Menghilangkan spasi antar sel */
            margin: 0 auto; /* Memusatkan tabel secara horizontal */
        }

        .city-table td {
            width: 50%; /* Membuat setiap sel tabel menempati 50% lebar */
            padding: 0; /* Menghilangkan padding default sel */
            text-align: center; /* Pastikan teks di dalam td rata tengah */
        }

        .city-box-content {
            border: 1px solid #000;
            padding: 6px 0;
            font-weight: bold;
            line-height: 1.4;
        }

        /* Mengatur jarak antar kotak dengan padding pada TD, lebih konsisten untuk tabel */
        .city-table td:first-child {
            padding-right: 10px; /* Memberi jarak ke kanan dari kotak pertama */
        }
        .city-table td:last-child {
            padding-left: 10px; /* Memberi jarak ke kiri dari kotak kedua */
        }


        /* --- CSS for Details (MODIFIED TO USE TABLE for robustness in PDF) --- */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px; /* Sesuaikan margin atas */
            /* margin-bottom: 10px; */ /* Tidak perlu margin-bottom jika footer langsung di bawah */
        }

        .details-table td {
            padding: 1px 0; /* Padding kecil untuk baris */
            vertical-align: top; /* Pastikan teks di atas */
        }

        .details-table td:first-child {
            width: 25%; /* Lebar kolom label (Berat, Harga, Pembayaran) */
            font-weight: bold;
            padding-left: 5px; /* Padding kiri agar tidak terlalu mepet ke border container */
        }

        .details-table td:nth-child(2) {
            width: 5px; /* Lebar kolom untuk titik dua */
            text-align: center;
        }

        .details-table td:last-child {
            width: auto; /* Kolom nilai akan mengisi sisa ruang */
            padding-right: 5px; /* Padding kanan */
        }

        .bottom-resi {
            text-align: right;
            font-size: 10px;
            margin-top: 8px; /* Sesuaikan margin atas agar ada jarak dari detail */
            padding-right: 5px; /* Sedikit padding agar tidak terlalu mepet ke border kanan */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('images/admin/logo2.jpg') }}" alt="Logo">
            <strong>SJ COURIER</strong>
        </div>

        <div class="center">
            <div class="qrcode">
                <img src="data:image/png;base64,{{ $qrcode }}" width="100">
            </div>
            <div class="resi-no">{{ $shipment->tracking_number }}</div>
        </div>

        <table class="info-table" style="margin-bottom: 10px;">
    <tr>
        <td style="width: 50%; vertical-align: top; line-height: 1.4;">
            <strong>Penerima :</strong>
            {{ $shipment->order->receiverName }}<br>
            <strong>Alamat Tujuan :</strong>
            {{ $shipment->order->receiverAddress }}<br>
            {{ Auth::user()->phone }}
        </td>
        <td style="width: 50%; vertical-align: top; line-height: 1.4;">
            <strong>Pengirim :</strong>
            {{ $shipment->order->sender->name }}<br>
            <strong>Alamat :</strong>
            {{ $shipment->order->pickupAddress }}<br>
            {{ $shipment->order->receiverPhoneNumber }}
        </td>
    </tr>
    <tr>
        <td style="text-align: center; padding-top: 10px;">
            <div class="city-box-content">BATAM KOTA</div>
        </td>
        <td style="text-align: center; padding-top: 10px;">
            <div class="city-box-content">NONGSA</div>
        </td>
    </tr>
</table>

        <table class="details-table" style="width: 100%; border-collapse: collapse;">
  <tr>
    <!-- Kolom kiri -->
    <td style="width: 50%; padding: 1px 5px;">
      <table style="width: 100%;">
        <tr>
          <td style="font-weight: bold; padding-left: 5px; width: 40%;">Produk</td>
          <td style="width: 5%; text-align: center;">:</td>
          <td style="width: 55%;">{{ $shipment->itemType }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold; padding-left: 5px;">Berat</td>
          <td style="text-align: center;">:</td>
          <td>{{ $shipment->weightKG }} Kg</td>
        </tr>
      </table>
    </td>

    <!-- Kolom kanan -->
    <td style="width: 50%; padding: 1px 5px;">
      <table style="width: 100%;">
        <tr>
          <td style="font-weight: bold; padding-left: 5px; width: 40%;">Harga</td>
          <td style="width: 5%; text-align: center;">:</td>
          <td style="width: 55%;">Rp {{ number_format($shipment->finalPrice, 0, ',', '.') }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold; padding-left: 5px;">Pembayaran</td>
          <td style="text-align: center;">:</td>
          <td>{{ $shipment->order->payments->first()->paymentMethod ?? '-' }}</td>
        </tr>
      </table>

        <div class="bottom-resi">
            No. Resi: {{ $shipment->tracking_number }}
        </div>
    </div>
</body>
</html>