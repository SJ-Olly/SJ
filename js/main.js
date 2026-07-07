(function () {
  var toggle = document.getElementById('navToggle');
  var mobileNav = document.getElementById('navMobile');

  if (toggle && mobileNav) {
    toggle.addEventListener('click', function () {
      var open = mobileNav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var parallaxEl = document.querySelector('[data-parallax]');

  if (parallaxEl && !reduceMotion) {
    var ticking = false;

    function onScroll() {
      if (ticking) return;
      ticking = true;

      requestAnimationFrame(function () {
        var rect = parallaxEl.getBoundingClientRect();
        var progress = 1 - Math.min(Math.max(rect.top / window.innerHeight, 0), 1);
        var shift = progress * 16;
        parallaxEl.style.transform = 'translateY(' + (shift - 8) + 'px)';
        ticking = false;
      });
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
})();
