function openModal() {
    document.getElementById('modalTentukanKurir').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalTentukanKurir').classList.add('hidden');
}

function showDetailModal(resi, pengirim, alamatJemput, penerima, alamatTujuan, metodePembayaran, kurir, tanggal, biaya, status) {
    // Set the values of the modal fields based on the data passed
    document.getElementById('resiDetail').value = resi;
    document.getElementById('pengirimDetail').value = pengirim;
    document.getElementById('alamatJemputDetail').value = alamatJemput;
    document.getElementById('penerimaDetail').value = penerima;
    document.getElementById('alamatTujuanDetail').value = alamatTujuan;
    document.getElementById('tanggalDetail').value = tanggal;
    document.getElementById('beratDetail').value = biaya;
    document.getElementById('hargaDetail').value = tanggal;
    document.getElementById('metodePembayaranDetail').value = metodePembayaran;
    document.getElementById('kurirDetail').value = kurir;
    document.getElementById('statusDetail').value = status;

}

function closeDetailModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}
