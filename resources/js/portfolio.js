/** Sadeq portfolio — small, dependency-free client interactions. */
(() => {
  'use strict';
  const root = document.documentElement;
  const themeButton = document.querySelector('#theme-toggle');
  const mobileButton = document.querySelector('#menu-toggle');
  const mobileNav = document.querySelector('#mobile-nav');
  const toast = document.querySelector('#toast');
  let toastTimer;
  const showToast = (message) => {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('visible');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('visible'), 3800);
  };

  // Dark is the approved reference. Light is an optional, saved alternative.
  try {
    if (localStorage.getItem('sadeq-portfolio-theme') === 'light') root.dataset.theme = 'light';
  } catch { /* Local storage might be restricted */ }
  themeButton?.addEventListener('click', () => {
    root.dataset.theme = root.dataset.theme === 'light' ? 'dark' : 'light';
    try { localStorage.setItem('sadeq-portfolio-theme', root.dataset.theme); } catch { /* Ignore */ }
  });

  const closeMobileMenu = () => {
    if (!mobileNav || !mobileButton) return;
    mobileNav.hidden = true;
    mobileButton.setAttribute('aria-expanded', 'false');
    mobileButton.setAttribute('aria-label', mobileButton.dataset.openLabel || 'Open navigation');
  };
  mobileButton?.addEventListener('click', () => {
    if (!mobileNav) return;
    mobileNav.hidden = !mobileNav.hidden;
    const expanded = !mobileNav.hidden;
    mobileButton.setAttribute('aria-expanded', String(expanded));
    mobileButton.setAttribute('aria-label', expanded ? (mobileButton.dataset.closeLabel || 'Close navigation') : (mobileButton.dataset.openLabel || 'Open navigation'));
  });
  mobileNav?.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMobileMenu));
  window.addEventListener('resize', () => { if (window.innerWidth > 740) closeMobileMenu(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobileMenu(); });

  // Keep desktop navigation highlighting in sync with scrolling.
  const navLinks = [...document.querySelectorAll('.desktop-nav a[href^="#"]')];
  const sections = [...document.querySelectorAll('main section[id]')];
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(entries => {
      entries.filter(entry => entry.isIntersecting).forEach(entry => {
        navLinks.forEach(a => a.classList.toggle('active', a.hash === `#${entry.target.id}`));
      });
    }, { rootMargin: '-23% 0px -63% 0px' });
    sections.forEach(section => observer.observe(section));
  }

  // Optional modal: project copy is read from localized HTML data attributes
  // rather than embedding English-only descriptions in JavaScript.
  const modal = document.querySelector('#project-modal');
  document.querySelectorAll('[data-project]').forEach(button => {
    button.addEventListener('click', () => {
      if (!modal) return;
      const title = modal.querySelector('#modal-title');
      const description = modal.querySelector('#modal-description');
      const projectTitle = button.dataset.projectTitle || button.textContent.trim();
      const projectDescription = button.dataset.projectDescription || '';
      if (title && projectTitle) title.textContent = projectTitle;
      if (description) description.textContent = projectDescription;
      if (typeof modal.showModal === 'function') modal.showModal();
      else showToast(projectDescription);
    });
  });
  modal?.querySelectorAll('.modal-close,.modal-dismiss').forEach(button => button.addEventListener('click', () => modal.close()));
  modal?.addEventListener('click', e => { if (e.target === modal) modal.close(); });

  // No address was provided; do not attempt to send email to a fictional recipient.
  document.querySelector('[data-placeholder-email]')?.addEventListener('click', event => {
    event.preventDefault();
    showToast(event.currentTarget.dataset.placeholderMessage || 'Add a contact email before publishing.');
  });
  const year = document.querySelector('#year');
  if (year) year.textContent = String(new Date().getFullYear());
})();
