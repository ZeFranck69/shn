const initHeader = () => {
  const header = document.querySelector('[data-tf-site-header]');

  if (!header) {
    return;
  }

  const syncHeight = () => {
    document.documentElement.style.setProperty('--tf-site-header-height', `${header.offsetHeight}px`);
  };

  const sync = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 24);
  };

  syncHeight();
  sync();
  window.addEventListener('resize', syncHeight);
  window.addEventListener('scroll', sync, { passive: true });
};

const initMobileNavigation = () => {
  const toggle = document.querySelector('[data-tf-menu-toggle]');
  const panel = document.querySelector('[data-tf-menu-panel]');

  if (!toggle || !panel) {
    return;
  }

  const setOpen = (isOpen) => {
    const isCurrentlyOpen = document.body.classList.contains('tf-menu-is-open');

    if (isOpen && !isCurrentlyOpen) {
      const scrollY = window.scrollY;
      document.body.dataset.tfScrollLock = String(scrollY);
      document.body.style.top = `-${scrollY}px`;
      document.body.classList.add('tf-menu-is-open');
    }

    if (!isOpen && isCurrentlyOpen) {
      const scrollY = Number.parseInt(document.body.dataset.tfScrollLock || '0', 10);
      const previousScrollBehavior = document.documentElement.style.scrollBehavior;

      delete document.body.dataset.tfScrollLock;
      document.documentElement.style.scrollBehavior = 'auto';
      document.body.classList.remove('tf-menu-is-open');
      document.body.style.top = '';
      window.scrollTo(0, scrollY);

      requestAnimationFrame(() => {
        document.documentElement.style.scrollBehavior = previousScrollBehavior;
      });
    }

    toggle.setAttribute('aria-expanded', String(isOpen));
    toggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
  };

  toggle.addEventListener('click', () => {
    setOpen(!document.body.classList.contains('tf-menu-is-open'));
  });

  panel.querySelectorAll('a[href^="#"]').forEach((link) => {
    link.addEventListener('click', () => setOpen(false));
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      setOpen(false);
    }
  });
};

const initReveal = () => {
  const elements = document.querySelectorAll('.reveal');

  if (!elements.length) {
    return;
  }

  if (!('IntersectionObserver' in window)) {
    elements.forEach((element) => element.classList.add('in'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in');
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.16 },
  );

  elements.forEach((element) => observer.observe(element));
};

document.addEventListener('DOMContentLoaded', () => {
  initHeader();
  initMobileNavigation();
  initReveal();
});
