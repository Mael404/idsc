document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-course-btn');
    const modal = document.getElementById('editCourseModal');

    if (viewButtons.length && modal) {
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                document.getElementById('modal-code').value = this.dataset.code;
                document.getElementById('modal-name').value = this.dataset.name;
                document.getElementById('modal-description').value = this.dataset.description;
                document.getElementById('modal-units').value = this.dataset.units;

                const form = document.getElementById('editCourseForm');
                form.action = `/courses/${id}`;

                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            });
        });
    }
});

