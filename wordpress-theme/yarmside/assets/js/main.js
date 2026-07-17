(function () {
  var header = document.getElementById('siteHeader');
  var toggle = document.getElementById('navToggle');

  // If a photo fails to load, hide it so the tinted frame shows
  // instead of a broken image icon.
  document.querySelectorAll('.frame img, .hero__media img').forEach(function (img) {
    img.addEventListener('error', function () {
      img.style.visibility = 'hidden';
    });
    if (img.complete && img.naturalWidth === 0 && img.src) {
      img.style.visibility = 'hidden';
    }
  });

  if (toggle && header) {
    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Property filters (archive page). Filters the cards already on the
  // page by property type term or availability status.
  var grid = document.getElementById('propertyGrid');
  var filterButtons = document.querySelectorAll('.filter-btn');

  if (grid && filterButtons.length) {
    filterButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var filter = btn.getAttribute('data-filter');

        filterButtons.forEach(function (other) {
          other.setAttribute('aria-pressed', other === btn ? 'true' : 'false');
        });

        grid.classList.toggle('is-filtered', filter !== 'all');

        grid.querySelectorAll('.property').forEach(function (card) {
          var show =
            filter === 'all' ||
            card.getAttribute('data-type') === filter ||
            (filter === 'available' && card.getAttribute('data-status') === 'available');
          card.classList.toggle('is-hidden', !show);
        });
      });
    });
  }
})();
