document.addEventListener('DOMContentLoaded', () => {
    initAttendanceForm();
});

/**
 * Mengambil koordinat pengguna, memvalidasi radius ke kantor, lalu mengirim data absensi.
 */
function initAttendanceForm() {
    const form = document.querySelector('[data-attendance-form]');

    if (!form) {
        return;
    }

    const latitudeField = form.querySelector('[data-attendance-latitude]');
    const longitudeField = form.querySelector('[data-attendance-longitude]');
    const officeLatitude = parseFloat(form.dataset.officeLatitude || '');
    const officeLongitude = parseFloat(form.dataset.officeLongitude || '');
    const maxRadiusMeters = parseFloat(form.dataset.maxRadiusMeters || '100');

    form.addEventListener('submit', async (event) => {
        if (form.dataset.locationReady === '1') {
            return;
        }

        event.preventDefault();

        if (!window.navigator.geolocation) {
            if (window.AppDialog) {
                window.AppDialog.warning({
                    title: 'Akses Lokasi Tidak Didukung',
                    message: 'Browser Anda tidak mendukung fitur deteksi lokasi (GPS).'
                });
            } else {
                window.alert('Browser Anda tidak mendukung akses lokasi.');
            }
            return;
        }

        const submitBtn = event.submitter || form.querySelector('button[type="submit"]');
        const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mendeteksi Lokasi...';
        }

        try {
            const position = await getCurrentPosition();
            const userLatitude = position.coords.latitude;
            const userLongitude = position.coords.longitude;
            const distanceMeters = calculateDistanceMeters(
                officeLatitude,
                officeLongitude,
                userLatitude,
                userLongitude
            );

            if (distanceMeters > maxRadiusMeters) {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                }

                const roundedDist = Math.round(distanceMeters);
                if (window.AppDialog) {
                    window.AppDialog.warning({
                        title: 'Di Luar Radius Kantor',
                        message: `Jarak Anda (${roundedDist} m) melebihi batas radius kantor (${maxRadiusMeters} m). Pastikan Anda berada di lokasi kantor untuk melakukan absensi.`
                    });
                } else {
                    window.alert('Jarak Terlalu Jauh, Tidak Bisa Melakukan Absensi');
                }
                return;
            }

            if (latitudeField) {
                latitudeField.value = userLatitude.toFixed(8);
            }

            if (longitudeField) {
                longitudeField.value = userLongitude.toFixed(8);
            }

            form.dataset.locationReady = '1';

            if (typeof form.requestSubmit === 'function' && event.submitter) {
                form.requestSubmit(event.submitter);
                return;
            }

            form.submit();
        } catch (error) {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }

            if (window.AppDialog) {
                window.AppDialog.warning({
                    title: 'Izin Lokasi Diperlukan',
                    message: 'Tidak dapat mendeteksi lokasi GPS Anda. Pastikan izin lokasi (GPS) pada browser sudah diaktifkan.'
                });
            } else {
                window.alert('Lokasi tidak dapat diakses. Pastikan izin GPS sudah aktif.');
            }
        }
    });
}

/**
 * Membungkus geolocation API ke dalam Promise agar mudah dipakai dengan async/await.
 */
function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        window.navigator.geolocation.getCurrentPosition(resolve, reject, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
        });
    });
}

/**
 * Menghitung jarak dua titik koordinat dengan rumus Haversine.
 */
function calculateDistanceMeters(latitudeFrom, longitudeFrom, latitudeTo, longitudeTo) {
    const earthRadius = 6371000;
    const toRadians = (degree) => (degree * Math.PI) / 180;

    const lat1 = toRadians(latitudeFrom);
    const lon1 = toRadians(longitudeFrom);
    const lat2 = toRadians(latitudeTo);
    const lon2 = toRadians(longitudeTo);

    const latitudeDelta = lat2 - lat1;
    const longitudeDelta = lon2 - lon1;

    const a =
        Math.sin(latitudeDelta / 2) ** 2 +
        Math.cos(lat1) * Math.cos(lat2) * Math.sin(longitudeDelta / 2) ** 2;

    return 2 * earthRadius * Math.asin(Math.min(1, Math.sqrt(a)));
}
