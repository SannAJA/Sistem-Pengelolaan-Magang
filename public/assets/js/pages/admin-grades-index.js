document.addEventListener('DOMContentLoaded', () => {
    initGradeEditModal();
});

/**
 * Mengisi form edit nilai dari data tombol pemicu modal.
 */
function initGradeEditModal() {
    const modalEl = document.getElementById('gradeEditModal');

    if (!modalEl) {
        return;
    }

    const form = modalEl.querySelector('form');
    const idField = modalEl.querySelector('[data-grade-id-field]');
    const scoreField = modalEl.querySelector('[data-grade-score-field]');
    const feedbackField = modalEl.querySelector('[data-grade-feedback-field]');
    const title = modalEl.querySelector('[data-grade-modal-title]');
    const subtitle = modalEl.querySelector('[data-grade-modal-subtitle]');

    modalEl.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;

        if (!trigger || !form) {
            return;
        }

        const gradeId = trigger.dataset.gradeId;
        const updateUrl = trigger.dataset.gradeUpdateUrl;
        const score = trigger.dataset.gradeScore || '';
        const feedback = trigger.dataset.gradeFeedback ? JSON.parse(trigger.dataset.gradeFeedback) : '';
        const studentName = trigger.dataset.gradeStudentName || '';

        if (updateUrl) {
            form.action = updateUrl;
        }

        if (idField) {
            idField.value = gradeId || '';
        }

        if (scoreField) {
            scoreField.value = score;
        }

        if (feedbackField) {
            feedbackField.value = feedback;
        }

        if (title) {
            title.textContent = 'Ubah Nilai';
        }

        if (subtitle) {
            subtitle.textContent = studentName ? `Perbarui nilai akhir dan feedback untuk ${studentName}.` : 'Perbarui nilai akhir dan feedback siswa.';
        }
    });
}
