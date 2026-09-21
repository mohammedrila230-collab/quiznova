(() => {
  const form = document.getElementById('contactForm');
  const messageBox = document.getElementById('contactMessage');
  if (!form || !messageBox) return;

  form.addEventListener('submit', async event => {
    event.preventDefault();
    messageBox.textContent = '';
    messageBox.className = '';

    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      return;
    }

    const button = form.querySelector('button[type="submit"]');
    const original = button.innerHTML;
    button.disabled = true;
    button.textContent = 'Sending...';

    try {
      const response = await fetch('contact.php', { method: 'POST', body: new FormData(form), credentials: 'same-origin' });
      const data = await response.json();
      messageBox.textContent = data.message || 'Unable to send message.';
      messageBox.className = data.success ? 'alert alert-success mt-2' : 'alert alert-danger mt-2';
      if (data.success) {
        form.reset();
        form.classList.remove('was-validated');
      }
    } catch (error) {
      messageBox.textContent = 'Server connection failed. Make sure WAMP and MySQL are running.';
      messageBox.className = 'alert alert-danger mt-2';
    } finally {
      button.disabled = false;
      button.innerHTML = original;
    }
  });
})();
