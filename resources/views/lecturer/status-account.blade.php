<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Status | myITS Fitness</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
  </style>
</head>
<body class="bg-[#f7f9fc] text-slate-800">

  {{-- Topbar (match lecturer pages) --}}
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('lecturer.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" class="h-7 w-auto" alt="myITS Fitness">
        </a>
      </div>

      <div id="topbarUser" class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User"
             class="w-9 h-9 rounded-full object-cover cursor-pointer select-none">
        {{-- Logout overlay (same behavior as other lecturer pages) --}}
        <div id="logoutOverlay" class="hidden absolute right-0 top-[3.25rem] z-50">
          <a href="{{ route('lecturer.login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout"
                 class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="relative">
    {{-- Background ornaments (same as others) --}}
    <img src="{{ asset('images/back-ornament.png') }}"
         class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back-ornament.png') }}"
         class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none"
         style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-12 gap-8">

        {{-- Sidebar (match lecturer pages + correct icons) --}}
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            <a href="{{ route('lecturer.dashboard') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Dashboard</span>
            </a>

            {{-- Active: Submission (correct icon) --}}
            <a href="{{ route('lecturer.reviews.sublec') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100">
              <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-semibold text-slate-900">Submission</span>
            </a>

            <a href="{{ route('lecturer.students.index') }}"
               class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/student-navigator-page-icon.png') }}" class="h-6 w-auto object-contain" alt="">
              <span class="font-medium">Students</span>
            </a>
          </nav>
        </aside>

        {{-- Content --}}
        <section class="col-span-12 md:col-span-9">
          <a href="{{ route('lecturer.students.index') }}"
             class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
            <span>Back to submissions</span>
          </a>

          <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Harry Styles</h1>

          <div class="mt-5 bg-white rounded-2xl border shadow-sm overflow-hidden">
            {{-- Profile header --}}
            <div class="px-6 md:px-8 py-6 border-b">
              <div class="flex items-center gap-4">
                <img src="{{ asset('images/icon-user.png') }}" class="w-16 h-16 rounded-full object-cover" alt="Harry Styles">
                <div>
                  <p class="text-xl font-semibold leading-tight">Harry Styles</p>
                  <p class="text-slate-500 text-sm">NRP {{ $nrp ?? '5026231000' }}</p>
                </div>
              </div>
            </div>

            {{-- Activity History --}}
            <div class="px-6 md:px-8 py-6">
              <h2 class="text-lg font-semibold mb-4">Activity History</h2>

              <div class="rounded-2xl border bg-white overflow-hidden">
                <table class="min-w-full">
                  <thead class="bg-slate-50 text-slate-500">
                    <tr>
                      <th class="text-left px-6 py-4 text-sm font-semibold">Name</th>
                      <th class="text-left px-6 py-4 text-sm font-semibold">Activity</th>
                      <th class="text-left px-6 py-4 text-sm font-semibold">Date</th>
                      <th class="text-left px-6 py-4 text-sm font-semibold">Status</th>
                    </tr>
                  </thead>
                  <tbody class="text-slate-800">
                    @php
                      $rows = [
                        ['date'=>'November 2, 2024','status'=>'Pending'],
                        ['date'=>'October 2, 2024','status'=>'Accepted'],
                        ['date'=>'October 1, 2024','status'=>'Need Revision'],
                      ];
                      $badge = [
                        'Pending'       => 'bg-blue-100 text-blue-700',
                        'Accepted'      => 'bg-emerald-100 text-emerald-700',
                        'Need Revision' => 'bg-amber-100 text-amber-700',
                        'Rejected'      => 'bg-rose-100 text-rose-700',
                      ];
                      $nrpFixed = $nrp ?? '5026231000';
                    @endphp

                    @foreach ($rows as $i => $r)
                      <tr
                        class="group {{ $i % 2 ? 'bg-white' : 'bg-slate-50/40' }}
                               hover:bg-slate-50 transition-colors duration-150 cursor-pointer"
                        onclick="window.location='{{ route('lecturer.show') }}'">
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="font-semibold text-slate-900">Harry Styles</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="text-slate-700">{{ $nrpFixed }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="text-slate-700">{{ $r['date'] }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                          <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badge[$r['status']] }}">
                            {{ $r['status'] }}
                          </span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </section>
      </div>
    </div>
  </main>

  {{-- Logout overlay script (same as others) --}}
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
