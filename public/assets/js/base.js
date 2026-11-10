/**
 * Base Application JS - Klik Media Sistem Pengelolaan Magang
 * Includes Image Preview, Auto-Open Modal, and Custom Animated AppDialog System
 */

document.addEventListener('DOMContentLoaded', () => {
    initImagePreview();
    initAutoOpenModal();
    initConfirmHandlers();
    initPasswordToggles();
});

/**
 * Menampilkan pratinjau gambar pada modal Bootstrap saat elemen preview diklik.
 */
function initImagePreview() {
    const modalEl = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('imagePreviewSource');
    const modalTitle = document.getElementById('imagePreviewTitle');

    document.querySelectorAll('[data-preview-image]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const src = trigger.dataset.previewSrc;
            const title = trigger.dataset.previewTitle || 'Pratinjau Gambar';

            if (modalImage) {
                modalImage.src = src;
            }

            if (modalTitle) {
                modalTitle.textContent = title;
            }

            if (modalEl && window.bootstrap) {
                window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        });
    });
}

/**
 * Membuka modal yang sebelumnya memicu validasi gagal berdasarkan data atribut pada body.
 */
function initAutoOpenModal() {
    const modalId = document.body?.dataset.autoOpenModal;

    if (!modalId) {
        return;
    }

    const modalEl = document.getElementById(modalId);

    if (modalEl && window.bootstrap) {
        window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
}

/**
 * Intersep form atau tombol dengan atribut data-confirm agar memunculkan AppDialog.confirm kustom
 */
function initConfirmHandlers() {
    document.addEventListener('submit', (event) => {
        const form = event.target;
        const confirmMsg = form.getAttribute('data-confirm');

        if (confirmMsg && !form.dataset.confirmed) {
            event.preventDefault();

            const title = form.getAttribute('data-confirm-title') || 'Konfirmasi Tindakan';
            const confirmText = form.getAttribute('data-confirm-btn') || 'Ya, Lanjutkan';
            const cancelText = form.getAttribute('data-cancel-btn') || 'Batal';
            const type = form.getAttribute('data-confirm-type') || 'danger';

            AppDialog.confirm({
                title: title,
                message: confirmMsg,
                confirmText: confirmText,
                cancelText: cancelText,
                type: type,
                onConfirm: () => {
                    form.dataset.confirmed = 'true';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit(event.submitter);
                    } else {
                        form.submit();
                    }
                }
            });
        }
    });
}

/**
 * Toggle visibility password pada field yang memakai data-password-toggle.
 */
function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-password-input]');
        const button = wrapper.querySelector('[data-password-toggle-button]');
        const icon = wrapper.querySelector('[data-password-toggle-icon]');

        if (!input || !button || !icon) {
            return;
        }

        const setState = (isVisible) => {
            input.type = isVisible ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(isVisible));
            button.setAttribute('aria-label', isVisible ? 'Sembunyikan password' : 'Tampilkan password');
            icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
        };

        button.addEventListener('click', () => {
            setState(input.type === 'password');
        });
    });

    document.querySelectorAll('.toggle-password-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const input = document.getElementById(targetId);
            if (!input) return;

            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            const icon = btn.querySelector('i');
            if (icon) {
                icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
            }
        });
    });
}

/**
 * Custom Animated Popup & Warning Dialog System (AppDialog)
 */
