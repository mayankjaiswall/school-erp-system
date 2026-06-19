<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    $('#parentForm').on('submit', function (event) {
        event.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        button.prop('disabled', true);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                Swal.fire({icon:'success', title:'Saved', text:response.message, timer:1400, showConfirmButton:false})
                    .then(() => window.location.href = response.redirect);
            },
            error: function (xhr) {
                button.prop('disabled', false);
                let message = 'Please check the form and try again.';
                if (xhr.responseJSON?.errors) message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                else if (xhr.responseJSON?.message) message = xhr.responseJSON.message;
                Swal.fire({icon:'error', title:'Unable to save parent', html:message});
            }
        });
    });
});
</script>
