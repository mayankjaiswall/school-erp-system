(function () {
    const forms = document.querySelectorAll('[data-ajax-user-form]');

    if (!forms.length) {
        return;
    }

    function showAlert(options) {
        if (window.Swal) {
            return Swal.fire(options);
        }

        window.alert(options.text || options.title || 'Request completed.');
        return Promise.resolve();
    }

    function findField(form, name) {
        return form.querySelector('[name="' + name.replace(/"/g, '\\"') + '"]');
    }

    function clearErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(function (field) {
            field.classList.remove('is-invalid');
        });

        form.querySelectorAll('.ajax-invalid-feedback, .ajax-form-alert').forEach(function (element) {
            element.remove();
        });
    }

    function showErrors(form, errors) {
        const firstMessage = Object.values(errors)[0]?.[0] || 'Please check the highlighted fields.';
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger ajax-form-alert';
        alert.textContent = firstMessage;
        form.prepend(alert);

        Object.entries(errors).forEach(function ([name, messages]) {
            const field = findField(form, name);

            if (!field) {
                return;
            }

            field.classList.add('is-invalid');

            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback ajax-invalid-feedback d-block';
            feedback.textContent = messages[0];
            field.insertAdjacentElement('afterend', feedback);
        });

        const firstInvalid = form.querySelector('.is-invalid');

        if (firstInvalid) {
            firstInvalid.focus({ preventScroll: true });
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    forms.forEach(function (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            clearErrors(form);

            const submitButton = form.querySelector('[type="submit"]');
            const originalButtonHtml = submitButton ? submitButton.innerHTML : '';

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Saving...';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                });

                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json')
                    ? await response.json()
                    : { message: await response.text() };

                if (response.status === 422 && data.errors) {
                    showErrors(form, data.errors);
                    return;
                }

                if (!response.ok) {
                    throw new Error(data.message || 'Unable to save user. Please try again.');
                }

                await showAlert({
                    icon: 'success',
                    title: 'Success',
                    text: data.message || 'User saved successfully.',
                    timer: 1200,
                    showConfirmButton: false,
                });

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } catch (error) {
                showAlert({
                    icon: 'error',
                    title: 'Unable to save user',
                    text: error.message || 'Please try again.',
                });
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonHtml;
                }
            }
        });
    });
})();
