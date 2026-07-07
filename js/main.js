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
})();
