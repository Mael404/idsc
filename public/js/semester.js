document.addEventListener('DOMContentLoaded', function () {
    const editButtons = document.querySelectorAll('.toggle-semester-btn');
    const modal = document.getElementById('editSemesterModal');
    const cancelButton = document.getElementById('cancelButton');
    
    if (editButtons.length && modal) {
        // Open Modal when Edit button is clicked
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const name = this.dataset.name;

                // Set input value
                document.getElementById('modal-semester-name').value = name;

                // Set form action
                const form = document.getElementById('editSemesterForm');
                form.action = `/semesters/${id}`;

                // Show the modal using Bootstrap Modal API
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                // Add Cancel Button Event Listener to Close Modal
                cancelButton.addEventListener('click', function() {
                    bsModal.hide();  // Hide the modal
                });
            });
        });
    }
});