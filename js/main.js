(function () {
  var header = document.getElementById('siteHeader');
  var toggle = document.getElementById('navToggle');

  // If a stock image fails to load, hide it so the tinted frame shows
  // instead of a broken image icon.
  document.querySelectorAll('img').forEach(function (img) {
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

    // One-page navigation: close the menu once a section link is tapped.
    document.querySelectorAll('.nav-mobile a').forEach(function (link) {
      link.addEventListener('click', function () {
        header.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
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

  // Valuation form (contact page). Submits to Netlify Forms without a
  // page reload; falls back to an error message if the request fails.
  var form = document.getElementById('valuationForm');
  var success = document.getElementById('formSuccess');
  var failure = document.getElementById('formError');

  if (form && success && failure) {
    form.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!form.reportValidity()) {
        return;
      }

      var submit = form.querySelector('[type="submit"]');
      submit.disabled = true;
      failure.classList.remove('is-visible');

      fetch('/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(new FormData(form)).toString()
      })
        .then(function (response) {
          if (!response.ok) {
            throw new Error('Form submission failed: ' + response.status);
          }
          success.classList.add('is-visible');
          success.scrollIntoView({ block: 'nearest' });
        })
        .catch(function () {
          submit.disabled = false;
          failure.classList.add('is-visible');
          failure.scrollIntoView({ block: 'nearest' });
        });
    });
  }
})();
