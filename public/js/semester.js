
document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.toggle-semester-btn');
    const modal = document.getElementById('editSemesterModal');

    if (editButtons.length && modal) {
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;

                // Set input value
                document.getElementById('modal-semester-name').value = name;

                // Set form action
                const form = document.getElementById('editSemesterForm');
                form.action = `/semesters/${id}`;

                // Show the modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            });
        });
    }
});

