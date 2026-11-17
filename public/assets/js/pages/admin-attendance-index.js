/**
 * Admin Attendance & Office Location Manager JS
 */
document.addEventListener('DOMContentLoaded', () => {
    initLocationEditModal();
    initCurrentLocationButtons();
});

/**
 * Mengisi data form modal edit lokasi cabang dari atribut data tombol trigger.
 */
function initLocationEditModal() {
    const editModalEl = document.getElementById('locationEditModal');
    if (!editModalEl) return;

    const form = editModalEl.querySelector('form');
    const nameField = editModalEl.querySelector('[data-edit-location-name]');
    const latField = editModalEl.querySelector('[data-edit-location-latitude]');
    const lngField = editModalEl.querySelector('[data-edit-location-longitude]');
    const radiusField = editModalEl.querySelector('[data-edit-location-radius]');
    const titleEl = editModalEl.querySelector('[data-edit-location-title]');

    editModalEl.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        if (!trigger || !form) return;

        const updateUrl = trigger.dataset.locationUpdateUrl;
        const name = trigger.dataset.locationName || '';
        const lat = trigger.dataset.locationLatitude || '';
        const lng = trigger.dataset.locationLongitude || '';
        const radius = trigger.dataset.locationRadius || '100';

        if (updateUrl) form.action = updateUrl;
        if (nameField) nameField.value = name;
        if (latField) latField.value = lat;
        if (lngField) lngField.value = lng;
        if (radiusField) radiusField.value = radius;
        if (titleEl) titleEl.textContent = `Ubah Lokasi: ${name}`;
    });
}

/**
 * Mengambil koordinat GPS browser untuk mengisi input Latitude dan Longitude secara otomatis.
 */
function initCurrentLocationButtons() {
    document.querySelectorAll('[data-get-current-coords]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung deteksi lokasi (GPS).');
                return;
            }

            const targetForm = btn.closest('form');
            if (!targetForm) return;

            const latInput = targetForm.querySelector('input[name="latitude"]');
            const lngInput = targetForm.querySelector('input[name="longitude"]');

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mendeteksi...';

            try {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    });
                });

                if (latInput) latInput.value = position.coords.latitude.toFixed(8);
                if (lngInput) lngInput.value = position.coords.longitude.toFixed(8);
            } catch (err) {
                alert('Gagal mengambil koordinat lokasi. Pastikan izin GPS aktif.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        });
    });
}
