

    document.addEventListener('DOMContentLoaded', function () {
        const viewButtons = document.querySelectorAll('.view-course-btn');
        const modal = document.getElementById('editCourseModal');
    
        const editSearchInput = document.getElementById('edit-prerequisite-search');
        const editSearchResults = document.getElementById('edit-search-results');
        const editSelectedList = document.getElementById('edit-selected-prerequisites');
        const editHiddenInputs = document.getElementById('edit-prerequisite-hidden-inputs');
    
 
        let selectedCourseIds = new Set();
    
        // RENDER SEARCH RESULTS
        function renderEditSearchResults(searchTerm) {
            editSearchResults.innerHTML = '';
    
            if (searchTerm.length === 0) return;
    
            const filtered = allCourses.filter(course =>
                course.name.toLowerCase().includes(searchTerm.toLowerCase()) &&
                !selectedCourseIds.has(String(course.id))
            );
    
            if (filtered.length === 0) {
                editSearchResults.innerHTML = `<div class="list-group-item disabled">No courses found</div>`;
                return;
            }
    
            filtered.forEach(course => {
                const button = document.createElement('button');
                button.type = 'button';
                button.classList.add('list-group-item', 'list-group-item-action');
                button.setAttribute('data-id', course.id);
                button.setAttribute('data-name', course.name);
                button.innerText = course.name;
                editSearchResults.appendChild(button);
            });
        }
    
        // ADD PREREQUISITE TO LIST
        function addPrerequisite(courseId, courseName) {
            if (selectedCourseIds.has(courseId)) return;
    
            selectedCourseIds.add(courseId);
    
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
            listItem.setAttribute('data-id', courseId);
            listItem.id = 'edit-selected-' + courseId;
            listItem.innerHTML = `
                ${courseName}
                <button type="button" class="btn btn-sm btn-danger remove-prerequisite" data-id="${courseId}">
                    Remove
                </button>
            `;
            editSelectedList.appendChild(listItem);
    
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'prerequisite_id[]';
            input.value = courseId;
            input.id = 'edit-input-' + courseId;
            input.setAttribute('data-id', courseId);
            editHiddenInputs.appendChild(input);
        }
    
        // HANDLE SEARCH INPUT
        editSearchInput.addEventListener('input', function () {
            renderEditSearchResults(this.value);
        });
    
        // HANDLE SEARCH SELECTION
        editSearchResults.addEventListener('click', function (e) {
            if (e.target.tagName === 'BUTTON') {
                const courseId = e.target.getAttribute('data-id');
                const courseName = e.target.getAttribute('data-name');
                addPrerequisite(courseId, courseName);
    
                editSearchInput.value = '';
                editSearchResults.innerHTML = '';
            }
        });
    
        // HANDLE REMOVE BUTTON
        editSelectedList.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-prerequisite')) {
                const courseId = e.target.getAttribute('data-id');
                document.getElementById('edit-selected-' + courseId)?.remove();
                document.getElementById('edit-input-' + courseId)?.remove();
                selectedCourseIds.delete(courseId);
                renderEditSearchResults(editSearchInput.value);
            }
        });
    
        // HANDLE VIEW BUTTON CLICK
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
                    const prerequisites = JSON.parse(this.dataset.prerequisites || '[]');
    
                    // Set form values
                    document.getElementById('modal-code').value = code;
                    document.getElementById('modal-name').value = name;
                    document.getElementById('modal-description').value = description;
                    document.getElementById('modal-units').value = units;
                    document.getElementById('modal-lecture-hours').value = lectureHours;
                    document.getElementById('modal-lab-hours').value = labHours;
    
                    // Set form action
                    const form = document.getElementById('editCourseForm');
                    form.action = `/courses/${id}`;
    
                    // Clear prerequisites UI
                    editSelectedList.innerHTML = '';
                    editHiddenInputs.innerHTML = '';
                    selectedCourseIds = new Set();
    
                    // Re-render existing prerequisites
                    prerequisites.forEach(prerequisite => {
                        addPrerequisite(String(prerequisite.id), prerequisite.name);
                    });
    
                    // Show modal
                    const bsModal = new bootstrap.Modal(modal);
                    bsModal.show();
                });
            });
        }
    
        // RE-INITIALIZE SELECTED IDS ON MODAL OPEN
        modal.addEventListener('shown.bs.modal', function () {
            selectedCourseIds = new Set();
    
            document.querySelectorAll('#edit-selected-prerequisites li').forEach(item => {
                const id = item.getAttribute('data-id');
                if (id) selectedCourseIds.add(id);
            });
    
            renderEditSearchResults(editSearchInput.value);
        });
    });
  
    