const AppDialog = (function () {
    let activeBackdrop = null;

    const iconMap = {
        warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
        danger: '<i class="bi bi-shield-x"></i>',
        success: '<i class="bi bi-check-circle-fill"></i>',
        info: '<i class="bi bi-info-circle-fill"></i>'
    };

    function createDialog({
        type = 'info',
        title = '',
        message = '',
        confirmText = 'Mengerti',
        cancelText = null,
        onConfirm = null,
        onCancel = null
    }) {
        closeDialog(); // Tutup dialog aktif jika ada

        const backdrop = document.createElement('div');
        backdrop.className = `app-dialog-backdrop app-dialog-${type}`;

        const isDanger = type === 'danger';
        const isWarning = type === 'warning';
        const confirmBtnClass = isDanger ? 'btn-klik-primary' : (isWarning ? 'btn-klik-primary' : 'btn-klik-secondary');

        let actionsHtml = '';
        if (cancelText) {
            actionsHtml = `
                <button type="button" class="btn btn-outline-secondary rounded-pill app-dialog-cancel-btn">
                    ${cancelText}
                </button>
                <button type="button" class="btn ${confirmBtnClass} rounded-pill app-dialog-confirm-btn">
                    ${confirmText}
                </button>
            `;
        } else {
            actionsHtml = `
                <button type="button" class="btn ${confirmBtnClass} rounded-pill w-100 app-dialog-confirm-btn">
                    ${confirmText}
                </button>
            `;
        }

        backdrop.innerHTML = `
            <div class="app-dialog-card">
                <div class="app-dialog-icon-wrap">
                    ${iconMap[type] || iconMap.info}
                </div>
                <h3 class="app-dialog-title">${title}</h3>
                <div class="app-dialog-message">${message}</div>
                <div class="app-dialog-actions">
                    ${actionsHtml}
                </div>
            </div>
        `;

        document.body.appendChild(backdrop);
        activeBackdrop = backdrop;

        // Force reflow and add active class for smooth entrance
        requestAnimationFrame(() => {
            backdrop.classList.add('active');
        });

        const confirmBtn = backdrop.querySelector('.app-dialog-confirm-btn');
        const cancelBtn = backdrop.querySelector('.app-dialog-cancel-btn');

        if (confirmBtn) {
            confirmBtn.focus();
            confirmBtn.addEventListener('click', () => {
                closeDialog(() => {
                    if (typeof onConfirm === 'function') onConfirm();
                });
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                closeDialog(() => {
                    if (typeof onCancel === 'function') onCancel();
                });
            });
        }

        // Close on backdrop click if not confirm dialog
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop && !cancelText) {
                closeDialog();
            }
        });

        // Close on Escape key
        const keyHandler = (e) => {
            if (e.key === 'Escape') {
                document.removeEventListener('keydown', keyHandler);
                if (cancelText && typeof onCancel === 'function') {
                    onCancel();
                }
                closeDialog();
            }
        };
        document.addEventListener('keydown', keyHandler);
    }

    function closeDialog(callback) {
        if (!activeBackdrop) return;

        const el = activeBackdrop;
        activeBackdrop = null;

        el.classList.remove('active');
        setTimeout(() => {
            if (el.parentNode) {
                el.parentNode.removeChild(el);
            }
            if (typeof callback === 'function') callback();
        }, 280);
    }

    return {
        warning(options) {
            createDialog({
                type: 'warning',
                title: options.title || 'Peringatan',
                message: options.message || options,
                confirmText: options.confirmText || 'Saya Mengerti',
                onConfirm: options.onConfirm
            });
        },
        danger(options) {
            createDialog({
                type: 'danger',
                title: options.title || 'Peringatan Bahaya',
                message: options.message || options,
                confirmText: options.confirmText || 'Lanjutkan',
                cancelText: options.cancelText || 'Batal',
                onConfirm: options.onConfirm,
                onCancel: options.onCancel
            });
        },
        confirm(options) {
            createDialog({
                type: options.type || 'danger',
                title: options.title || 'Konfirmasi Tindakan',
                message: options.message || options,
                confirmText: options.confirmText || 'Ya, Lanjutkan',
                cancelText: options.cancelText || 'Batal',
                onConfirm: options.onConfirm,
                onCancel: options.onCancel
            });
        },
        success(options) {
            createDialog({
                type: 'success',
                title: options.title || 'Berhasil',
                message: options.message || options,
                confirmText: options.confirmText || 'Selesai',
                onConfirm: options.onConfirm
            });
        },
        info(options) {
            createDialog({
                type: 'info',
                title: options.title || 'Informasi',
                message: options.message || options,
                confirmText: options.confirmText || 'Tutup',
                onConfirm: options.onConfirm
            });
        },
        alert(options) {
            if (typeof options === 'string') {
                createDialog({ message: options });
            } else {
                createDialog(options);
            }
        },
        close: closeDialog
    };
})();

// Export globally
window.AppDialog = AppDialog;
