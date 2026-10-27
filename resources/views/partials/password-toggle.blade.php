<script>
    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        const input = document.getElementById(button.dataset.togglePassword);

        button.addEventListener('click', function () {
            const reveal = input.type === 'password';
            input.type = reveal ? 'text' : 'password';
            button.setAttribute('aria-pressed', String(reveal));
            button.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
        });
    });
</script>
