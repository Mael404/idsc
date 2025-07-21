document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.toggle-year-level-btn');
    const editYearLevelModal = document.getElementById('editYearLevelModal');

    if (editButtons.length && editYearLevelModal) {
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;

                // Set values in the modal
                document.getElementById('modal-name').value = name;

                const form = document.getElementById('editYearLevelForm');
                form.action = `/year/${id}`;

                // Initialize Bootstrap Modal and show it
                const bsModal = new bootstrap.Modal(editYearLevelModal);
                bsModal.show();
            });
        });
    }
});
