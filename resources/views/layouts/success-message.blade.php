@if (session('success'))
    <div id="success-alert" class="popup-alert fadeDownIn shadow rounded-lg p-4">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold fs-6 text-success-custom">
                {{ session('success') }}
                <i class="fas fa-check-circle ms-1"></i>
            </span>
        </div>
    </div>
@endif
@if (session('error'))
<div id="error-alert" class="popup-alert fadeDownIn shadow rounded-lg p-4" style="background-color: #dc3545; color: #fff;">
    <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold fs-6">
            {{ session('error') }}
            <i class="fas fa-exclamation-circle ms-1"></i>
        </span>
    </div>
</div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const errorAlert = document.getElementById('error-alert');
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.classList.remove('fadeDownIn');
                errorAlert.classList.add('fadeOut');
                setTimeout(() => {
                    errorAlert.remove();
                }, 400);
            }, 3000);
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('fadeDownIn');
                alert.classList.add('fadeOut');
                setTimeout(() => {
                    alert.remove();
                }, 400);
            }, 2500);
        }
    });
</script>
