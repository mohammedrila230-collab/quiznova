(() => {
  const revealItems = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealItems.forEach(item => observer.observe(item));
  } else {
    revealItems.forEach(item => item.classList.add('show'));
  }

  const authAreas = document.querySelectorAll('[data-auth-area]');
  if (!authAreas.length) return;

  fetch('api/session.php', { credentials: 'same-origin' })
    .then(response => response.ok ? response.json() : Promise.reject())
    .then(data => {
      if (!data.loggedIn) return;
      authAreas.forEach(area => {
        area.innerHTML = `<a class="btn btn-text" href="dashboard.php">${escapeHtml(data.username || 'Dashboard')}</a><a class="btn btn-accent" href="auth/logout.php">Sign out <i class="bi bi-box-arrow-right"></i></a>`;
      });
    })
    .catch(() => {});

  function escapeHtml(value) {
    return String(value).replace(/[&<>\'\"]/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[ch]));
  }
})();
