
@if (session('success'))
<div id="success-alert" class="popup-alert fadeDownIn shadow rounded-lg p-4">
    <div class="d-flex justify-content-between align-items-center">
        <span class="fw-semibold fs-5 text-success-custom">
            {{ session('success') }}
            <i class="fas fa-check-circle ms-1"></i>
            <!-- Added ms-3 for spacing and positioned icon on the right -->
        </span>
    </div>
</div>
@endif


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
