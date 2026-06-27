<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()}
    });

    function childPayload(option) {
        return {
            student_id: option.data('student-id'),
            relationship: option.find('.relationship-select').val()
        };
    }

    function showToast(icon, message) {
        Swal.fire({toast:true, position:'top-end', icon:icon, title:message, timer:1500, showConfirmButton:false});
    }

    const childrenSection = $('#childrenLinkSection');
    const hasLiveLinking = Boolean(childrenSection.data('link-url'));
    const childPicker = $('[data-child-picker]');
    const childPickerText = $('[data-child-picker-text]');
    const selectedList = $('[data-child-selected-list]');

    function updateChildPickerSummary() {
        const checkedOptions = $('.child-check:checked').closest('[data-child-option]');
        const count = checkedOptions.length;

        childPickerText.text(count ? `${count} child${count > 1 ? 'ren' : ''} selected` : 'Select children');
        selectedList.empty();

        checkedOptions.each(function () {
            const option = $(this);
            selectedList.append(
                $('<span/>', {
                    class: 'child-chip',
                    text: `${option.data('student-name')} - ${option.find('.relationship-select').val()}`
                })
            );
        });
    }

    childPicker.find('.child-picker-toggle').on('click', function () {
        const isOpen = childPicker.toggleClass('open').hasClass('open');
        $(this).attr('aria-expanded', isOpen ? 'true' : 'false');
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('[data-child-picker]').length) {
            childPicker.removeClass('open');
            childPicker.find('.child-picker-toggle').attr('aria-expanded', 'false');
        }
    });

    $('.child-option').on('click', function (event) {
        if ($(event.target).is('input, select, option')) {
            return;
        }

        const checkbox = $(this).find('.child-check');
        checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
    });

    if (hasLiveLinking) {
        $('.child-check').on('change', function () {
            const checkbox = $(this);
            const option = checkbox.closest('[data-child-option]');
            const checked = checkbox.prop('checked');
            checkbox.prop('disabled', true);
            updateChildPickerSummary();

            $.ajax({
                url: checked ? childrenSection.data('link-url') : childrenSection.data('remove-url'),
                method: checked ? 'POST' : 'DELETE',
                data: childPayload(option),
                dataType: 'json',
                success: function (response) {
                    showToast('success', response.message);
                },
                error: function (xhr) {
                    checkbox.prop('checked', !checked);
                    updateChildPickerSummary();
                    showToast('error', xhr.responseJSON?.message || 'Unable to update child link.');
                },
                complete: function () {
                    checkbox.prop('disabled', false);
                }
            });
        });

        $('.relationship-select').on('change', function () {
            const select = $(this);
            const option = select.closest('[data-child-option]');
            updateChildPickerSummary();

            if (!option.find('.child-check').prop('checked')) {
                return;
            }

            select.prop('disabled', true);
            $.ajax({
                url: childrenSection.data('relationship-url'),
                method: 'PATCH',
                data: childPayload(option),
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
    } else {
        $('.child-check, .relationship-select').on('change', updateChildPickerSummary);
    }

    updateChildPickerSummary();

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
