<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}
    });

    function rowPayload(row) {
        return {
            student_id: row.data('student-id'),
            relationship: row.find('.relationship-select').val()
        };
    }

    function showToast(icon, message) {
        Swal.fire({toast:true, position:'top-end', icon:icon, title:message, timer:1500, showConfirmButton:false});
    }

    const childrenSection = $('#childrenLinkSection');
    const hasLiveLinking = Boolean(childrenSection.data('link-url'));

    if (hasLiveLinking) {
        $('.child-check').on('change', function () {
            const checkbox = $(this);
            const row = checkbox.closest('tr');
            const checked = checkbox.prop('checked');
            checkbox.prop('disabled', true);

            $.ajax({
                url: checked ? childrenSection.data('link-url') : childrenSection.data('remove-url'),
                method: checked ? 'POST' : 'DELETE',
                data: rowPayload(row),
                dataType: 'json',
                success: function (response) {
                    showToast('success', response.message);
                },
                error: function (xhr) {
                    checkbox.prop('checked', !checked);
                    showToast('error', xhr.responseJSON?.message || 'Unable to update child link.');
                },
                complete: function () {
                    checkbox.prop('disabled', false);
                }
            });
        });

        $('.relationship-select').on('change', function () {
            const select = $(this);
            const row = select.closest('tr');

            if (!row.find('.child-check').prop('checked')) {
                return;
            }

            select.prop('disabled', true);
            $.ajax({
                url: childrenSection.data('relationship-url'),
                method: 'PATCH',
                data: rowPayload(row),
                dataType: 'json',
                success: function (response) {
                    showToast('success', response.message);
                },
                error: function (xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Unable to update relationship.');
                },
                complete: function () {
                    select.prop('disabled', false);
                }
            });
        });
    }

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
