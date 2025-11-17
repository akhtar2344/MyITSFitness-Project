<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit & Resubmit | myITS Fitness</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
    input[type=number] { -moz-appearance:textfield; }
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button { -webkit-appearance: auto; margin: 0; }
    .dropzone:hover { border-color: #60a5fa; background: #f8fbff; }
    .editable-title { outline: none; border-radius: 12px; transition: box-shadow .15s ease, border-color .15s ease, background-color .15s ease; }
    .editable-title:focus { box-shadow: 0 0 0 4px rgba(59,130,246,.15); background: #fff; border-color: #93c5fd !important; }
  </style>
</head>

<body class="bg-[#f7f9fc] text-slate-800">
@php
  $rawActivity = request()->query('activity', 'Running');
  $activity    = ucfirst(trim($rawActivity));
  $isOther     = in_array(strtolower($activity), ['other','others']);
  $iconMap = [
    'Gym'        => 'gym-icon.png',
    'Running'    => 'Running-icon.png',
    'Soccer'     => 'soccer-icon.png',
    'Cycling'    => 'cycling-icon.png',
    'Basketball' => 'basketball-icon.png',
  ];
  $iconFile = $iconMap[$activity] ?? null;
@endphp

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
        <div id="logoutOverlay" class="hidden absolute right-0 top-[2.8rem] z-50">
          <a href="{{ route('student.login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout" class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <div class="pointer-events-none select-none relative">
    <img src="{{ asset('images/back-20ornament.png') }}" class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10" alt="">
    <img src="{{ asset('images/back-20ornament.png') }}" class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10" style="transform:scaleX(-1);" alt="">
  </div>

  <main class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
      <aside class="col-span-12 md:col-span-3">
        <nav class="space-y-3">
          <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm">
            <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
            <span class="font-medium">Home</span>
          </a>
          <a href="{{ route('student.submit') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm">
            <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
            <span class="font-medium">Submit</span>
          </a>
          <a href="{{ route('student.status') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm">
            <img src="{{ asset('images/status-page.png') }}" class="w-6 h-6" alt="">
            <span class="font-medium">Status</span>
          </a>
        </nav>
      </aside>

      <section class="col-span-12 md:col-span-9">
        <a href="{{ route('student.activity.show.needrevision') }}" class="text-slate-500 underline underline-offset-4">⮜ Back to activity details</a>

        @if($isOther)
          <div class="mt-2">
            <input id="activityTitle" type="text" placeholder="Type your activity" class="editable-title w-full text-3xl md:text-[32px] font-semibold tracking-tight border border-slate-200 px-4 py-3 bg-white/70"/>
            <input type="hidden" id="activityHidden" value="">
            <p class="mt-1 text-xs text-slate-500">Tip: Enter a clear, sport-related name.</p>
          </div>
        @else
          <h1 class="mt-2 flex items-center gap-3 text-4xl font-extrabold tracking-tight">
            @if($iconFile)
              <img src="{{ asset('images/'.$iconFile) }}" alt="{{ $activity }} icon" class="w-12 h-12 object-contain select-none">
            @endif
            <span>{{ $activity }}</span>
          </h1>
          <input type="hidden" id="activityHidden" value="{{ $activity }}">
        @endif

        <div class="mt-6 rounded-2xl bg-white border shadow-sm p-6">
          <form id="resubmitForm" action="{{ route('student.activity.resubmit') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-12 gap-6">
            @csrf
            <!-- Left -->
            <div class="col-span-12 lg:col-span-7">
              <label class="block text-sm font-semibold text-slate-600">Date of Occurrence</label>
              <div class="mt-2">
                <input name="date" id="dateField" type="date" class="w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-700"/>
              </div>

              <div class="mt-6 grid grid-cols-12 gap-3">
                <div class="col-span-6">
                  <label class="block text-sm font-semibold text-slate-600">Session Duration</label>
                  <input name="duration" id="durationField" type="number" min="1" step="1" value="1" class="mt-2 w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-700"/>
                </div>
                <div class="col-span-6">
                  <label class="block text-sm font-semibold text-transparent select-none">.</label>
                  <input value="Minutes" disabled class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-500"/>
                </div>
              </div>

              <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-600">Place of Issue</label>
                <input name="place" id="placeField" type="text" placeholder="ex: KONI" class="mt-2 w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-700"/>
              </div>

              <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-600">Certificate or Membership (Optional)</label>
                <label for="certInput" class="dropzone mt-2 flex h-44 w-full cursor-pointer items-center justify-center rounded-xl border-2 border-dashed border-slate-300 text-center">
                  <div class="space-y-1">
                    <p class="text-slate-600 font-semibold">PNG, JPEG, JPG</p>
                    <p class="text-xs text-slate-400">10 MB MAX</p>
                    <p id="certName" class="text-xs text-slate-500"></p>
                  </div>
                </label>
                <input id="certInput" name="certificate" type="file" accept="image/png,image/jpeg" class="hidden">
              </div>
            </div>

            <!-- Right -->
            <div class="col-span-12 lg:col-span-5">
              <label class="block text-sm font-semibold text-slate-600">Activity Proof</label>
              <label for="proofInput" class="dropzone mt-2 flex h-64 w-full cursor-pointer items-center justify-center rounded-xl border-2 border-dashed border-slate-300 text-center">
                <div class="space-y-1">
                  <p class="text-slate-600 font-semibold">PNG, JPEG, JPG</p>
                  <p class="text-xs text-slate-400">10 MB MAX</p>
                  <p id="proofName" class="text-xs text-slate-500"></p>
                </div>
              </label>
              <input id="proofInput" name="proof" type="file" accept="image/png,image/jpeg" class="hidden">
              <p class="mt-2 text-xs italic text-rose-700">Notes: Upload a clear activity photo with the timestamp visible.</p>

              <div class="mt-6 flex justify-end">
                <button id="submitBtn" type="submit" disabled class="rounded-xl bg-[#2367ff] px-6 py-3 text-white font-semibold shadow-sm hover:brightness-110 disabled:opacity-50 disabled:cursor-not-allowed">
                  Re-submit
                </button>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </main>

  <!-- MODAL TENGAH: Submission Complete -->
  <div id="completeModal" class="fixed inset-0 z-50 hidden">
    <div id="completeOverlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-200"></div>
    <div class="absolute inset-0 flex items-center justify-center">
      <div id="completeCard" class="scale-95 opacity-0 transition-all duration-200 ease-out">
        <div class="relative">
          <button id="closeComplete"
                  class="absolute -right-3 -top-3 z-10 w-8 h-8 rounded-full bg-white/90 shadow
                         flex items-center justify-center text-slate-600 hover:bg-white">✕</button>
          <!-- Pakai revision-complete.png -->
          <img src="{{ asset('images/revision-complete.png') }}" alt="Submission Complete"
               class="w-[min(520px,90vw)] h-auto select-none pointer-events-none rounded-2xl shadow-xl">
        </div>
      </div>
    </div>
  </div>

  <script>
    // default date today
    (function () {
      const el = document.getElementById('dateField');
      if (el && !el.value) {
        const now = new Date();
        const m = String(now.getMonth()+1).padStart(2,'0');
        const d = String(now.getDate()).padStart(2,'0');
        el.value = `${now.getFullYear()}-${m}-${d}`;
      }
    })();

    // preview + enable button
    const proofInput = document.getElementById('proofInput');
    const certInput  = document.getElementById('certInput');
    proofInput?.addEventListener('change', e => {
      const f = e.target.files?.[0];
      document.getElementById('proofName').textContent = f ? f.name : '';
      updateSubmitState();
    });
    certInput?.addEventListener('change', e => {
      const f = e.target.files?.[0];
      document.getElementById('certName').textContent = f ? f.name : '';
    });

    const dateEl   = document.getElementById('dateField');
    const durEl    = document.getElementById('durationField');
    const placeEl  = document.getElementById('placeField');
    const submitBtn= document.getElementById('submitBtn');

    function isFilled(v){ return v != null && String(v).trim().length > 0; }
    function updateSubmitState(){
      const okDate = isFilled(dateEl?.value);
      const okDur  = Number(durEl?.value || 0) >= 1;
      const okPlace= isFilled(placeEl?.value);
      const okProof= proofInput?.files && proofInput.files.length > 0;
      submitBtn.disabled = !(okDate && okDur && okPlace && okProof);
    }
    [dateEl, durEl, placeEl].forEach(el => {
      if (!el) return;
      el.addEventListener('input', updateSubmitState);
      el.addEventListener('change', updateSubmitState);
    });
    updateSubmitState();

    // logout overlay toggle
    (function () {
      const avatar  = document.getElementById('userAvatar');
      const overlay = document.getElementById('logoutOverlay');
      const wrap    = document.getElementById('topbarUser');
      if (!avatar || !overlay || !wrap) return;
      avatar.addEventListener('click', (e) => { e.stopPropagation(); overlay.classList.toggle('hidden'); });
      document.addEventListener('click', (e) => { if (!wrap.contains(e.target)) overlay.classList.add('hidden'); });
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape') overlay.classList.add('hidden'); });
    })();

    // --- MODAL: buka saat Re-submit ---
    (function () {
      const form = document.getElementById('resubmitForm');
      const submitBtn = document.getElementById('submitBtn');
      const modal = document.getElementById('completeModal');
      const overlay = document.getElementById('completeOverlay');
      const card = document.getElementById('completeCard');
      const closeBtn = document.getElementById('closeComplete');

      function openModal(){
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
          overlay.classList.add('opacity-100');
          card.classList.remove('opacity-0','scale-95');
          card.classList.add('opacity-100','scale-100');
        });
      }
      function closeModal(){
        overlay.classList.remove('opacity-100');
        card.classList.remove('opacity-100','scale-100');
        card.classList.add('opacity-0','scale-95');
        setTimeout(()=> modal.classList.add('hidden'), 200);
      }

      // tampilkan modal saat submit
      form.addEventListener('submit', (e) => {
        e.preventDefault(); // hapus kalau mau lanjut submit ke server
        openModal();
      });

      // overlay: hanya menutup modal
      overlay.addEventListener('click', closeModal);

      // ❗️X button: tutup lalu redirect ke halaman activity yang sama
      const backLinkEl = [...document.querySelectorAll('a')].find(a =>
        (a.textContent || '').includes('Back to activity details')
      );
      const backHref = backLinkEl?.getAttribute('href') || "{{ route('student.activity.show.needrevision') }}";
      const qs = window.location.search || '';

      closeBtn.addEventListener('click', () => {
        closeModal();
        setTimeout(() => { window.location.href = backHref + qs; }, 220);
      });

      document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeModal(); });
    })();
  </script>
</body>
</html>
