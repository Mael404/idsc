document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-course-btn');
    const modal = document.getElementById('editCourseModal');

    if (viewButtons.length && modal) {
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const code = this.dataset.code;
                const name = this.dataset.name;
                const description = this.dataset.description;
                const units = this.dataset.units;
                const lectureHours = this.dataset.lectureHours;
                const labHours = this.dataset.labHours;
                const prerequisiteId = this.dataset.prerequisiteId;

                // Set the form fields with the course data
                document.getElementById('modal-code').value = code;
                document.getElementById('modal-name').value = name;
                document.getElementById('modal-description').value = description;
                document.getElementById('modal-units').value = units;
                document.getElementById('modal-lecture-hours').value = lectureHours;
                document.getElementById('modal-lab-hours').value = labHours;

                // Set the prerequisite dropdown value (if any)
                const prerequisiteSelect = document.getElementById('modal-prerequisite');
                prerequisiteSelect.value = prerequisiteId ? prerequisiteId : ''; // Set the value to the course's prerequisite or 'None'

                const form = document.getElementById('editCourseForm');
                form.action = `/courses/${id}`; // Set the form action for updating the course

                // Show the modal
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
            });
        });
    }
});
