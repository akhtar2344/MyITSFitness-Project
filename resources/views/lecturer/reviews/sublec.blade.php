<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Students | myITS Fitness</title>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html,
    body {
      font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial;
    }
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
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User"
          class="w-9 h-9 rounded-full object-cover cursor-pointer select-none">
        <div id="logoutOverlay" class="hidden absolute right-0 top-[3.25rem] z-50">
          <a href="{{ route('login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout"
              class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="relative">
    <img src="{{ asset('images/back ornament.png') }}"
      class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back ornament.png') }}"
      class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none"
      style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-12 gap-8">
        <!-- Sidebar -->
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            <!-- Dashboard -->
            <a href="{{ route('lecturer.dashboard') }}"
              class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Dashboard</span>
            </a>
            <!-- Submission (active) -->
            <div class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100">
              <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-semibold text-slate-900">Submission</span>
            </div>
            <!-- Students -->
            <a href="{{ route('lecturer.index') }}#"
              class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/student-navigator-page-icon.png') }}" class="h-6 w-auto object-contain" alt="">
              <span class="font-medium">Students</span>
            </a>
          </nav>
        </aside>

        <!-- Content -->
        <section class="col-span-12 md:col-span-9">
          <h1 class="text-4xl font-extrabold tracking-tight">Submission</h1>

          <!-- Filter chips -->
          <div class="mt-5 flex flex-wrap items-center gap-4 text-[15px]">
            @php $tabs = ['All', 'Pending', 'Accepted', 'Need Revision', 'Rejected']; @endphp
            @foreach ($tabs as $t)
              <button
                class="status-tab rounded-full px-5 py-2 bg-white border shadow-sm text-slate-600 hover:bg-slate-50 transition"
                data-status="{{ $t }}">
                {{ $t }}
              </button>
            @endforeach
          </div>

          <!-- Table -->
          <div class="mt-6 rounded-2xl bg-white border shadow-sm overflow-hidden">
            <div class="overflow-y-auto max-h-[600px]">
              <table class="min-w-full">
                <thead class="text-left text-slate-500 text-sm bg-slate-50">
                  <tr>
                    <th class="px-8 py-4 font-semibold">Student</th>
                    <th class="px-8 py-4 font-semibold">Activity</th>
                    <th class="px-8 py-4 font-semibold">Details</th>
                    <th class="px-8 py-4 font-semibold">Submitted</th>
                    <th class="px-8 py-4 font-semibold">Proof</th>
                    <th class="px-8 py-4 font-semibold">Attachment</th>
                  </tr>
                </thead>
                <tbody id="submissionTable" class="text-slate-800">
                  @forelse ($submissions as $i => $submission)
                    <tr
                      class="row-item row-like-find {{ $i % 2 ? 'bg-white' : 'bg-slate-50/40' }} hover:bg-slate-50 transition-colors"
                      data-status="{{ $submission->status }}">
                      <td class="px-8 py-5 align-middle">
                        <a href="{{ route('lecturer.submissions.show', $submission->id) }}" class="block w-fit">
                          <div class="h-grad font-semibold text-slate-900">{{ $submission->student->name ?? 'N/A' }}</div>
                          <div class="h-grad text-slate-500 text-sm">{{ $submission->student->nrp ?? 'N/A' }}</div>
                        </a>
                      </td>
                      <td class="px-8 py-5 align-middle"><span class="h-grad text-slate-700">{{ $submission->activity->name ?? 'N/A' }}</span>
                      </td>
                      <td class="px-8 py-5 align-middle"><span class="h-grad text-slate-700">{{ $submission->duration ?? 'N/A' }} Hours</span>
                      </td>
                      <td class="px-8 py-5 align-middle"><span class="h-grad text-slate-700">{{ $submission->created_at->format('M d, Y') }}</span>
                      </td>

                      <!-- Proof: HANYA FOTO, tanpa frame/border/shadow/oval -->
                      <td class="px-8 py-5 align-middle">
                        @if ($submission->fileAttachments && $submission->fileAttachments->count() > 0)
                          <img src="{{ $submission->fileAttachments->first()->url }}" alt="proof" class="proof-avatar">
                        @else
                          <span class="text-slate-400 text-sm">No proof</span>
                        @endif
                      </td>

                      <td class="px-8 py-5 align-middle">
                        <span class="h-grad text-slate-700">{{ $submission->fileAttachments->first()->file_name ?? 'N/A' }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr class="bg-slate-50">
                      <td colspan="6" class="px-8 py-12 text-center text-slate-500">
                        No submissions found
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

  <script>
    // ====== STATUS CHIP ACTIVE COLORS + FILTER ======
    const tabs = document.querySelectorAll('.status-tab');
    const rows = document.querySelectorAll('.row-item');

    const activeStyles = {
      'All': ['ring-2', 'ring-slate-400', 'text-slate-900'], // hitam
      'Pending': ['ring-2', 'ring-blue-300', 'text-transparent', 'bg-clip-text', 'bg-gradient-to-r', 'from-[#3b82f6]', 'to-[#2563eb]'],
      'Accepted': ['ring-2', 'ring-green-300', 'text-transparent', 'bg-clip-text', 'bg-gradient-to-r', 'from-[#34d399]', 'to-[#10b981]'],
      'Need Revision': ['ring-2', 'ring-amber-300', 'text-transparent', 'bg-clip-text', 'bg-gradient-to-r', 'from-[#fbbf24]', 'to-[#f59e0b]'],
      'Rejected': ['ring-2', 'ring-rose-300', 'text-transparent', 'bg-clip-text', 'bg-gradient-to-r', 'from-[#fb7185]', 'to-[#ef4444]'],
    };

    function clearActive() {
      tabs.forEach(t => {
        t.classList.remove('ring-2', 'ring-slate-400', 'ring-blue-300', 'ring-green-300', 'ring-amber-300', 'ring-rose-300');
        t.classList.remove('text-transparent', 'bg-clip-text', 'bg-gradient-to-r',
          'from-[#5b83ff]', 'to-[#7b61ff]',
          'from-[#34d399]', 'to-[#10b981]',
          'from-[#fbbf24]', 'to-[#f59e0b]',
          'from-[#fb7185]', 'to-[#ef4444]'
        );
        t.classList.remove('text-slate-900');
        t.classList.add('text-slate-600');
      });
    }

    function setActive(status) {
      clearActive();
      const el = Array.from(tabs).find(b => b.dataset.status === status);
      if (!el) return;
      activeStyles[status].forEach(c => el.classList.add(c));
      el.classList.remove('text-slate-600');
    }

    // Get current filter from URL query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const currentFilter = urlParams.get('status') || 'All';
    setActive(currentFilter);

    tabs.forEach(b => b.addEventListener('click', () => {
      const status = b.dataset.status;
      // Navigate with query parameter
      const url = status === 'All' 
        ? window.location.pathname 
        : window.location.pathname + '?status=' + encodeURIComponent(status);
      window.location.href = url;
    }));
  </script>

  <!-- Logout Overlay logic -->
  <script>
    (function () {
      const avatar = document.getElementById('userAvatar');
      const overlay = document.getElementById('logoutOverlay');
      const wrap = document.getElementById('topbarUser');
      if (!avatar || !overlay || !wrap) return;
      avatar.addEventListener('click', function (e) {
        e.stopPropagation();
        overlay.classList.toggle('hidden');
      });
      document.addEventListener('click', function (e) {
        if (overlay.classList.contains('hidden')) return;
        if (!wrap.contains(e.target)) overlay.classList.add('hidden');
      });
      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') overlay.classList.add('hidden');
      });
    })();
  </script>
</body>

</html>