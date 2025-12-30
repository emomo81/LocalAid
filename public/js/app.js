// Global Loading State
document.addEventListener('DOMContentLoaded', () => {
    // Add loading spinner to buttons on form submit
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.classList.add('cursor-not-allowed', 'opacity-75');
                btn.innerHTML = `<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Processing...`;

                // Safety timeout to reset button if request hangs or is handled by AJAX without reload
                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('cursor-not-allowed', 'opacity-75');
                    btn.innerHTML = originalText;
                }, 10000);
            }
        });
    });
});
