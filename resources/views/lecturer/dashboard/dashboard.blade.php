<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard | myITS Fitness</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
  </style>

  {{-- Chart.js untuk donut interaktif --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#f7f9fc] text-slate-800">

  {{-- Topbar (with Logout Overlay) --}}
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('lecturer.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
        </a>
      </div>

      <div class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>

        {{-- user button --}}
        <button id="userMenuBtn" type="button" class="rounded-full focus:outline-none">
          <img src="{{ asset('images/icon-user.png') }}" alt="User" class="w-9 h-9 rounded-full object-cover">
        </button>

        {{-- logout overlay: gambar overlay bisa diklik untuk logout, tidak ada tombol merah lagi --}}
        <div id="logoutOverlay" class="hidden absolute right-0 top-12 z-50">
          <a href="{{ route('login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout" class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105 absolute right-0 top-0">
          </a>
        </div>
      </div>
    </div>
  </header>

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
    })();
  </script>

  <main class="relative">
    {{-- Background ornaments (kiri/kanan) --}}
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none" style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      {{-- Notification Alert --}}
      @if (session('success'))
      <div id="successAlert" class="mb-6 rounded-xl border border-emerald-300 bg-emerald-50 p-4 flex items-center gap-3 shadow-sm">
        <div class="flex-1">
          <p class="text-emerald-900 font-medium">{{ session('success') }}</p>
        </div>
        <button onclick="document.getElementById('successAlert').remove()" class="text-emerald-700 hover:text-emerald-900">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
      </div>
      <script>
        setTimeout(() => {
          const alert = document.getElementById('successAlert');
          if (alert) alert.remove();
        }, 4000);
      </script>
      @endif

      <div class="grid grid-cols-12 gap-8">
        {{-- Sidebar --}}
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            {{-- Dashboard (aktif) --}}
            <a href="{{ route('lecturer.dashboard') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-semibold text-slate-900">Dashboard</span>
            </a>

            {{-- Submission --}}
            <a href="{{ route('lecturer.reviews.sublec') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Submission</span>
            </a>

            {{-- Students --}}
            <a href="{{ route('lecturer.index') }}#"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/student-navigator-page-icon.png') }}" class="h-6 w-auto object-contain" alt="">
              <span class="font-medium">Students</span>
            </a>
          </nav>
        </aside>

        {{-- Content --}}
        <section class="col-span-12 md:col-span-9">
          <h1 class="text-4xl font-extrabold tracking-tight">Hi, <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#5b83ff] to-[#7b61ff]">{{ $lecturer->name ?? 'Lecturer' }}</span></h1>

          {{-- STATUS WIDGETS --}}
          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6">
            {{-- Total Submission --}}
            <div class="rounded-2xl bg-white border shadow-sm ring-2 ring-slate-300">
              <div class="h-full p-5 flex flex-col">
                <p class="text-slate-500 text-sm leading-snug min-h-[40px]">Total Submission</p>
                <div class="mt-auto text-4xl font-extrabold leading-none">{{ $totalSubmissions }}</div>
              </div>
            </div>

            {{-- Pending --}}
            <div class="rounded-2xl bg-white border shadow-sm ring-2 ring-blue-300">
              <div class="h-full p-5 flex flex-col">
                <p class="text-slate-500 text-sm leading-snug min-h-[40px]">
                  Pending <span class="hidden md:inline">Submission</span>
                </p>
                <div class="mt-auto text-4xl font-extrabold leading-none">{{ $pendingCount }}</div>
              </div>
            </div>

            {{-- Accepted --}}
            <div class="rounded-2xl bg-white border shadow-sm ring-2 ring-emerald-300">
              <div class="h-full p-5 flex flex-col">
                <p class="text-slate-500 text-sm leading-snug min-h-[40px]">
                  Accepted <span class="hidden md:inline">Submission</span>
                </p>
                <div class="mt-auto text-4xl font-extrabold leading-none">{{ $acceptedCount }}</div>
              </div>
            </div>

            {{-- Need Revision --}}
            <div class="rounded-2xl bg-white border shadow-sm ring-2 ring-amber-300">
              <div class="h-full p-5 flex flex-col">
                <p class="text-slate-500 text-sm leading-snug min-h-[40px]">Need Revision</p>
                <div class="mt-auto text-4xl font-extrabold leading-none">{{ $needRevisionCount }}</div>
              </div>
            </div>

            {{-- Rejected --}}
            <div class="rounded-2xl bg-white border shadow-sm ring-2 ring-rose-300">
              <div class="h-full p-5 flex flex-col">
                <p class="text-slate-500 text-sm leading-snug min-h-[40px]">
                  Rejected <span class="hidden md:inline">Submission</span>
                </p>
                <div class="mt-auto text-4xl font-extrabold leading-none">{{ $rejectedCount }}</div>
              </div>
            </div>
          </div>

          {{-- Ringkasan + Kalender --}}
          <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl bg-white border shadow-sm p-6">
              <h2 class="font-semibold text-lg flex items-center gap-2">
                <span class="text-slate-700">Submission Status</span>
              </h2>

              <div class="mt-6 grid grid-cols-12 gap-6 items-center">
                {{-- Donut interaktif (Chart.js) - DIPERKECIL --}}
                <div class="col-span-12 md:col-span-5 flex items-center justify-center">
                  <div class="relative w-44 h-44"> {{-- was w-56 h-56 --}}
                    <canvas id="submissionDonut" width="176" height="176"></canvas>
                  </div>
                </div>

                {{-- Legend - DIPERKECIL & RAPIH --}}
                <div class="col-span-12 md:col-span-7">
                  @php
                    $stats = [
                      'Accepted Submission' => $acceptedCount,
                      'Pending Submission'  => $pendingCount,
                      'Need Revision'       => $needRevisionCount,
                      'Rejected Submission' => $rejectedCount,
                    ];
                    $totalSubmission = $totalSubmissions;
                  @endphp
                  <ul class="space-y-2 text-[13px] leading-5"> {{-- was text-sm space-y-3 --}}
                    <li class="flex items-center gap-2.5">
                      <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                      <span>Accepted Submission</span>
                      <span class="ml-auto font-semibold">{{ $stats['Accepted Submission'] }}</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                      <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                      <span>Pending Submission</span>
                      <span class="ml-auto font-semibold">{{ $stats['Pending Submission'] }}</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                      <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                      <span>Need Revision</span>
                      <span class="ml-auto font-semibold">{{ $stats['Need Revision'] }}</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                      <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                      <span>Rejected Submission</span>
                      <span class="ml-auto font-semibold">{{ $stats['Rejected Submission'] }}</span>
                    </li>
                  </ul>

                  {{-- angka total di center donut via plugin --}}
                  <script>
                    (function () {
                      const ctx = document.getElementById('submissionDonut')?.getContext('2d');
                      if (!ctx) return;

                      const labels = ['Accepted Submission', 'Pending Submission', 'Need Revision', 'Rejected Submission'];
                      const data = [{{ $stats['Accepted Submission'] }}, {{ $stats['Pending Submission'] }}, {{ $stats['Need Revision'] }}, {{ $stats['Rejected Submission'] }}];
                      const colors = ['#10B981', '#3B82F6', '#F59E0B', '#F43F5E'];

                      // Plugin center text - font diperkecil agar proporsional dgn chart
                      const centerText = {
                        id: 'centerText',
                        afterDraw(chart) {
                          const { ctx } = chart;
                          const c = chart.getDatasetMeta(0).data[0];
                          if (!c) return;
                          ctx.save();
                          ctx.font = '700 22px Poppins, system-ui';   // was 28px
                          ctx.fillStyle = '#0f172a';
                          ctx.textAlign = 'center';
                          ctx.textBaseline = 'middle';
                          ctx.fillText('{{ $totalSubmission }}', c.x, c.y - 6);
                          ctx.font = '500 11px Poppins, system-ui';   // was 12px
                          ctx.fillStyle = '#64748b';
                          ctx.fillText('Submission', c.x, c.y + 12);
                          ctx.restore();
                        }
                      };

                      new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                          labels,
                          datasets: [{
                            data,
                            backgroundColor: colors,
                            borderWidth: 0
                          }]
                        },
                        options: {
                          responsive: true,
                          maintainAspectRatio: false,
                          cutout: '68%',
                          plugins: {
                            legend: { display: false },
                            tooltip: {
                              backgroundColor: '#0f172a',
                              titleColor: '#e2e8f0',
                              bodyColor: '#e2e8f0',
                              displayColors: true,
                              callbacks: {
                                label: function(ctx) {
                                  const value = ctx.parsed;
                                  const total = data.reduce((a,b)=>a+b,0);
                                  const pct = total ? Math.round((value/total)*100) : 0;
                                  return ` ${ctx.label}: ${value} (${pct}%)`;
                                }
                              }
                            }
                          },
                          animation: { duration: 600, easing: 'easeOutQuart' }
                        },
                        plugins: [centerText]
                      });
                    })();
                  </script>
                </div>
              </div>
            </div>

            {{-- Kalender realtime --}}
            <div class="rounded-2xl bg-white border shadow-sm p-6">
              @php
                $year  = (int) date('Y');
                $month = (int) date('n');
                $today = (int) date('j');
                $monthNames = [1=>'January','February','March','April','May','June','July','August','September','October','November','December'];
                $monthName  = $monthNames[$month];
                $weekdayLabels = ['S','M','T','W','T','F','S'];
                $firstWeekday = (int) date('w', strtotime("$year-$month-01"));
                $daysInMonth  = cal_days_in_month(CAL_GREGORIAN, $month, $year);
              @endphp

              <h2 class="font-semibold text-lg">{{ $monthName }} {{ $year }}</h2>
              <div class="mt-4 grid grid-cols-7 gap-2 text-center text-sm">
                @foreach($weekdayLabels as $d)
                  <div class="text-slate-500">{{ $d }}</div>
                @endforeach
                @for($k = 0; $k < $firstWeekday; $k++)
                  <div></div>
                @endfor
                @for($i = 1; $i <= $daysInMonth; $i++)
                  @php
                    $isToday = ($i === $today);
                    $classes = $isToday ? 'ring-2 ring-[#6b63ff] font-semibold' : 'bg-white';
                  @endphp
                  <div class="py-2 rounded-xl border {{ $classes }}">{{ $i }}</div>
                @endfor
              </div>
            </div>
          </div>

          {{-- RECENT SUBMISSION --}}
          <div class="mt-6 rounded-2xl bg-white border shadow-sm p-0 overflow-hidden">
            <h2 class="px-6 pt-6 pb-3 font-semibold text-lg flex items-center gap-2">
              <img src="{{ asset('images/revent-submission-icon.png') }}" class="w-6 h-6" alt="">
              <span>Recent Submission</span>
            </h2>

            {{-- Scroll: maksimal 5 baris --}}
            <div class="overflow-x-auto overflow-y-auto max-h-[416px] pr-2">
              <table class="min-w-full">
                <thead class="text-left text-slate-500 text-sm bg-slate-50 sticky top-0 z-10">
                  <tr>
                    <th class="px-8 py-4 font-semibold">Name</th>
                    <th class="px-8 py-4 font-semibold">Activity</th>
                    <th class="px-8 py-4 font-semibold">Date</th>
                    <th class="px-8 py-4 font-semibold">Status</th>
                    <th class="px-8 py-4 font-semibold">Proof</th>
                  </tr>
                </thead>
                <tbody class="text-slate-800">
                  @php
                    $badge = [
                      'Pending'       => 'bg-blue-100 text-blue-600',
                      'Accepted'      => 'bg-green-100 text-green-700',
                      'Rejected'      => 'bg-rose-100  text-rose-700',
                      'NeedRevision'  => 'bg-amber-100 text-amber-700',
                    ];
                  @endphp

                  @forelse ($recentSubmissions as $i => $submission)
                    <tr class="group {{ $i % 2 ? 'bg-white' : 'bg-slate-50/40' }} hover:bg-slate-50 transition-colors duration-200 cursor-pointer"
                        onclick="window.location='{{ route('lecturer.submissions.show', $submission->id) }}'">
                      <td class="px-8 py-5 align-middle">
                        <span class="font-semibold text-slate-900
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $submission->student->name ?? 'N/A' }}
                        </span>
                      </td>
                      <td class="px-8 py-5 align-middle">
                        <span class="text-slate-700
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $submission->activity->name ?? 'N/A' }}
                        </span>
                      </td>
                      <td class="px-8 py-5 align-middle">
                        <span class="text-slate-700
                                     group-hover:text-transparent group-hover:bg-clip-text
                                     group-hover:bg-gradient-to-r group-hover:from-[#5b83ff] group-hover:to-[#7b61ff]">
                          {{ $submission->created_at->format('M d, Y') }}
                        </span>
                      </td>
                      <td class="px-8 py-5 align-middle">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badge[$submission->status] ?? 'bg-slate-100 text-slate-600' }}">
                          {{ $submission->status === 'NeedRevision' ? 'Need Revision' : $submission->status }}
                        </span>
                      </td>
                      <td class="px-8 py-5 align-middle">
                        @if ($submission->fileAttachments && $submission->fileAttachments->count() > 0)
                          @php
                            $proofUrl = $submission->fileAttachments->first()->url;
                            if (strpos($proofUrl, 'submissions/') === 0) {
                              $proofUrl = '/storage/' . $proofUrl;
                            }
                          @endphp
                          <img src="{{ $proofUrl }}" alt="proof" class="w-8 h-8 rounded-full object-cover border shadow-sm">
                        @else
                          <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs text-slate-500">N/A</div>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="px-8 py-8 text-center text-slate-500">
                        No recent submissions
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
</body>
</html>
