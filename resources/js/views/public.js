/**
 * Public pages (welcome, about, contact, welcome-after) shared behaviour.
 * - Hamburger toggle
 * - IntersectionObserver scroll reveal (data-animate → is-visible)
 * - FAQ accordion (data-faq-toggle)
 * - Contact form simulated submit (data-contact-form)
 */

const onReady = (fn) => {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
};

onReady(() => {
    // ─── Hamburger ──────────────────────────────────────────────────────
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('is-open');
            hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        mobileMenu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('is-open');
                hamburger.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // ─── Scroll reveal ──────────────────────────────────────────────────
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -50px 0px' }
        );
        document.querySelectorAll('[data-animate]').forEach((el) => observer.observe(el));
    } else {
        document.querySelectorAll('[data-animate]').forEach((el) => el.classList.add('is-visible'));
    }

    // ─── FAQ accordion ──────────────────────────────────────────────────
    document.querySelectorAll('[data-faq-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq-item');
            if (!item) return;
            const wasOpen = item.classList.contains('is-open');
            document
                .querySelectorAll('.faq-item.is-open')
                .forEach((i) => i !== item && i.classList.remove('is-open'));
            item.classList.toggle('is-open', !wasOpen);
        });
    });

    // ─── Contact form (simulated submit) ────────────────────────────────
    const form = document.querySelector('[data-contact-form]');
    if (form) {
        const successEl = document.querySelector('[data-contact-success]');
        const submitBtn = form.querySelector('[data-contact-submit]');
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Reset previous errors
            form.querySelectorAll('[data-error-for]').forEach((node) => node.remove());
            form.querySelectorAll('.form-input--error, .form-textarea--error, .form-select--error').forEach((node) =>
                node.classList.remove('form-input--error', 'form-textarea--error', 'form-select--error')
            );

            let valid = true;
            form.querySelectorAll('[required]').forEach((field) => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('form-input--error', 'form-textarea--error', 'form-select--error');
                }
            });
            if (!valid) return;

            // Loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalHtml = submitBtn.innerHTML;
                submitBtn.innerHTML =
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="animate-spin"><circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,.3)" stroke-width="2"/><path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="2" stroke-linecap="round"/></svg> Mengirim...';
            }

            // Send POST to backend
            fetch('/kontak', {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (form) form.style.display = 'none';
                    if (successEl) successEl.style.display = 'flex';
                } else {
                    alert('Gagal mengirim pesan: ' + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan jaringan.');
            })
            .finally(() => {
                if (submitBtn && submitBtn.dataset.originalHtml) {
                    submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                    submitBtn.disabled = false;
                }
            });
        });

        // "Send another" button
        const resetBtn = document.querySelector('[data-contact-reset]');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                if (successEl) successEl.style.display = 'none';
                form.reset();
                form.style.display = '';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.dataset.originalHtml || submitBtn.innerHTML;
                }
            });
        }
    }

    // ─── Navbar Active Link Management ───
    const publicNavLinks = document.querySelectorAll('.public-nav__link');
    const path = window.location.pathname;
    const isHomePage = path === '/' || path === '/index.php';

    function setNavbarActive(key) {
        publicNavLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (!href) return;
            const isFitur = href.includes('#fitur');
            const isHome = href === '/' || href.endsWith('/#') || (href.endsWith('/') && !href.includes('#'));
            
            link.classList.remove('public-nav__link--active');
            if (key === 'fitur' && isFitur) {
                link.classList.add('public-nav__link--active');
            } else if (key === 'home' && isHome && !isFitur) {
                link.classList.add('public-nav__link--active');
            }
        });
    }

    if (isHomePage) {
        const fiturSection = document.getElementById('fitur');
        
        // Initial check for hash
        if (window.location.hash === '#fitur') {
            setNavbarActive('fitur');
        }

        if (fiturSection && 'IntersectionObserver' in window) {
            const observerOptions = {
                root: null,
                rootMargin: '-30% 0px -50% 0px', // Trigger when Fitur is somewhat in view
                threshold: 0
            };

            const activeObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        setNavbarActive('fitur');
                    } else {
                        const rect = entry.boundingClientRect;
                        // If the section is below the viewport, we are at the top (home)
                        if (rect.top > 0) {
                            setNavbarActive('home');
                        }
                    }
                });
            }, observerOptions);

            activeObserver.observe(fiturSection);
        }
    }
});
