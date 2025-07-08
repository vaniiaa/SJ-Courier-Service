<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resi Pengiriman</title>
    <style>
        @page {
            margin: 0; /* Penting untuk DomPDF agar tidak ada margin halaman default */
            size: 283.46pt 425.2pt; /* Menyamakan ukuran kertas (lebar tinggi) dengan setPaper di DomPDF */
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Ukuran font dasar */
            padding: 5px 12px; /* Padding dari tepi body */
            line-height: 1.3;
        }

        .container {
            border: 1px solid #000;
            padding: 8px; /* Padding di dalam container utama */
        }

        .header {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
            margin-top: 0px; /* Menaikkan header */
        }

        .header img {
            height: 20px;
        }

        .header strong {
            font-size: 11px;
        }

        .center {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 5px;
        }

        .qrcode {
            margin: 3px 0;
        }

        .qrcode img {
            width: 70px; /* Ukuran QR Code */
            height: 70px;
        }

        .resi-no {
            font-weight: bold;
            font-size: 11px;
            margin-top: 2px;
            margin-bottom: 5px;
        }

        /* --- CSS for Pengirim and Penerima (using table) --- */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 8px;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
            line-height: 1.3;
        }

        .info-table strong {
            display: block;
            margin-bottom: 1px;
        }

        /* --- CSS for City Boxes (MODIFIED TO USE TABLE) --- */
        .city-table-wrapper {
            width: 100%;
            margin: 8px 0 12px 0;
            text-align: center;
        }

        .city-table {
            width: calc(100% - 16px);
            max-width: 350px;
            border-collapse: collapse;
            margin: 0 auto;
        }

        .city-table td {
            width: 50%;
            padding: 0;
            text-align: center;
        }

        .city-box-content {
            border: 1px solid #000;
            padding: 4px 0;
            font-weight: bold;
            line-height: 1.3;
            font-size: 10px;
        }

        .city-table td:first-child {
            padding-right: 8px;
        }
        .city-table td:last-child {
            padding-left: 8px;
        }

        /* --- CSS for Details (MODIFIED TO USE TABLE for robustness in PDF) --- */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .details-table td {
            padding: 1px 0;
            vertical-align: top;
            line-height: 1.3;
        }

        .details-table td:first-child {
            width: 25%;
            font-weight: bold;
            padding-left: 5px;
        }

        .details-table td:nth-child(2) {
            width: 5px;
            text-align: center;
        }

        .details-table td:last-child {
            width: auto;
            padding-right: 5px;
        }

        .bottom-resi {
            text-align: right;
            font-size: 9px; /* Ukuran font footer */
            margin-top: 5px;
            padding-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
    @php
        $logoPath = public_path('images/admin/logo2.jpg'); 
        $logoBase64 = '';

        // Cek apakah file ada sebelum mencoba membacanya
        if (file_exists($logoPath)) {
            // Dapatkan ekstensi file untuk tipe MIME (misal: 'jpg', 'png')
            $extension = pathinfo($logoPath, PATHINFO_EXTENSION);
            // Baca konten file dan encode ke Base64
            $logoBase64 = 'data:image/' . $extension . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp

    @if($logoBase64)
        {{-- Gunakan gambar yang sudah di-encode Base64 --}}
        <img src="{{ $logoBase64 }}" alt="Logo SJ Courier">
    @else
        {{-- Fallback jika gambar tidak ditemukan atau dalam mode pengembangan/debug --}}
        {{-- Ini hanya akan muncul jika $logoBase64 kosong (file tidak ditemukan) --}}
        <img src="{{ asset('images/admin/logo2.jpg') }}" alt="Logo SJ Courier (Fallback)" style="height: 20px;">
        @endif
    
    <strong>SJ COURIER</strong>
</div>

        <div class="center">
            <div class="qrcode">
                <img src="data:image/png;base64,{{ $qrcode }}" alt="QR Code">
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
    <td style="width: 50%; vertical-align: top;">
      <table style="width: 100%; border-collapse: collapse;">
        <tr>
          <td style="font-weight: bold; width: 35%; padding-left: 5px;">Produk</td>
          <td style="width: 5%; text-align: center;">:</td>
          <td style="width: 60%;">{{ $shipment->itemType }}</td>
        </tr>
        <tr>
          <td style="font-weight: bold; padding-left: 5px;">Berat</td>
          <td style="text-align: center;">:</td>
          <td>{{ $shipment->weightKG }} Kg</td>
        </tr>
      </table>
    </td>

    <!-- Kolom kanan -->
<td style="width: 50%; vertical-align: top;">
  <table style="width: 100%; border-collapse: collapse;">
    <tr>
      <td style="font-weight: bold; width: 35%; padding-left: 5px; text-align: left;">Harga</td>
      <td style="width: 5%; text-align: center;">:</td>
      <td style="width: 60%; text-align: left;">Rp {{ number_format($shipment->finalPrice, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td style="font-weight: bold; padding-left: 5px; text-align: left;">Pembayaran</td>
      <td style="text-align: center;">:</td>
      <td style="text-align: left;">{{ $shipment->order->payments->first()->paymentMethod ?? '-' }}</td>
    </tr>
      </table>

        <div class="bottom-resi">
            No. Resi: {{ $shipment->tracking_number }}
        </div>
    </div>
</body>
<script>
    window.onload = function() {
        setTimeout(function() {
            window.print(); // <-- Ini adalah perintah JavaScript yang memicu dialog cetak
        }, 250); // Memberi jeda waktu agar DOM sepenuhnya siap
    };
</script>
</html>