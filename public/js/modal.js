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

function bukaModal() {
    headerEl.classList.add('darken');
    document.getElementById('popup-modal').classList.remove('hidden');
}

function tutupModal() {
    document.getElementById('popup-modal').classList.add('hidden');
    headerEl.classList.remove('darken');
}