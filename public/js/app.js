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
    fallbackImage: '/images/news-placeholder.svg',

    async init() {
      try {
        const res = await fetch('/data/news.json');
        const data = await res.json();
        this.articles = data
          .map((article) => ({
            ...article,
            image: this.resolveImagePath(article.image),
            _expanded: false
          }))
          .sort((a, b) => new Date(b.date) - new Date(a.date));
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

    resolveImagePath(imagePath) {
      if (typeof imagePath !== 'string' || imagePath.trim() === '') {
        return this.fallbackImage;
      }

      if (imagePath.startsWith('http://') || imagePath.startsWith('https://') || imagePath.startsWith('/')) {
        return imagePath;
      }

      return `/${imagePath.replace(/^\.?\//, '')}`;
    },

    handleImageError(event) {
      if (!event?.target) {
        return;
      }

      if (event.target.getAttribute('src') !== this.fallbackImage) {
        event.target.setAttribute('src', this.fallbackImage);
      }
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
    errorMessage: '',

    submit() {
      if (!this.name || !this.email || !this.message) {
        this.error = true;
        this.submitted = false;
        this.errorMessage = 'Prosím vyplňte všechna povinná pole.';
        return;
      }

      const subject = encodeURIComponent('Zpráva z kontaktniho formulare Stop Kamenolomu');
      const body = encodeURIComponent(
        `Jmeno: ${this.name}\nE-mail: ${this.email}\n\nZprava:\n${this.message}`
      );
      const mailtoUrl = `mailto:info@stopkamenolomu.cz?subject=${subject}&body=${body}`;
      window.location.href = mailtoUrl;

      this.error = false;
      this.errorMessage = '';
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
