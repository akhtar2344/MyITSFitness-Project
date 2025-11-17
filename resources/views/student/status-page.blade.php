<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Status | myITS Fitness</title>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
  </style>
</head>
<body class="bg-[#f7f9fc] text-slate-800">

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

        {{-- avatar as button --}}
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User"
             class="w-9 h-9 rounded-full object-cover cursor-pointer select-none">

        {{-- logout overlay --}}
        <div id="logoutOverlay" class="hidden absolute right-0 top-[2.75rem] z-50">
          <a href="{{ route('student.login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout"
                 class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="relative">
    {{-- Background ornaments --}}
    <img src="{{ asset('images/back-ornament.png') }}"
         class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back-ornament.png') }}"
         class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none"
         style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-12 gap-8">
        {{-- Sidebar --}}
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Home</span>
            </a>

            <a href="{{ route('student.submit') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/submit-icon.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Submit</span>
            </a>

            {{-- Active: Status --}}
            <a class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100">
              <img src="{{ asset('images/status-page.png') }}" class="h-6 w-auto object-contain" alt="">
              <span class="font-semibold text-slate-900">Status</span>
            </a>
          </nav>
        </aside>

        {{-- Content --}}
        <section class="col-span-12 md:col-span-9">
          <h1 class="text-4xl font-extrabold tracking-tight">Submission History</h1>

          {{-- Search + Filter --}}
          <div class="mt-5 flex flex-col md:flex-row md:items-center gap-3 md:gap-4 max-w-3xl">
            <div class="relative flex-1">
              <input
                id="searchInput"
                type="text"
                placeholder="Search for"
                class="w-full rounded-full border border-slate-200 bg-white px-5 py-3 pr-12 text-slate-700 placeholder-slate-400 focus:border-blue-400 focus:ring-2 focus:ring-blue-100"
              />
              <button
                id="iconButton"
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-9 h-9 focus:outline-none"
                aria-label="Search or clear"
                title="Search"
              >
                <img id="searchIcon" src="{{ asset('images/search-icon.png') }}" alt="Search" class="w-5 h-5 select-none">
              </button>
            </div>

            {{-- Filter Status (custom dropdown) --}}
            <div class="relative">
              {{-- Hidden value for logic --}}
              <input type="hidden" id="filterStatus" value="All" />

              <button id="filterBtn" type="button"
                      class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white pl-4 pr-11 py-3 text-slate-700 shadow-sm hover:shadow focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                <span id="filterDot" class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                <span id="filterLabel">All Status</span>
                <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 transition group-data-[open=true]:rotate-180"
                     id="filterChevron" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.168l3.71-2.94a.75.75 0 01.94 1.17l-4.2 3.33a.75.75 0 01-.94 0l-4.2-3.33a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
                </svg>
              </button>

              <div id="filterMenu"
                   class="invisible opacity-0 scale-95 transition transform origin-top-right absolute right-0 mt-2 w-56 z-40
                          rounded-2xl border border-slate-200 bg-white shadow-xl ring-1 ring-black/5 backdrop-blur-sm">
                <ul class="py-2 text-sm">
                  <li>
                    <button data-value="All" class="filter-opt w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50">
                      <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                      <span class="flex-1 text-left">All Status</span>
                      <svg class="check hidden h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.414 0l-3.3-3.3a1 1 0 111.414-1.414l2.593 2.594 6.303-6.303a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </button>
                  </li>
                  <li class="border-t border-slate-100 my-1"></li>
                  <li>
                    <button data-value="Pending" class="filter-opt w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50">
                      <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                      <span class="flex-1 text-left">Pending</span>
                      <svg class="check hidden h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.414 0l-3.3-3.3a1 1 0 111.414-1.414l2.593 2.594 6.303-6.303a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </button>
                  </li>
                  <li>
                    <button data-value="Accepted" class="filter-opt w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50">
                      <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                      <span class="flex-1 text-left">Accepted</span>
                      <svg class="check hidden h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.414 0l-3.3-3.3a1 1 0 111.414-1.414l2.593 2.594 6.303-6.303a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </button>
                  </li>
                  <li>
                    <button data-value="Need Revision" class="filter-opt w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50">
                      <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                      <span class="flex-1 text-left">Need Revision</span>
                      <svg class="check hidden h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.414 0l-3.3-3.3a1 1 0 111.414-1.414l2.593 2.594 6.303-6.303a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </button>
                  </li>
                  <li>
                    <button data-value="Rejected" class="filter-opt w-full px-4 py-2.5 flex items-center gap-3 hover:bg-slate-50">
                      <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                      <span class="flex-1 text-left">Rejected</span>
                      <svg class="check hidden h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.01 7.01a1 1 0 01-1.414 0l-3.3-3.3a1 1 0 111.414-1.414l2.593 2.594 6.303-6.303a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          {{-- Table card --}}
          <div class="mt-6 rounded-2xl bg-white border shadow-sm p-0 overflow-hidden">
            {{-- Scrollable container --}}
            <div class="overflow-x-auto overflow-y-auto max-h-[576px] pr-2">
              <table class="min-w-full">
                <thead class="text-left text-slate-500 text-sm bg-slate-50 sticky top-0 z-10">
                  <tr>
                    <th class="px-8 py-4 font-semibold">Name</th>
                    <th class="px-8 py-4 font-semibold">Activity</th>
                    <th class="px-8 py-4 font-semibold">Date</th>
                    <th class="px-8 py-4 font-semibold">Status</th>
                  </tr>
                </thead>

                <tbody id="studentTable" class="text-slate-800">
                  @php
                    $rows = [
                      ['name'=>'Harry Styles','activity'=>'Running','date'=>'November 2, 2024','status'=>'Pending'],
                      ['name'=>'Harry Styles','activity'=>'Running','date'=>'October 2, 2024','status'=>'Accepted'],
                      ['name'=>'Harry Styles','activity'=>'Running','date'=>'October 1, 2024','status'=>'Need Revision'],
                    ];
                    $badge = [
                      'Pending'       => 'bg-blue-100 text-blue-600',
                      'Accepted'      => 'bg-green-100 text-green-700',
                      'Rejected'      => 'bg-rose-100 text-rose-700',
                      'Need Revision' => 'bg-amber-100 text-amber-700',
                    ];

                    /* Tambahan: target detail per status (tidak mengubah yang lain) */
                    $detailHref = [
                      'Pending'       => route('student.activity.show.pending'),
                      'Accepted'      => route('student.activity.show.accepted'),
                      'Need Revision' => route('student.activity.show.needrevision'),
                      'Rejected'      => '#',
                    ];
                  @endphp

                  @foreach ($rows as $i => $r)
                    <tr
                      class="student-row group {{ $i % 2 ? 'bg-white' : 'bg-slate-50/40' }} hover:bg-slate-50 transition-colors duration-200 cursor-pointer"
                      data-status="{{ $r['status'] }}"
                      data-href="{{ $detailHref[$r['status']] ?? '#' }}"
                      tabindex="0"
                      role="button"
                    >
                      <td class="px-8 py-4">
                        <span class="font-semibold text-slate-900 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $r['name'] }}
                        </span>
                      </td>
                      <td class="px-8 py-4">
                        <span class="font-semibold text-slate-600 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $r['activity'] }}
                        </span>
                      </td>
                      <td class="px-8 py-4">
                        <span class="font-semibold text-slate-600 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $r['date'] }}
                        </span>
                      </td>
                      <td class="px-8 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium transition duration-200
                                    {{ $badge[$r['status']] ?? 'bg-slate-100 text-slate-600' }}
                                    group-hover:bg-blue-100 group-hover:text-blue-600">
                          {{ $r['status'] }}
                        </span>
                      </td>
                    </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  {{-- Search + filter logic --}}
  <script>
    const input   = document.getElementById('searchInput');
    const button  = document.getElementById('iconButton');
    const icon    = document.getElementById('searchIcon');
    const tbody   = document.getElementById('studentTable');
    const rows    = Array.from(document.querySelectorAll('.student-row'));
    const filter  = document.getElementById('filterStatus');

    // Custom dropdown elements
    const filterBtn    = document.getElementById('filterBtn');
    const filterMenu   = document.getElementById('filterMenu');
    const filterLabel  = document.getElementById('filterLabel');
    const filterDot    = document.getElementById('filterDot');
    const optionsBtns  = Array.from(document.querySelectorAll('.filter-opt'));
    const chevron      = document.getElementById('filterChevron');

    rows.forEach((row, i) => row.dataset.idx = i);

    const searchIcon = "{{ asset('images/search-icon.png') }}";
    const eraseIcon  = "{{ asset('images/erase-icon.png') }}";

    const norm = (s) => (s || '').toLowerCase().trim();

    function scoreRow(row, q){
      if (!q) return -1;
      const name = norm(row.querySelector('td:nth-child(1)').innerText);
      const act  = norm(row.querySelector('td:nth-child(2)').innerText);
      const date = norm(row.querySelector('td:nth-child(3)').innerText);
      let score = 0;
      if (name === q)              score += 200;
      else if (name.startsWith(q)) score += 120;
      else if (name.includes(q))   score += 60;

      if (act.startsWith(q))       score += 110;
      else if (act.includes(q))    score += 55;

      if (date.startsWith(q))      score += 90;
      else if (date.includes(q))   score += 45;
      return score;
    }

    function applyFilter(){
      const q = norm(input.value);
      const picked = filter.value;
      const hasValue = q.length > 0;

      icon.src = hasValue ? eraseIcon : searchIcon;
      button.title = hasValue ? 'Clear' : 'Search';

      rows.sort((a,b) => +a.dataset.idx - +b.dataset.idx).forEach(r => tbody.appendChild(r));

      rows.forEach(r => {
        const statusOk = (picked === 'All') ? true : (r.dataset.status === picked);
        r.dataset.statusOk = statusOk ? '1' : '0';
      });

      if (!hasValue){
        rows.forEach(r => r.style.display = (r.dataset.statusOk === '1') ? '' : 'none');
        return;
      }

      const ranked = rows.map(r => {
        const s = scoreRow(r, q);
        const visible = (s > 0) && (r.dataset.statusOk === '1');
        r.style.display = visible ? '' : 'none';
        return { el: r, s };
      }).filter(x => x.s > 0 && x.el.style.display !== 'none');

      ranked.sort((a,b) => b.s - a.s || (+a.el.dataset.idx - +b.el.dataset.idx));
      ranked.forEach(x => tbody.appendChild(x.el));
    }

    input.addEventListener('input', applyFilter);
    button.addEventListener('click', () => {
      if (input.value.trim() !== '') {
        input.value = '';
        applyFilter();
        input.focus();
      } else {
        input.focus();
      }
    });

    // Dropdown logic
    const dotMap = {
      'All': 'bg-slate-400',
      'Pending': 'bg-blue-500',
      'Accepted': 'bg-emerald-500',
      'Need Revision': 'bg-amber-500',
      'Rejected': 'bg-rose-500'
    };

    function setFilter(value, label){
      filter.value = value;
      filterLabel.textContent = label;
      filterDot.className = 'w-2.5 h-2.5 rounded-full ' + (dotMap[value] || 'bg-slate-400');
      optionsBtns.forEach(btn => {
        const isSel = btn.dataset.value === value;
        const check = btn.querySelector('.check');
        if (check) check.classList.toggle('hidden', !isSel);
      });
      applyFilter();
    }

    function openMenu(){
      filterMenu.classList.remove('invisible','opacity-0','scale-95');
      filterBtn.dataset.open = 'true';
      chevron.classList.add('[&]:rotate-180');
    }
    function closeMenu(){
      filterMenu.classList.add('invisible','opacity-0','scale-95');
      filterBtn.dataset.open = 'false';
      chevron.classList.remove('[&]:rotate-180');
    }

    filterBtn.addEventListener('click', (e)=>{
      e.stopPropagation();
      const open = filterBtn.dataset.open === 'true';
      open ? closeMenu() : openMenu();
    });
    document.addEventListener('click', (e)=>{
      if (filterBtn.dataset.open === 'true' && !filterMenu.contains(e.target)){
        closeMenu();
      }
    });
    document.addEventListener('keydown', (e)=>{
      if (e.key === 'Escape') closeMenu();
    });

    optionsBtns.forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const val = btn.dataset.value;
        const label = btn.textContent.trim();
        setFilter(val, label);
        closeMenu();
      });
    });

    // default: All Status
    setFilter('All','All Status');
  </script>

  {{-- Logout Overlay logic --}}
  <script>
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
  </script>
  
  <!-- Row redirect ke detail (tambahan; non-intrusif) -->
  <script>
    document.querySelectorAll('.student-row').forEach(row => {
      const href = row.dataset.href;
      if (!href || href === '#') return;

      row.addEventListener('click', (e) => {
        // hindari klik pada elemen interaktif lain jika nanti ada
        if (e.target.closest('a, button, input, select, textarea')) return;
        if (e.metaKey || e.ctrlKey) {
          window.open(href, '_blank');
        } else {
          window.location.href = href;
        }
      });

      row.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          window.location.href = href;
        }
      });
    });
  </script>
</body>
</html>
