/* ===== Stop Kamenolomu - App JS ===== */

document.addEventListener('alpine:init', () => {

  /* --- Shared navigation state --- */
  Alpine.store('nav', {
    scrolled: false,
    mobileOpen: false,
    init() {
      window.addEventListener('scroll', () => {
        this.scrolled = window.scrollY > 60;
      });
    }
  });

  /* --- News loader component --- */
  Alpine.data('newsLoader', () => ({
    articles: [],
    displayCount: 3,
    loading: true,

    async init() {
      try {
        const res = await fetch('/data/news.json');
        const data = await res.json();
        this.articles = data.sort((a, b) => new Date(b.date) - new Date(a.date));
      } catch (e) {
        console.error('Failed to load news:', e);
        this.articles = [];
      } finally {
        this.loading = false;
      }
    },

    get visible() {
      return this.articles.slice(0, this.displayCount);
    },

    get hasMore() {
      return this.displayCount < this.articles.length;
    },

    showMore() {
      this.displayCount += 3;
    },

    formatDate(dateStr) {
      const d = new Date(dateStr);
      return d.toLocaleDateString('cs-CZ', { day: 'numeric', month: 'numeric', year: 'numeric' });
    }
  }));

  /* --- Contact form component --- */
  Alpine.data('contactForm', () => ({
    name: '',
    email: '',
    message: '',
    submitted: false,
    error: false,

    submit() {
      if (!this.name || !this.email || !this.message) {
        this.error = true;
        return;
      }
      this.error = false;
      // In production, send to a backend endpoint
      console.log('Form submitted:', { name: this.name, email: this.email, message: this.message });
      this.submitted = true;
      this.name = '';
      this.email = '';
      this.message = '';
    }
  }));
});

/* ===== Scroll-based fade-in animations ===== */
document.addEventListener('DOMContentLoaded', () => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
});
