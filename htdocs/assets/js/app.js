document.addEventListener('DOMContentLoaded', () => {
  const search = document.getElementById('searchProducts');
  const filter = document.getElementById('categoryFilter');
  const empty = document.getElementById('noResults');
  const cards = [...document.querySelectorAll('.product-card[data-nombre]')];
  function applyFilters() {
    const term = (search?.value || '').toLowerCase().trim();
    const categoria = filter?.value || '';
    let visible = 0;
    cards.forEach(card => {
      const show = card.dataset.nombre.includes(term) && (!categoria || card.dataset.categoria === categoria);
      card.hidden = !show;
      if (show) visible++;
    });
    if (empty) empty.hidden = visible > 0 || cards.length === 0;
  }
  search?.addEventListener('input', applyFilters);
  filter?.addEventListener('change', applyFilters);
  applyFilters();
});
