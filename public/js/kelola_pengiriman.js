function openModal() {
    document.getElementById('modalTentukanKurir').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalTentukanKurir').classList.add('hidden');
}

function showDetailModal(resi, pengirim, alamatJemput, penerima, alamatTujuan, kurir, tanggal, berat, harga, status) {
    document.getElementById('resiDetail').value = resi;
    document.getElementById('pengirimDetail').value = pengirim;
    document.getElementById('alamatJemputDetail').value = alamatJemput;
    document.getElementById('penerimaDetail').value = penerima;
    document.getElementById('alamatTujuanDetail').value = alamatTujuan;
    document.getElementById('beratDetail').value = berat;
    document.getElementById('hargaDetail').value = harga.replace('Rp ', '').replace(/\./g, '');
    document.getElementById('kurirDetail').value = kurir;
    document.getElementById('statusDetail').value = status.toLowerCase();
    document.getElementById('tanggalDetail').value = tanggal;
    document.getElementById('catatanDetail').value = catatan;
    document.getElementById('modalDetail').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}