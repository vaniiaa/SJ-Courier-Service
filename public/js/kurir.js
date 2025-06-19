let formToDeleteId = null;

function bukaModal(id, name) {
    formToDeleteId = id;
    document.getElementById('modal-item-name').textContent = name;

    const modal = document.getElementById('popup-modal');
    modal.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
    modal.classList.add('opacity-100');

    document.querySelector('body').classList.add('modal-open');
}

function tutupModal() {
    const modal = document.getElementById('popup-modal');
    const modalContent = modal.querySelector('div > div');

    modalContent.classList.add('scale-90');
    modalContent.classList.remove('scale-100');

    setTimeout(() => {
        modal.classList.add('opacity-0', 'pointer-events-none', 'hidden');
        modal.classList.remove('opacity-100');
        formToDeleteId = null;
        document.querySelector('body').classList.remove('modal-open');
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', () => {
            if (formToDeleteId) {
                const form = document.getElementById('hapusForm-' + formToDeleteId);
                if (form) {
                    form.submit();
                }
            }
        });
    }
});

 document.addEventListener('DOMContentLoaded', function() {
        const wilayahSelect = document.getElementById('wilayahPengiriman');
        const kurirSelectDropdown = document.getElementById('kurirSelect');
        const formTentukanKurir = document.getElementById('formTentukanKurir');
        const shipmentIdToAssignInput = document.getElementById('shipmentIdToAssign');
        const selectedKurirIdInput = document.getElementById('selectedKurirId');
        const modalTentukanKurir = document.getElementById('modalTentukanKurir');
        const modalDetail = document.getElementById('modalDetail');
        // Pastikan elemen header Anda memiliki ID 'admin-header'
        const adminHeader = document.getElementById('admin-header');

        // Periksa elemen yang *masih* ada
        if (!wilayahSelect || !kurirSelectDropdown || !formTentukanKurir || !shipmentIdToAssignInput || !selectedKurirIdInput || !modalTentukanKurir || !modalDetail || !adminHeader) {
            console.error('One or more critical HTML elements for modal/form are missing. Please check your IDs and ensure admin-header exists.');
            return;
        }

        window.openModal = function(shipmentId) {
            modalTentukanKurir.classList.remove('hidden');
            // Add the class to darken the header
            adminHeader.classList.add('header-darken');
            shipmentIdToAssignInput.value = shipmentId;
            // Reset modal state
            wilayahSelect.value = '';
            kurirSelectDropdown.innerHTML = '<option value="">Pilih kurir...</option>';
            selectedKurirIdInput.value = ''; // Ensure no courier ID is pre-selected
            document.getElementById('tanggalPengiriman').valueAsDate = new Date();
            document.getElementById('catatan').value = '';
        };

        window.closeModal = function() {
            modalTentukanKurir.classList.add('hidden');
            // Remove the class to revert the header
            adminHeader.classList.remove('header-darken');
        };

        window.showDetailModal = function(resi, pengirim, alamatJemput, penerima, alamatTujuan, kurir, tanggalPemesanan, berat, harga, status, catatan) {
            document.getElementById('resiDetail').value = resi;
            document.getElementById('pengirimDetail').value = pengirim;
            document.getElementById('alamatJemputDetail').value = alamatJemput;
            document.getElementById('penerimaDetail').value = penerima;
            document.getElementById('alamatTujuanDetail').value = alamatTujuan;
            document.getElementById('kurirDetail').value = kurir;
            document.getElementById('tanggalDetail').value = tanggalPemesanan;
            document.getElementById('beratDetail').value = berat;
            document.getElementById('hargaDetail').value = harga;
            document.getElementById('statusDetail').value = status;
            document.getElementById('catatanDetail').value = catatan;
            modalDetail.classList.remove('hidden');
            // Add the class to darken the header
            adminHeader.classList.add('header-darken');
        };

        window.closeDetailModal = function() {
            modalDetail.classList.add('hidden');
            // Remove the class to revert the header
            adminHeader.classList.remove('header-darken');
        };

        window.printData = function(resi) {
            alert('Fungsi print untuk Resi: ' + resi + ' akan ditambahkan di sini.');
            // Implement printing logic here
            // Example: window.open('/print/resi/' + resi, '_blank');
        };

        wilayahSelect.addEventListener('change', async function() {
            const selectedWilayah = this.value;
            kurirSelectDropdown.innerHTML = '<option value="">Loading...</option>';
            selectedKurirIdInput.value = ''; // Reset selected ID when region changes

            if (selectedWilayah) {
                const url = `{{ route('kurir.byWilayah', ['wilayah' => '__WILAYAH__']) }}`.replace('__WILAYAH__', encodeURIComponent(selectedWilayah));
                try {
                    const response = await fetch(url);
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                    }
                    const data = await response.json();
                    kurirSelectDropdown.innerHTML = '<option value="">Pilih kurir...</option>';
                    if (data.length === 0) {
                        kurirSelectDropdown.innerHTML = '<option value="">Tidak ada kurir untuk wilayah ini</option>';
                    } else {
                        data.forEach(kurir => {
                            const option = document.createElement('option');
                            option.value = kurir.id;
                            option.textContent = kurir.username;
                            kurirSelectDropdown.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error fetching courier data by region:', error);
                    kurirSelectDropdown.innerHTML = '<option value="">Gagal memuat kurir</option>';
                    alert('Gagal memuat daftar kurir: ' + error.message);
                }
            } else {
                kurirSelectDropdown.innerHTML = '<option value="">Pilih kurir...</option>';
            }
        });

        // Update hidden kurir_id input when dropdown selection changes
        kurirSelectDropdown.addEventListener('change', function() {
            // Hanya set selectedKurirIdInput jika nilai yang dipilih bukan kosong
            if (this.value) {
                selectedKurirIdInput.value = this.value;
            } else {
                selectedKurirIdInput.value = ''; // Jika memilih "Pilih kurir...", reset ID
            }
        });

        formTentukanKurir.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Basic validation for courier selection
            if (!selectedKurirIdInput.value) {
                alert('Harap pilih kurir terlebih dahulu dari daftar wilayah.');
                return;
            }

            const formData = new FormData(this);
            formData.set('kurir_id', selectedKurirIdInput.value); // Pastikan ini yang dikirim

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    let errorMessage = errorData.message || `Server error! Status: ${response.status}`;
                    if (errorData.errors) {
                        errorMessage += '\n\nDetail Kesalahan:\n' + Object.values(errorData.errors).flat().join('\n');
                    }
                    alert('Terjadi kesalahan saat menetapkan kurir: ' + errorMessage);
                } else {
                    const successData = await response.json();
                    alert(successData.message);
                    closeModal(); // Tutup modal setelah berhasil
                    location.reload(); // Muat ulang halaman untuk melihat perubahan
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                alert('Gagal mengirim permintaan: ' + error.message);
            }
        });

        function ucfirst(str) {
            if (!str) return str;
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');

    function showNotification(alertElement) {
        if (alertElement) {
            alertElement.style.display = 'block';
            setTimeout(() => {
                alertElement.classList.add('show');
            }, 100);
        }
    }

    function hideNotification(alertElement) {
        if (alertElement) {
            alertElement.classList.remove('show');
            alertElement.addEventListener('transitionend', function() {
                alertElement.style.display = 'none';
            }, { once: true });
        }
    }

    if (successAlert) {
        showNotification(successAlert);
        setTimeout(() => {
            hideNotification(successAlert);
        }, 5000); // tampil 8 detik
    }

    if (errorAlert) {
        showNotification(errorAlert);
        setTimeout(() => {
            hideNotification(errorAlert);
        }, 5000); // tampil 8 detik
    }
});