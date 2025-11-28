<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | myITS Fitness</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
    .nice-scroll::-webkit-scrollbar{width:8px}
    .nice-scroll::-webkit-scrollbar-thumb{background:#dfe7fb;border-radius:9999px}
    .eqH{height:184px;}
    .timeline-img { width:100%; height:100%; object-fit:cover; object-position:center; display:block; }

    /* --- Shiny hover overlay (ala Steam) --- */
    .hover-glow {
      background-image:
        radial-gradient(1200px 300px at -20% -20%, rgba(255,255,255,.25) 0%, rgba(255,255,255,0) 60%),
        linear-gradient(135deg, #4271ffff 0%, #2d81ffff 50%, #6c89ffff 100%);
      opacity: 0;
      transition: opacity .28s ease;
    }
    .group:hover .hover-glow { opacity:.28; } /* ~28% transparansi */
  </style>
</head>

<body class="bg-[#f7f9fc] text-slate-800">
  {{-- Topbar (with Logout Overlay) --}}
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-8 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
        </a>
      </div>

      <div class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>

        {{-- user button --}}
        <button id="userMenuBtn" type="button" class="rounded-full focus:outline-none">
          <img src="{{ asset('images/icon-user.png') }}" alt="User" class="w-9 h-9 rounded-full object-cover">
        </button>

        {{-- logout overlay (klik gambar untuk "logout" → balik ke login student) --}}
        <div id="logoutOverlay" class="hidden absolute right-0 top-[2.75rem] z-50">
          <a href="{{ route('login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout"
                 class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  {{-- Toggle overlay logic --}}
  <script>
    (function () {
      const btn = document.getElementById('userMenuBtn');
      const overlay = document.getElementById('logoutOverlay');

      document.addEventListener('click', (e) => {
        if (btn && btn.contains(e.target)) {
          overlay.classList.toggle('hidden');
          return;
        }
        if (overlay && !overlay.contains(e.target)) {
          overlay.classList.add('hidden');
        }
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') overlay.classList.add('hidden');
      });
    })();
  </script>

  <main>
    <div class="max-w-7xl mx-auto px-8 py-8">

      <!-- GRID DUA KOLOM -->
      <div class="grid gap-8 md:grid-cols-[260px,1fr]">

        {{-- Sidebar --}}
        <aside>
          <nav class="space-y-3">
            <a href="{{ route('student.dashboard') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100 w-full">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-semibold">Home</span>
            </a>
            <a href="{{ route('student.submit') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50 w-full">
              <img src="{{ asset('images/submit-icon.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Submit</span>
            </a>
            <a href="{{ route('student.status') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50 w-full">
              <img src="{{ asset('images/status-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Status</span>
            </a>
          </nav>
        </aside>

        {{-- Konten --}}
        <section>
          <div class="grid grid-cols-12 gap-6 items-start">

            {{-- Kiri --}}
            <div class="col-span-12 lg:col-span-8">

              <h1 class="text-4xl font-extrabold tracking-tight">
                Hi, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#5b83ff] to-[#7b61ff]">{{ $student->name ?? 'Student' }}</span>
              </h1>

              {{-- Statistik --}}
              <div class="mt-5 rounded-2xl bg-white border shadow-sm px-6 py-6 eqH flex items-center">
                <div class="grid grid-cols-4 gap-4 sm:gap-6 w-full justify-items-center">

                  {{-- Chart SKEM --}}
                  <div class="flex flex-col items-center">
                    <canvas id="skemChart" width="68" height="68"></canvas>
                    <div class="mt-2 text-center">
                      <div class="text-lg font-semibold">{{ $totalSubmissions }}</div>
                      <div class="text-xs text-slate-500">Activity</div>
                    </div>
                  </div>

                  {{-- Widget Pending --}}
                  <div class="flex flex-col items-center">
                      <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                      <span class="text-blue-600 font-semibold text-lg">{{ $pendingCount }}</span>
                    </div>
                    <div class="mt-2 text-center">
                      <div class="text-xs text-slate-500">Pending</div>
                    </div>
                  </div>

                  {{-- Widget Accepted --}}
                  <div class="flex flex-col items-center">
                      <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                      <span class="text-green-600 font-semibold text-lg">{{ $acceptedCount }}</span>
                    </div>
                    <div class="mt-2 text-center">
                      <div class="text-xs text-slate-500">Accepted</div>
                    </div>
                  </div>

                  {{-- Widget Need Revision --}}
                  <div class="flex flex-col items-center">
                      <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center">
                      <span class="text-yellow-600 font-semibold text-lg">{{ $needRevisionCount }}</span>
                    </div>
                    <div class="mt-2 text-center">
                      <div class="text-xs text-slate-500">Need Revision</div>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Timeline --}}
              <div class="mt-6 space-y-6 max-h-[760px] overflow-y-auto nice-scroll pr-1">
                @forelse($recentSubmissions as $idx => $submission)
                  @php
                    $activity = $submission->activity;
                    $status = $submission->status;

                    $badge = [
                      'Pending' => ['bg' => 'bg-blue-100','text' => 'text-blue-700'],
                      'Accepted' => ['bg' => 'bg-emerald-100','text' => 'text-emerald-700'],
                      'NeedRevision' => ['bg' => 'bg-amber-100','text' => 'text-amber-700'],
                      'Rejected' => ['bg' => 'bg-red-100','text' => 'text-red-700'],
                    ][$status] ?? ['bg' => 'bg-gray-100','text' => 'text-gray-700'];

                    // Get proof image from FileAttachment (FIXED: use actual uploaded image)
                    $proofFile = $submission->fileAttachments()->whereIn('file_type', ['JPG', 'JPEG', 'PNG'])->first();
                    $img = $proofFile ? $proofFile->url : asset('images/harrystyles-proof1.png');
                  @endphp

                  <a href="{{ route('student.submissions.show', $submission->id) }}"
                     class="group relative block rounded-2xl bg-white border shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="hover-glow absolute inset-0 pointer-events-none z-[1]"></div>
                    <article class="relative z-[2]">
                      <div class="p-4">
                        <div class="relative h-64 rounded-xl overflow-hidden">
                          <img src="{{ $img }}" alt="Activity Proof" class="w-full h-full object-cover">
                          <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                          <div class="absolute top-3 left-4 text-white">
                            <div class="text-3xl font-semibold">{{ $activity->name ?? 'Activity' }}</div>
                            <div class="text-xs opacity-90">{{ optional($submission->created_at)->format('d/m/Y') }}</div>
                          </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4 items-center">
                          <div>
                            <div class="text-slate-500 text-sm">Duration</div>
                            <div class="font-semibold">{{ round(($submission->duration_minutes ?? 0) / 60, 2) }} Hours</div>
                          </div>
                          <div class="border-l pl-4">
                            <div class="text-slate-500 text-sm">Location</div>
                            <div class="font-semibold">{{ $activity->location ?? '—' }}</div>
                          </div>
                          <div class="border-l pl-4">
                            <div class="text-slate-500 text-sm">Status</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badge['bg'] }} {{ $badge['text'] }}">{{ $status }}</span>
                          </div>
                        </div>
                      </div>
                    </article>
                  </a>
                @empty
                  <div class="rounded-2xl bg-white border p-6 text-center">No submissions yet.</div>
                @endforelse
              </div>
            </div>

            {{-- Kanan --}}
            <div class="col-span-12 lg:col-span-4 space-y-6 mt-[64px]">
              {{-- Kalender sejajar dgn statistik --}}
              <div class="relative rounded-2xl overflow-hidden shadow-sm border eqH flex items-center justify-center">
                <img src="{{ asset('images/academic-calendar-student.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="Calendar">
                <div class="absolute inset-0 p-6 text-white">
                  <div id="cal-day" class="text-3xl font-bold leading-tight">Tuesday</div>
                  <div id="cal-date" class="text-sm mt-1">14 October 2025</div>
                  <div id="cal-week" class="text-sm mt-2 opacity-90">8th Lecture Week</div>
                </div>
              </div>

              {{-- Ilustrasi sejajar dgn bawah timeline (kotak diperkecil, pas ke gambar) --}}
              <div class="rounded-2xl overflow-hidden bg-white border">
                <img src="{{ asset('images/illustration-dashboard.png') }}" alt="Illustration" class="block w-full h-[360px] object-contain">
              </div>
            </div>
          </div>
        </section>

      </div>
    </div>
  </main>

  {{-- SKEM Chart --}}
  <script>
    const ctx = document.getElementById('skemChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
          labels: ['Activity', 'Remaining'],
          datasets: [{
            data: [{{ $totalSubmissions ?? 0 }}, {{ max(0, 30 - ($totalSubmissions ?? 0)) }}],
            backgroundColor: ['#3B82F6', '#E5E7EB'],
            borderWidth: 0,
            cutout: '70%',
          }]
        },
      options: {
        responsive: false,
        plugins: { legend: { display: false } }
      }
    });
  </script>

  {{-- ✅ Real-time academic calendar --}}
  <script>
    (function () {
      const days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
      const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];

      const now = new Date();
      const dayName = days[now.getDay()];
      const dateNum = now.getDate();
      const monthName = months[now.getMonth()];
      const yearNum = now.getFullYear();

      // Atur awal semester (example: 1 Aug)
      const ACADEMIC_START = new Date(yearNum, 7, 1);

      const week = Math.max(1, Math.floor((now - ACADEMIC_START) / (1000*60*60*24*7)) + 1);

      function ordinal(n){
        const s = ["th","st","nd","rd"];
        const v = n % 100;
        return n + (s[(v - 20) % 10] || s[v] || s[0]);
      }

      const elDay  = document.getElementById("cal-day");
      const elDate = document.getElementById("cal-date");
      const elWeek = document.getElementById("cal-week");

      if (elDay)  elDay.textContent  = dayName;
      if (elDate) elDate.textContent = `${dateNum} ${monthName} ${yearNum}`;
      if (elWeek) elWeek.textContent = `${ordinal(week)} Lecture Week`;
    })();
  </script>

</body>
</html>

{{-- Dashboard Student: Taffy Nirarale Kamajaya - 5026221047 --}}