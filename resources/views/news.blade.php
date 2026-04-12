<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Aktuality - Stop Kamenolomu</title>
  <meta name="description" content="Nejnovější zprávy a aktuality z boje proti kamenolomu u Bukova.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="/css/custom.css">
  <script src="/js/app.js"></script>
</head>
<body x-data x-init="$store.nav.init()">

  <!-- ===== Navigation ===== -->
  <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 nav-scrolled py-3">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
      <a href="/" class="text-white font-bold text-xl tracking-tight" style="font-family: 'Newsreader', serif;">
        STOP KAMENOLOMU
      </a>
      <div class="hidden md:flex items-center gap-8">
        <a href="/" class="nav-link text-sm font-medium">Domů</a>
        <a href="/about" class="nav-link text-sm font-medium">O projektu</a>
        <a href="/news" class="nav-link text-sm font-medium">Aktuality</a>
        <a href="/petition" class="nav-link text-sm font-medium">Petice</a>
        <a href="/contact" class="nav-link text-sm font-medium">Kontakt</a>
        <a href="/petition" class="btn-white text-sm !py-2 !px-4">Podepsat petici</a>
      </div>
      <button class="md:hidden text-white" @click="$store.nav.mobileOpen = !$store.nav.mobileOpen">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path x-show="!$store.nav.mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          <path x-show="$store.nav.mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div x-show="$store.nav.mobileOpen" x-transition class="md:hidden bg-[#2D5F2D] px-6 py-4">
      <a href="/" class="block py-2 text-white">Domů</a>
      <a href="/about" class="block py-2 text-white">O projektu</a>
      <a href="/news" class="block py-2 text-white">Aktuality</a>
      <a href="/petition" class="block py-2 text-white">Petice</a>
      <a href="/contact" class="block py-2 text-white">Kontakt</a>
    </div>
  </nav>

  <!-- ===== Page Header ===== -->
  <section class="bg-primary pt-32 pb-16 px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Aktuality</h1>
      <p class="text-lg text-white/80">
        Sledujte vývoj situace kolem plánovaného kamenolomu
      </p>
    </div>
  </section>

  <!-- ===== News Grid ===== -->
  <section class="py-20 px-6" x-data="newsLoader">
    <div class="max-w-7xl mx-auto">

      <!-- Loading state -->
      <div x-show="loading" class="text-center py-10">
        <div class="inline-block w-8 h-8 border-4 border-[#2D5F2D]/20 border-t-[#2D5F2D] rounded-full animate-spin"></div>
        <p class="mt-3 text-[#5A5A5A]">Načítání článků...</p>
      </div>

      <!-- Articles grid -->
      <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <template x-for="article in visible" :key="article.id">
          <article class="news-card fade-in visible">
            <img :src="article.image" :alt="article.title" class="news-card-image">
            <div class="p-6">
              <time class="text-sm text-[#8B7355] font-medium" x-text="formatDate(article.date)"></time>
              <h2 class="text-xl font-bold mt-2 mb-3 text-[#2C2C2C] leading-snug" x-text="article.title"></h2>
              <p class="text-sm text-[#5A5A5A] leading-relaxed mb-4" x-text="article.excerpt"></p>
              <div class="flex gap-3">
                <template x-if="article.link">
                  <a :href="article.link" target="_blank" rel="noopener" class="btn-primary text-sm !py-2 !px-4">
                    Číst více →
                  </a>
                </template>
                <template x-if="article.content && !article.link">
                  <button
                    class="btn-outline text-sm !py-2 !px-4"
                    @click="article._expanded = !article._expanded">
                    <span x-text="article._expanded ? 'Méně' : 'Více'"></span>
                  </button>
                </template>
              </div>
              <template x-if="article._expanded && article.content">
                <div class="mt-4 pt-4 border-t border-[#E8E0D8] text-sm text-[#5A5A5A] leading-relaxed" x-text="article.content"></div>
              </template>
            </div>
          </article>
        </template>
      </div>

      <!-- Load more -->
      <div x-show="hasMore && !loading" class="text-center mt-12">
        <button @click="showMore()" class="btn-outline">Načíst další</button>
      </div>

      <!-- Empty state -->
      <div x-show="!loading && articles.length === 0" class="text-center py-16">
        <p class="text-lg text-[#5A5A5A]">Zatím nejsou žádné aktuality.</p>
      </div>
    </div>
  </section>

  <!-- ===== CTA ===== -->
  <section class="bg-primary py-16 px-6">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-3xl font-bold text-white mb-4">Chcete být informováni?</h2>
      <p class="text-lg text-white/80 mb-8">Sledujte nás na sociálních sítích a buďte stále v obraze.</p>
      <a href="/contact" class="btn-white">Kontaktujte nás</a>
    </div>
  </section>

  <!-- ===== Footer ===== -->
  <footer class="bg-[#1E421E] text-white py-16 px-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10">
      <div>
        <h3 class="text-lg font-bold mb-4" style="font-family: 'Newsreader', serif;">STOP KAMENOLOMU</h3>
        <p class="text-white/70 text-sm leading-relaxed">Chráníme přírodu a podporujeme udržitelnost v okolí Bukova u Hořoviček.</p>
      </div>
      <div>
        <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-white/50">Navigace</h4>
        <ul class="space-y-2 text-sm">
          <li><a href="/" class="text-white/70 hover:text-white transition-colors">Domů</a></li>
          <li><a href="/about" class="text-white/70 hover:text-white transition-colors">O projektu</a></li>
          <li><a href="/news" class="text-white/70 hover:text-white transition-colors">Aktuality</a></li>
          <li><a href="/petition" class="text-white/70 hover:text-white transition-colors">Petice</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-white/50">Kontakt</h4>
        <ul class="space-y-2 text-sm text-white/70">
          <li>Bedlno 37</li>
          <li>Restaurace Clear Point, Hokov</li>
        </ul>
      </div>
      <div>
        <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-white/50">Sociální sítě</h4>
        <div class="flex gap-4">
          <a href="#" class="text-white/70 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
        </div>
      </div>
    </div>
    <div class="max-w-7xl mx-auto mt-10 pt-8 border-t border-white/10 text-center text-sm text-white/40">
      &copy; 2025 Stop Kamenolomu - Bukov u Hořoviček. Všechna práva vyhrazena.
    </div>
  </footer>

</body>
</html>
