document.addEventListener('DOMContentLoaded', () => {
    initStudentEditModal();
    initTablePasswordToggles();
});

/**
 * Mengisi form edit siswa dari data tombol pemicu modal.
 */
function initStudentEditModal() {
    const modalEl = document.getElementById('studentEditModal');

    if (!modalEl) {
        return;
    }

    const form = modalEl.querySelector('form');
    const idField = modalEl.querySelector('[data-student-id-field]');
    const nameField = modalEl.querySelector('[data-student-name-field]');
    const emailField = modalEl.querySelector('[data-student-email-field]');
    const passwordField = modalEl.querySelector('[data-student-password-field]');
    const currentPasswordText = modalEl.querySelector('[data-student-current-password-text]');
    const toggleCurrentBtn = modalEl.querySelector('.toggle-modal-current-password');
    const title = modalEl.querySelector('[data-student-modal-title]');

    modalEl.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;

        if (!trigger || !form) {
            return;
        }

        const studentId = trigger.dataset.studentId;
        const updateUrl = trigger.dataset.studentUpdateUrl;
        const name = trigger.dataset.studentName || '';
        const email = trigger.dataset.studentEmail || '';
        const plainPassword = trigger.dataset.studentPlainPassword || '';

        if (updateUrl) {
            form.action = updateUrl;
        }

        if (idField) {
            idField.value = studentId || '';
        }

        if (nameField) {
            nameField.value = name;
        }

        if (emailField) {
            emailField.value = email;
        }

        if (passwordField) {
            passwordField.value = '';
        }

        if (currentPasswordText) {
            currentPasswordText.dataset.plain = plainPassword;
            currentPasswordText.textContent = '••••••••';
            if (toggleCurrentBtn) {
                const icon = toggleCurrentBtn.querySelector('i');
                if (icon) icon.className = 'bi bi-eye';
            }
        }

        if (title) {
            title.textContent = `Ubah Siswa ${name}`;
        }
    });

    if (toggleCurrentBtn && currentPasswordText) {
        toggleCurrentBtn.addEventListener('click', () => {
            const plain = currentPasswordText.dataset.plain || '';
            const isMasked = currentPasswordText.textContent === '••••••••';
            currentPasswordText.textContent = isMasked ? (plain || '(Belum diset/hashed)') : '••••••••';
            const icon = toggleCurrentBtn.querySelector('i');
            if (icon) {
                icon.className = isMasked ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    }
}

/**
 * Mengatur toggle lihat/sembunyikan password pada tabel kelola siswa admin.
 */
function initTablePasswordToggles() {
    document.querySelectorAll('.toggle-table-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const container = btn.closest('div');
            if (!container) return;

            const textEl = container.querySelector('.student-password-masked');
            if (!textEl) return;

            const plain = textEl.dataset.plain || '';
            const isMasked = textEl.textContent === '••••••••';
            textEl.textContent = isMasked ? plain : '••••••••';

            const icon = btn.querySelector('i');
            if (icon) {
                icon.className = isMasked ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    });
}
