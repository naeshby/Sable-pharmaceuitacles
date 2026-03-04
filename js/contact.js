// Contact form handling with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const inquirySelect = document.getElementById('inquiryType');
    if (!inquirySelect) return;

    const formSets = {
        general: document.getElementById('general-inquiry'),
        quotation: document.getElementById('quotation-request'),
        partnership: document.getElementById('partnership-inquiry'),
        support: document.getElementById('customer-support')
    };

    function showForm(formId) {
        Object.values(formSets).forEach(set => {
            if (set) set.classList.add('d-none');
        });
        if (formSets[formId]) formSets[formId].classList.remove('d-none');
    }

    showForm(inquirySelect.value);

    inquirySelect.addEventListener('change', function(e) {
        showForm(e.target.value);
    });

    // Handle form submissions via AJAX
    document.querySelectorAll('.dynamic-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
            submitBtn.disabled = true;

            // Remove any existing alerts
            const existingAlert = form.querySelector('.form-alert');
            if (existingAlert) existingAlert.remove();

            // Collect form data
            const formData = new FormData(form);
            
            // Add form type based on which form is active
            if (form.closest('#general-inquiry')) {
                formData.append('form_type', 'general');
            } else if (form.closest('#quotation-request')) {
                formData.append('form_type', 'quotation');
            } else if (form.closest('#partnership-inquiry')) {
                formData.append('form_type', 'partnership');
            } else if (form.closest('#customer-support')) {
                formData.append('form_type', 'support');
            }

            try {
                const response = await fetch('contact-process.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                // Create alert div
                const alertDiv = document.createElement('div');
                alertDiv.className = `form-alert alert alert-${result.success ? 'success' : 'danger'} mt-3`;
                alertDiv.innerHTML = result.message;
                
                form.appendChild(alertDiv);

                if (result.success) {
                    form.reset();
                    if (result.redirect) {
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 2000);
                    }
                }

                // Scroll to alert
                alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });

            } catch (error) {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'form-alert alert alert-danger mt-3';
                alertDiv.innerHTML = 'Network error. Please try again.';
                form.appendChild(alertDiv);
            } finally {
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    });
});