<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function () {
    $('#{{ $formId }}').submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ $successTitle }}',
                    text: response.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ $redirectUrl }}";
                });
            },
            error: function (xhr) {
                let message = 'Please check the form and try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Validation failed',
                    text: message
                });
            }
        });
    });
});
</script>
