const initHeader = () => {
  const header = document.querySelector('[data-tf-site-header]');

  if (!header) {
    return;
  }

  const sync = () => {
    header.classList.toggle('is-scrolled', window.scrollY > 24);
  };

  sync();
  window.addEventListener('scroll', sync, { passive: true });
};

const initMobileNavigation = () => {
  const toggle = document.querySelector('[data-tf-menu-toggle]');
  const panel = document.querySelector('[data-tf-menu-panel]');

  if (!toggle || !panel) {
    return;
  }

  const setOpen = (isOpen) => {
    document.body.classList.toggle('tf-menu-is-open', isOpen);
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
