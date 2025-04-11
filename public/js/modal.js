function bukaModal(nama) {
    document.getElementById('popup-modal').classList.remove('hidden');
    const box = document.getElementById('popup-box');
    box.classList.remove('modal-bouncy'); // Reset animasi
    void box.offsetWidth; // Trigger reflow
    box.classList.add('modal-bouncy');
}

function tutupModal() {
    document.getElementById('popup-modal').classList.add('hidden');
}
