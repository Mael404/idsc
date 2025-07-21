$(document).on('click', '.toggle-program-btn', function() {
    // Get data attributes from the clicked button
    var programId = $(this).data('id');
    var programName = $(this).data('name');
    var programCode = $(this).data('code');
    var programEffectiveSchoolYear = $(this).data('effective_school_year');

    // Set modal fields
    $('#program-name').val(programName);
    $('#program-code').val(programCode);
    $('#program-effective-school-year').val(programEffectiveSchoolYear);

    // Dynamically set the form action for the PUT request
    $('#viewEditProgramForm').attr('action', '/programs/' + programId); // Update the action URL

    // Show the modal
    $('#viewEditProgramModal').modal('show');
});
