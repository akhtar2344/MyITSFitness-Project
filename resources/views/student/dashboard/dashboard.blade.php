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

    /* Dashboard responsive improvements */
    @media (max-width: 1024px) {
      .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
      }
      .chart-container canvas {
        width: 80px !important;
        height: 80px !important;
      }
      .status-summary {
        padding: 0.5rem;
      }
      .status-card {
        padding: 0.5rem;
      }
    }
    
    @media (max-width: 768px) {
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
      }
      .chart-container {
        padding: 0.25rem;
      }
      .chart-container canvas {
        width: 70px !important;
        height: 70px !important;
      }
      .status-summary {
        padding: 0.5rem;
        height: auto !important;
      }
      .status-card {
        padding: 0.5rem;
      }
      .status-card .text-lg {
        font-size: 1rem;
      }
      .status-card .text-sm {
        font-size: 0.75rem;
      }
    }

    @media (max-width: 640px) {
      .chart-container canvas {
        width: 60px !important;
        height: 60px !important;
      }
      .chart-container .text-lg {
        font-size: 1rem;
      }
      .chart-container .text-xl {
        font-size: 1.25rem;
      }
    }

    /* Chart container styling - compact version */
    .chart-container {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 0.5rem;
      min-height: 140px;
    }

    /* Horizontal status cards styling */
    .horizontal-status-card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .horizontal-status-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
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
    <div class="max-w-7xl mx-auto px-4 py-6">

      <!-- GRID DUA KOLOM -->
      <div class="grid gap-6 md:grid-cols-[240px,1fr]">

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

              {{-- Statistik Dashboard - 3 Column Layout --}}
              <div class="mt-5 rounded-2xl bg-white border shadow-sm px-3 py-3 eqH">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center h-full">

                  {{-- Overall Status Pie Chart --}}
                  <div class="chart-container">
                    <div class="relative">
                      <canvas id="overallChart" width="100" height="100"></canvas>
                    </div>
                    <div class="mt-2 text-center">
                      <div class="text-base font-semibold text-slate-700">{{ $totalSubmissions }}</div>
                      <div class="text-xs text-slate-500">Overall Submissions</div>
                    </div>
                  </div>

                  {{-- Accepted Progress (Donut Chart) --}}
                  <div class="chart-container">
                    <div class="relative">
                      <canvas id="acceptedChart" width="100" height="100"></canvas>
                      {{-- Center text for donut chart --}}
                      <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <div class="text-lg font-bold text-emerald-600">{{ $acceptedCount }}</div>
                        <div class="text-xs text-slate-500">/ 32</div>
                      </div>
                    </div>
                    <div class="mt-2 text-center">
                      <div class="text-sm font-semibold text-emerald-600">{{ $acceptedCount > 0 ? round(($acceptedCount/32)*100, 1) : 0 }}% Complete</div>
                      <div class="text-xs text-slate-500">Accepted Activities</div>
                    </div>
                  </div>

                  {{-- Status Summary - Vertical Cards --}}
                  <div class="space-y-2 flex flex-col justify-center">
                    {{-- Pending --}}
                    <div class="status-card bg-white rounded-lg p-2 border border-blue-100 flex items-center justify-between hover:shadow-md transition-shadow">
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500 flex-shrink-0"></div>
                        <span class="text-sm font-medium text-slate-700 leading-tight">Pending</span>
                      </div>
                      <div class="text-lg font-bold text-blue-600">{{ $pendingCount }}</div>
                    </div>
                    
                    {{-- Need Revision --}}
                    <div class="status-card bg-white rounded-lg p-2 border border-amber-100 flex items-center justify-between hover:shadow-md transition-shadow">
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-amber-500 flex-shrink-0"></div>
                        <span class="text-sm font-medium text-slate-700 leading-tight">Need Revision</span>
                      </div>
                      <div class="text-lg font-bold text-amber-600">{{ $needRevisionCount }}</div>
                    </div>
                    
                    {{-- Rejected --}}
                    <div class="status-card bg-white rounded-lg p-2 border border-red-100 flex items-center justify-between hover:shadow-md transition-shadow">
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500 flex-shrink-0"></div>
                        <span class="text-sm font-medium text-slate-700 leading-tight">Rejected</span>
                      </div>
                      <div class="text-lg font-bold text-red-600">{{ $rejectedCount }}</div>
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
                    $proofUrl = null;
                    if ($proofFile && $proofFile->url) {
                      // Convert database path to public URL
                      if (strpos($proofFile->url, 'submissions/') === 0) {
                        $proofUrl = '/storage/' . $proofFile->url;
                      } else {
                        $proofUrl = $proofFile->url;
                      }
                    }
                    $img = $proofUrl ?: asset('images/harrystyles-proof1.png');
                  @endphp

                  <a href="{{ route('student.submissions.show', $submission->id) }}"
                     class="group relative block rounded-2xl bg-white border shadow-sm overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="hover-glow absolute inset-0 pointer-events-none z-[1]"></div>
                    <article class="relative z-[2]">
                      <div class="p-4">
                        <div class="relative h-64 rounded-xl overflow-hidden">
                          <img src="{{ $img }}" alt="Activity Proof" class="w-full h-full object-cover">
                          <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                          <div class="absolute top-3 left-4 text-white">
                            <div class="text-3xl font-semibold drop-shadow-lg">{{ $activity->name ?? 'Activity' }}</div>
                            <div class="text-xs opacity-90 drop-shadow-md">{{ optional($submission->created_at)->format('d/m/Y') }}</div>
                          </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4 items-center">
                          <div>
                            <div class="text-slate-500 text-sm">Duration</div>
                            <div class="font-semibold">{{ round(($submission->duration_minutes ?? 0) / 60, 2) }} Hours</div>
                          </div>
                          <div class="border-l pl-4">
                            <div class="text-slate-500 text-sm">Location</div>
                            <div class="font-semibold">{{ $submission->location ?? '—' }}</div>
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

  {{-- Chart Scripts --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Overall Status Pie Chart
      const overallCtx = document.getElementById('overallChart')?.getContext('2d');
      if (overallCtx) {
        const totalSubmissions = {{ $acceptedCount + $pendingCount + $needRevisionCount + $rejectedCount }};
        
        // Show placeholder if no data
        if (totalSubmissions === 0) {
          new Chart(overallCtx, {
            type: 'pie',
            data: {
              labels: ['No Submissions'],
              datasets: [{
                data: [1],
                backgroundColor: ['#E5E7EB'],
                borderWidth: 0,
              }]
            },
            options: {
              responsive: false,
              plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
              },
              maintainAspectRatio: false,
              layout: { padding: 0 }
            }
          });
        } else {
          new Chart(overallCtx, {
            type: 'pie',
            data: {
              labels: ['Accepted', 'Pending', 'Need Revision', 'Rejected'],
              datasets: [{
                data: [
                  {{ $acceptedCount }}, 
                  {{ $pendingCount }}, 
                  {{ $needRevisionCount }}, 
                  {{ $rejectedCount }}
                ],
                backgroundColor: [
                  '#10B981', // emerald-500 untuk accepted
                  '#3B82F6', // blue-500 untuk pending
                  '#F59E0B', // amber-500 untuk need revision
                  '#EF4444'  // red-500 untuk rejected
                ],
                borderWidth: 0,
                borderRadius: 2,
              }]
            },
            options: {
              responsive: false,
              plugins: {
                legend: { display: false },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      const label = context.label;
                      const value = context.parsed;
                      const total = context.dataset.data.reduce((a, b) => a + b, 0);
                      const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                      return `${label}: ${value} (${percentage}%)`;
                    }
                  }
                }
              },
              maintainAspectRatio: false,
              layout: { padding: 0 }
            }
          });
        }
      }

      // Accepted Progress Donut Chart
      const acceptedCtx = document.getElementById('acceptedChart')?.getContext('2d');
      if (acceptedCtx) {
        new Chart(acceptedCtx, {
          type: 'doughnut',
          data: {
            labels: ['Completed', 'Remaining'],
            datasets: [{
              data: [{{ $acceptedCount }}, {{ max(0, 32 - $acceptedCount) }}],
              backgroundColor: [
                '#10B981', // emerald-500 untuk progress
                '#E5E7EB'  // gray-200 untuk sisa
              ],
              borderWidth: 0,
              cutout: '70%',
            }]
          },
          options: {
            responsive: false,
            plugins: {
              legend: { display: false },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    if (context.dataIndex === 0) {
                      return `Accepted: ${context.parsed} activities`;
                    } else {
                      return `Remaining: ${context.parsed} activities`;
                    }
                  }
                }
              }
            },
            maintainAspectRatio: false,
            layout: { padding: 0 }
          }
        });
      }
    });
  </script>

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

      // Atur awal semester (untuk mendapat 14th Lecture Week pada 28 Nov 2025)
      const ACADEMIC_START = new Date(yearNum, 7, 27);

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