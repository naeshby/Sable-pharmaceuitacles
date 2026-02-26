// Mobile Menu Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
}

// Close mobile menu when a link is clicked
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
    });
});

// Dynamic Contact Forms
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
        // Hide all forms
        Object.values(formSets).forEach(set => {
            if (set) set.classList.remove('active');
        });
        // Show selected
        if (formSets[formId]) formSets[formId].classList.add('active');
    }

    // Initial show
    showForm(inquirySelect.value);

    // On change
    inquirySelect.addEventListener('change', function(e) {
        showForm(e.target.value);
    });

    // Handle form submissions (demo only)
    const forms = document.querySelectorAll('.dynamic-form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for contacting Sable Medical. A representative will respond within 24 hours.');
            this.reset(); // optional
        });
    });
});

// Dynamic Contact Forms (unchanged from original)
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

    // Demo form submission
    document.querySelectorAll('.dynamic-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Thank you for contacting Sable Medical. A representative will respond within 24 hours.');
            this.reset();
        });
    });
});