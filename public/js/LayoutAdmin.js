// File: LayoutAdmin.js (Konten Baru)

// --- Sidebar Toggle (Tetap Sama) ---
const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebar-toggle");
const body = document.body;
const headerEl = document.getElementById("admin-header");

if (sidebarToggle) {
    sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("-translate-x-full");
        body.classList.toggle("sidebar-open");
        headerEl.style.left = body.classList.contains("sidebar-open")
            ? "256px"
            : "0";
    });
}

// --- Logika Modal Penjadwalan Kurir (Diperbarui) ---
function openModal(buttonElement) {
    // Membaca data dari tombol yang diklik
    const shipmentId = buttonElement.dataset.shipmentId;

    // VALIDASI PENTING: Periksa apakah shipmentId ada dan bukan string kosong
    if (!shipmentId) {
        alert(
            "Error: Tidak dapat menemukan ID Pengiriman. Silakan muat ulang halaman."
        );
        return; // Hentikan eksekusi jika ID tidak ditemukan
    }

    // Jika ID ditemukan, lanjutkan proses
    const modal = document.getElementById("modalTentukanKurir");
    if (modal) {
        modal.querySelector("#shipmentIdToAssign").value = shipmentId;

        // Bersihkan catatan customer sebelumnya jika ada
        const catatanCustomerInput = modal.querySelector("#catatanKurirModal");
        if (catatanCustomerInput) {
            catatanCustomerInput.value =
                buttonElement.dataset.catatanCustomer || "";
        }

        modal.classList.remove("hidden");
    }

    if (headerEl) headerEl.classList.add("darken");
}

function closeModal() {
    const modal = document.getElementById("modalTentukanKurir");
    if (modal) {
        modal.classList.add("hidden");
    }
    if (headerEl) headerEl.classList.remove("darken");
}

// --- Logika Modal Detail Pengiriman (Diperbarui dan Diperbaiki) ---
function showDetailModal(buttonElement) {
    // Membaca semua data dari atribut data-* tombol
    const data = buttonElement.dataset;

    // Default ke string kosong jika data tidak ada, untuk mencegah error
    const resi = data.resi || "";
    const pengirim = data.pengirim || "";
    const alamatJemput = data.alamatJemput || "";
    const penerima = data.penerima || "";
    const alamatTujuan = data.alamatTujuan || "";
    const kurir = data.kurir || "";
    const tanggal = data.tanggal || "";
    const berat = data.berat || "";
    const harga = data.harga || ""; // Ini penting untuk perbaikan error 'replace'
    const status = data.status || "";
    const catatan = data.catatan || "";

    // Mengisi nilai ke dalam form modal
    document.getElementById("resiDetail").value = resi;
    document.getElementById("pengirimDetail").value = pengirim;
    document.getElementById("alamatJemputDetail").value = alamatJemput;
    document.getElementById("penerimaDetail").value = penerima;
    document.getElementById("alamatTujuanDetail").value = alamatTujuan;
    document.getElementById("beratDetail").value = berat;
    document.getElementById("hargaDetail").value = harga.replace(/\D/g, ""); // Aman digunakan sekarang
    document.getElementById("kurirDetail").value = kurir;
    document.getElementById("statusDetail").value = status.toLowerCase();
    document.getElementById("tanggalDetail").value = tanggal;
    document.getElementById("catatanDetail").value = catatan;

    // Menampilkan modal
    if (headerEl) headerEl.classList.add("darken");
    document.getElementById("modalDetail").classList.remove("hidden");
}

function closeDetailModal() {
    document.getElementById("modalDetail").classList.add("hidden");
    if (headerEl) headerEl.classList.remove("darken");
}
