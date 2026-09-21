(() => {
  // Auth pages use the shared reveal animation. Make them visible even if IntersectionObserver/app.js is unavailable.
  document.querySelectorAll('.reveal').forEach(item => item.classList.add('show'));
  const form = document.querySelector('[data-auth-form]');
  const message = document.getElementById('formMessage');
  const requiredNotice = document.getElementById('requiredNotice');

  if (requiredNotice && new URLSearchParams(location.search).get('required') === '1') {
    requiredNotice.classList.remove('d-none');
  }

  document.querySelectorAll('[data-toggle-password]').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.togglePassword);
      if (!input) return;
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
      button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    });
  });

  const password = document.getElementById('password');
  const strengthBar = document.getElementById('strengthBar');
  const strengthLabel = document.getElementById('strengthLabel');
  if (password && strengthBar && strengthLabel) {
    password.addEventListener('input', () => {
      const value = password.value;
      let score = 0;
      if (value.length >= 6) score++;
      if (/[A-Z]/.test(value) && /[a-z]/.test(value)) score++;
      if (/\d/.test(value)) score++;
      if (/[^A-Za-z0-9]/.test(value)) score++;
      const widths = ['0%', '25%', '50%', '75%', '100%'];
      const labels = ['Password strength', 'Weak', 'Fair', 'Good', 'Strong'];
      const colors = ['#ff8798', '#ff8798', '#f3c96c', '#7f96ff', '#6fe4ad'];
      strengthBar.style.width = widths[score];
      strengthBar.style.background = colors[score];
      strengthLabel.textContent = labels[score];
    });
  }

  if (!form || !message) return;
  const submitBtn = form.querySelector('button[type="submit"]');

  form.addEventListener('submit', async event => {
    event.preventDefault();
    message.textContent = '';
    message.className = 'form-message mt-3';

    const confirm = document.getElementById('confirm_password');
    if (confirm && password && confirm.value !== password.value) {
      message.textContent = 'Passwords do not match.';
      message.classList.add('text-danger');
      return;
    }

    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'Please wait...';

    try {
      const response = await fetch(form.action, { method: 'POST', body: new FormData(form), credentials: 'same-origin' });
      const data = await response.json();
      message.textContent = data.message || 'Unable to complete request.';
      message.classList.add(data.success ? 'text-success' : 'text-danger');
      if (data.success) window.location.href = data.redirect || '../dashboard.php';
    } catch (error) {
      message.textContent = 'Server connection failed. Make sure WAMP and MySQL are running.';
      message.classList.add('text-danger');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = form.dataset.submitLabel || 'Submit';
    }
  });
})();
