<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit | myITS Fitness</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
    .glass-card { background: #fff; }
    .menu-enter { opacity: 0; transform: translateY(-6px) scale(.98); }
    .menu-enter-active { opacity: 1; transform: translateY(0) scale(1); transition: opacity .18s ease, transform .18s ease; }
    .menu-exit { opacity: 1; transform: translateY(0) scale(1); }
    .menu-exit-active { opacity: 0; transform: translateY(-6px) scale(.98); transition: opacity .14s ease, transform .14s ease; }
  </style>
</head>
<body class="bg-[#f3f6fb] text-slate-800">

  {{-- Topbar (with Logout Overlay) --}}
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
        </a>
      </div>

      <div id="topbarUser" class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User" class="w-9 h-9 rounded-full object-cover cursor-pointer select-none">
        {{-- Logout overlay --}}
        <div id="logoutOverlay" class="hidden absolute right-0 top-[2.8rem] z-50">
          <a href="{{ route('student.login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout" class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="relative">
    {{-- Subtle background ornaments (optional) --}}
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none" style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid gap-8 md:grid-cols-[260px,1fr]">

        {{-- Sidebar --}}
        <aside>
          <nav class="space-y-3">
            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50 w-full">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Home</span>
            </a>
            <a class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100 w-full">
              <img src="{{ asset('images/submit-icon.png') }}" class="w-6 h-6" alt="">
              <span class="font-semibold text-slate-900">Submit</span>
            </a>
            <a href="{{ route('student.status') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50 w-full">
              <img src="{{ asset('images/status-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Status</span>
            </a>
          </nav>
        </aside>

        {{-- Content --}}
        <section>
          <h1 class="text-4xl font-extrabold tracking-tight mb-4">Submit</h1>

          <!-- Diperpanjang: tambah min-h dan padding bawah -->
          <div class="glass-card rounded-2xl border shadow-sm p-6 md:p-8 min-h-[600px] pb-16">
            <h2 class="text-center text-2xl md:text-[28px] font-bold text-slate-900">Select your activity</h2>

            {{-- Dropdown --}}
            <div class="mt-6 mx-auto max-w-2xl">
              <div class="relative">
                <button id="selectBtn"
                        type="button"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-left shadow-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition flex items-center justify-between">
                  <span id="selectLabel" class="text-slate-500">Select...</span>
                  <svg id="chev" class="h-4 w-4 text-slate-500 transition-transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.168l3.71-2.94a.75.75 0 01.94 1.17l-4.2 3.33a.75.75 0 01-.94 0l-4.2-3.33a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                  </svg>
                </button>

                {{-- Animated menu --}}
                <div id="menu"
                     class="hidden absolute left-0 right-0 mt-2 z-40 rounded-2xl border border-slate-200 bg-white shadow-xl ring-1 ring-black/5 backdrop-blur-sm">
                  <ul class="py-2">
                    @php
                      $acts = [
                        ['val'=>'Gym',        'icon'=>'gym-icon.png'],
                        ['val'=>'Running',    'icon'=>'Running-icon.png'],
                        ['val'=>'Soccer',     'icon'=>'soccer-icon.png'],
                        ['val'=>'Cycling',    'icon'=>'cycling-icon.png'],
                        ['val'=>'Basketball', 'icon'=>'basketball-icon.png'],
                        ['val'=>'Other',      'icon'=>'other-icon.png'],
                      ];
                    @endphp
                    @foreach($acts as $a)
                      <li>
                        <button data-value="{{ $a['val'] }}"
                                class="w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50 transition">
                          <img src="{{ asset('images/'.$a['icon']) }}" alt="{{ $a['val'] }} icon" class="w-5 h-5">
                          <span class="text-slate-800">{{ $a['val'] }}</span>
                        </button>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            {{-- Illustration --}}
            <div class="mt-10">
              <img src="{{ asset('images/submit-illustration.png') }}" alt="Submit illustration" class="mx-auto max-w-[640px] w-full h-auto select-none">
            </div>
          </div>
        </section>

      </div>
    </div>
  </main>

  {{-- Dropdown & Logout overlay scripts --}}
  <script>
    // Logout overlay
    (function () {
      const avatar  = document.getElementById('userAvatar');
      const overlay = document.getElementById('logoutOverlay');
      const wrap    = document.getElementById('topbarUser');
      if (!avatar || !overlay || !wrap) return;
      avatar.addEventListener('click', (e) => {
        e.stopPropagation();
        overlay.classList.toggle('hidden');
      });
      document.addEventListener('click', (e) => {
        if (overlay.classList.contains('hidden')) return;
        if (!wrap.contains(e.target)) overlay.classList.add('hidden');
      });
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') overlay.classList.add('hidden');
      });
    })();

    // Pretty dropdown with tiny enter/exit animation
    (function () {
      const btn = document.getElementById('selectBtn');
      const menu = document.getElementById('menu');
      const label = document.getElementById('selectLabel');
      const chev = document.getElementById('chev');

      function openMenu() {
        if (!menu.classList.contains('hidden')) return;
        menu.classList.remove('hidden');
        menu.classList.add('menu-enter');
        requestAnimationFrame(() => {
          menu.classList.add('menu-enter-active');
          chev.style.transform = 'rotate(180deg)';
        });
        setTimeout(() => menu.classList.remove('menu-enter', 'menu-enter-active'), 200);
      }
      function closeMenu() {
        if (menu.classList.contains('hidden')) return;
        menu.classList.add('menu-exit');
        menu.classList.add('menu-exit-active');
        chev.style.transform = 'rotate(0deg)';
        setTimeout(() => {
          menu.classList.add('hidden');
          menu.classList.remove('menu-exit', 'menu-exit-active');
        }, 160);
      }

      btn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (menu.classList.contains('hidden')) openMenu(); else closeMenu();
      });

      document.addEventListener('click', (e) => {
        if (!menu.contains(e.target)) closeMenu();
      });
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMenu();
      });

      // Option clicks -> set label then navigate to edit page with query
      menu.querySelectorAll('button[data-value]').forEach(opt => {
        opt.addEventListener('click', () => {
          const val = opt.getAttribute('data-value');
          label.textContent = val;
          closeMenu();
          window.location.href = "{{ route('student.submissions.edit') }}" + "?activity=" + encodeURIComponent(val);
        });
      });
    })();
  </script>
</body>
</html>
