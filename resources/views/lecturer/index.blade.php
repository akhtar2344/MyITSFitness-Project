<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Students | myITS Fitness</title>

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
        <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
      </div>
      <div id="topbarUser" class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User" class="w-9 h-9 rounded-full object-cover cursor-pointer select-none">
        <div id="logoutOverlay" class="hidden absolute right-0 top-[3.25rem] z-50">
          <a href="{{ route('login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout" class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="relative">
    {{-- Background ornaments --}}
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none" style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-12 gap-8">
        {{-- Sidebar --}}
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            <a href="{{ route('lecturer.dashboard') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('lecturer.reviews.sublec') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm">
              <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Submission</span>
            </a>
            <a href="{{ route('lecturer.students.index') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100">
              <img src="{{ asset('images/student-navigator-page-icon.png') }}" class="h-6 w-auto object-contain" alt="">
              <span class="font-semibold text-slate-900">Students</span>
            </a>
          </nav>
        </aside>

        {{-- Content --}}
        <section class="col-span-12 md:col-span-9">
          <h1 class="text-4xl font-extrabold tracking-tight">Students</h1>

        {{-- Search --}}
          <div class="mt-5 flex flex-col max-w-3xl">
            <div class="relative flex-1">
              <input
                id="searchInput"
                type="text"
                placeholder="Search by name, NRP, or program"
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
          </div>

          {{-- Table card --}}
          <div class="mt-6 rounded-2xl bg-white border shadow-sm p-0 overflow-hidden">
            {{-- Scrollable container: kira-kira 8 baris, header sticky --}}
            <div class="overflow-x-auto overflow-y-auto max-h-[480px] pr-2">
              <table class="min-w-full">
                <thead class="text-left text-slate-500 text-sm bg-slate-50 sticky top-0 z-10">
                  <tr>
                    <th class="px-8 py-4 font-semibold">Name</th>
                    <th class="px-8 py-4 font-semibold">NRP</th>
                    <th class="px-8 py-4 font-semibold">Email</th>
                    <th class="px-8 py-4 font-semibold">Program</th>
                  </tr>
                </thead>
                <tbody id="studentTable" class="text-slate-800">
                  @forelse ($students as $i => $student)
                    <tr class="student-row group cursor-pointer {{ $i % 2 ? 'bg-white' : 'bg-slate-50/40' }} hover:bg-slate-50 transition-colors duration-200"
                        onclick="window.location='{{ route('lecturer.status.account', $student->nrp) }}'">
                      {{-- NAME --}}
                      <td class="px-8 py-4">
                        <span class="font-semibold text-slate-900 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $student->name }}
                        </span>
                      </td>

                      {{-- NRP --}}
                      <td class="px-8 py-4">
                        <span class="inline-block text-slate-600 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $student->nrp }}
                        </span>
                      </td>

                      {{-- EMAIL --}}
                      <td class="px-8 py-4">
                        <span class="inline-block text-slate-600 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $student->email }}
                        </span>
                      </td>

                      {{-- PROGRAM --}}
                      <td class="px-8 py-4">
                        <span class="inline-block font-semibold text-slate-600 transition duration-200
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $student->program }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="px-8 py-8 text-center text-slate-500">
                        No students found.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  {{-- Search logic --}}
  <script>
    const input   = document.getElementById('searchInput');
    const button  = document.getElementById('iconButton');
    const icon    = document.getElementById('searchIcon');
    const tbody   = document.getElementById('studentTable');
    const rows    = Array.from(document.querySelectorAll('.student-row'));

    rows.forEach((row, i) => row.dataset.idx = i);

    const searchIcon = "{{ asset('images/search-icon.png') }}";
    const eraseIcon  = "{{ asset('images/erase-icon.png') }}";

    const norm = (s) => (s || '').toLowerCase().trim();

    function searchStudentKeyword(row, q){
      if (!q) return -1;
      const name = norm(row.querySelector('td:nth-child(1)').innerText);
      const nrp  = norm(row.querySelector('td:nth-child(2)').innerText);
      const email = norm(row.querySelector('td:nth-child(3)').innerText);
      const prog = norm(row.querySelector('td:nth-child(4)').innerText);
      let score = 0;
      if (name === q)              score += 200;
      else if (name.startsWith(q)) score += 120;
      else if (name.includes(q))   score += 60;
      if (nrp.startsWith(q))       score += 110;
      else if (nrp.includes(q))    score += 55;
      if (email.startsWith(q))     score += 100;
      else if (email.includes(q))  score += 50;
      if (prog.startsWith(q))      score += 90;
      else if (prog.includes(q))   score += 45;
      return score;
    }

    function handleSearchEvent(){
      const q = norm(input.value);
      const hasValue = q.length > 0;

      icon.src = hasValue ? eraseIcon : searchIcon;
      button.title = hasValue ? 'Clear' : 'Search';

      // Reset order by original index
      rows.sort((a,b) => +a.dataset.idx - +b.dataset.idx).forEach(r => tbody.appendChild(r));

      if (!hasValue){
        rows.forEach(r => r.style.display = '');
        return;
      }

      // Search with ranking
      const ranked = rows.map(r => {
        const s = searchStudentKeyword(r, q);
        const visible = s > 0;
        r.style.display = visible ? '' : 'none';
        return { el: r, s };
      }).filter(x => x.s > 0 && x.el.style.display !== 'none');

      ranked.sort((a,b) => b.s - a.s || (+a.el.dataset.idx - +b.el.dataset.idx));
      ranked.forEach(x => tbody.appendChild(x.el));
    }

    // Search interactions
    input.addEventListener('input', handleSearchEvent);
    button.addEventListener('click', () => {
      if (input.value.trim() !== '') {
        input.value = '';
        handleSearchEvent();
        input.focus();
      } else {
        input.focus();
      }
    });
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
</body>
</html>