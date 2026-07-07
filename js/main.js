(function () {
  var header = document.getElementById('siteHeader');
  var toggle = document.getElementById('navToggle');

  if (toggle && header) {
    toggle.addEventListener('click', function () {
      var open = header.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Header goes solid once the hero is mostly scrolled past.
  // On pages without a hero the header is solid from the start.
  if (header) {
    var hero = document.querySelector('.hero');

    if (!hero) {
      header.classList.add('is-solid');
    } else if ('IntersectionObserver' in window) {
      var observer = new IntersectionObserver(
        function (entries) {
          header.classList.toggle('is-solid', !entries[0].isIntersecting);
        },
        { rootMargin: '-72px 0px 0px 0px' }
      );
      observer.observe(hero);
    } else {
      header.classList.add('is-solid');
    }
  }

  // Property filters (properties page).
  var grid = document.getElementById('propertyGrid');
  var filterButtons = document.querySelectorAll('.filter-btn');

  if (grid && filterButtons.length) {
    filterButtons.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var filter = btn.getAttribute('data-filter');

        filterButtons.forEach(function (other) {
          other.setAttribute('aria-pressed', other === btn ? 'true' : 'false');
        });

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

  // Valuation form (contact page). Client-side confirmation only:
  // wire to a real endpoint before launch.
  var form = document.getElementById('valuationForm');
  var success = document.getElementById('formSuccess');

  if (form && success) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!form.reportValidity()) {
        return;
      }

      success.classList.add('is-visible');
      form.querySelector('[type="submit"]').disabled = true;
      success.scrollIntoView({ block: 'nearest' });
    });
  }
})();
