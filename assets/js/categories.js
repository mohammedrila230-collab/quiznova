(() => {
  const search = document.getElementById('categorySearch');
  const cards = [...document.querySelectorAll('[data-category-card]')];
  const filters = [...document.querySelectorAll('[data-filter]')];
  const empty = document.getElementById('categoryEmpty');
  if (!search || !cards.length) return;

  let activeGroup = 'all';

  function applyFilters() {
    const term = search.value.trim().toLowerCase();
    let visible = 0;
    cards.forEach(card => {
      const matchesText = card.dataset.name.includes(term);
      const matchesGroup = activeGroup === 'all' || card.dataset.group === activeGroup;
      const show = matchesText && matchesGroup;
      card.classList.toggle('d-none', !show);
      if (show) visible += 1;
    });
    empty?.classList.toggle('d-none', visible !== 0);
  }

  search.addEventListener('input', applyFilters);
  filters.forEach(button => {
    button.addEventListener('click', () => {
      activeGroup = button.dataset.filter;
      filters.forEach(item => item.classList.toggle('active', item === button));
      applyFilters();
    });
  });
})();
