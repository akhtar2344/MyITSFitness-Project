<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit & Resubmit | myITS Fitness</title>

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Flatpickr (calendar) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
    /* number arrows keep visible */
    input[type=number] { -moz-appearance:textfield; }
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button { -webkit-appearance: auto; margin: 0; }
    /* dropzone hover */
    .dropzone:hover { border-color: #60a5fa; background: #f8fbff; }

    /* editable title look */
    .editable-title { outline: none; border-radius: 12px; transition: box-shadow .15s ease, border-color .15s ease, background-color .15s ease; }
    .editable-title:focus { box-shadow: 0 0 0 4px rgba(59,130,246,.15); background: #fff; border-color: #93c5fd !important; }

    /* Light brand touch for the calendar (tidak mengubah layout form) */
    .flatpickr-calendar .flatpickr-day.selected,
    .flatpickr-calendar .flatpickr-day.startRange,
    .flatpickr-calendar .flatpickr-day.endRange {
      background: linear-gradient(90deg,#5b83ff,#7b61ff);
      border-color: transparent;
      color: #fff;
    }
    .flatpickr-calendar .flatpickr-day.today {
      border-color: #7b61ff;
    }
    .flatpickr-calendar .flatpickr-day:hover {
      background: rgba(123,97,255,0.12);
      border-color: rgba(123,97,255,0.35);
      color: #111827;
    }
  </style>
</head>

<body class="bg-[#f7f9fc] text-slate-800">
@php
  // Get activity from query (?activity=...)
  $rawActivity = request()->query('activity', 'Running');
  $activity    = ucfirst(trim($rawActivity));
  $isOther     = in_array(strtolower($activity), ['other','others']);

  // Map ikon untuk judul (kecuali Other)
  $iconMap = [
    'Gym'        => 'gym-icon.png',
    'Running'    => 'Running-icon.png',
    'Soccer'     => 'soccer-icon.png',
    'Cycling'    => 'cycling-icon.png',
    'Basketball' => 'basketball-icon.png',
  ];
  $iconFile = $iconMap[$activity] ?? null;
@endphp

  <!-- Topbar -->
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
        </a>
      </div>

      <!-- UPDATED USER SECTION WITH LOGOUT OVERLAY -->
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

  <!-- Ornaments -->
  <div class="pointer-events-none select-none relative">
    <img src="{{ asset('images/back-20ornament.png') }}" class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10" alt="">
    <img src="{{ asset('images/back-20ornament.png') }}" class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10" style="transform:scaleX(-1);" alt="">
  </div>

  <main class="max-w-7xl mx-auto px-6 py-8">
    <div class="grid grid-cols-12 gap-8">
      <!-- Sidebar -->
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

      <!-- Content -->
      <section class="col-span-12 md:col-span-9">
        <a href="{{ route('student.submit') }}" class="text-slate-500 underline underline-offset-4">⮜ Back to selecting activity</a>

        {{-- Title --}}
        @if($isOther)
          <div class="mt-2">
            <input
              id="activityTitle"
              type="text"
              placeholder="Type your activity (e.g., Hiking, Badminton, Swimming)"
              class="editable-title w-full text-3xl md:text-[32px] font-semibold tracking-tight border border-slate-200 px-4 py-3 bg-white/70"
              value=""
              aria-label="Custom activity title"
            />
            <input type="hidden" id="activityHidden" value="">
            <p class="mt-1 text-xs text-slate-500">Tip: Enter a clear, sport-related name that best describes your activity.</p>
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
          <form id="resubmitForm" class="grid grid-cols-12 gap-6">
            <!-- Left -->
            <div class="col-span-12 lg:col-span-7">
              <label class="block text-sm font-semibold text-slate-600">Date of Occurrence</label>
              <div class="mt-2">
                <input id="dateField" type="text" placeholder="dd/mm/yyyy"
                       class="w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-700"/>
              </div>

              <div class="mt-6 grid grid-cols-12 gap-3">
                <div class="col-span-6">
                  <label class="block text-sm font-semibold text-slate-600">Session Duration</label>
                  <input id="durationField" type="number" min="1" step="1" value="1" class="mt-2 w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-700"/>
                </div>
                <div class="col-span-6">
                  <label class="block text-sm font-semibold text-transparent select-none">.</label>
                  <input value="Minutes" disabled class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-500"/>
                </div>
              </div>

              <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-600">Place of Issue</label>
                <input id="placeField" type="text" placeholder="ex: KONI" class="mt-2 w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-700"/>
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
                <input id="certInput" type="file" accept="image/png,image/jpeg" class="hidden">
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
              <input id="proofInput" type="file" accept="image/png,image/jpeg" class="hidden">
              <p class="mt-2 text-xs italic text-rose-700">
                Notes: Upload a clear activity photo with the timestamp visible.
              </p>

              <div class="mt-6 flex justify-end">
                <button
                  id="submitBtn"
                  type="button"
                  disabled
                  class="rounded-xl bg-[#2367ff] px-6 py-3 text-white font-semibold shadow-sm hover:brightness-110 active:translate-y-px
                         disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Submit
                </button>
              </div>
            </div>
          </form>
        </div>
      </section>
    </div>
  </main>

  <!-- Centered Modal: Submission Complete -->
  <div id="completeModal" class="fixed inset-0 z-50 hidden">
    <div id="completeOverlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-200"></div>
    <div class="absolute inset-0 flex items-center justify-center">
      <div id="completeCard"
           class="scale-95 opacity-0 transition-all duration-200 ease-out">
        <div class="relative">
          <button id="closeComplete" class="absolute -right-3 -top-3 z-10 w-8 h-8 rounded-full bg-white/90 shadow
                                           flex items-center justify-center text-slate-600 hover:bg-white">
            ✕
          </button>
          <img src="{{ asset('images/Submission Complete Notification.png') }}" alt="Submission Complete"
               class="w-[min(520px,90vw)] h-auto select-none pointer-events-none rounded-2xl shadow-xl">
        </div>
      </div>
    </div>
  </div>

  <!-- Hidden Success Screen -->
  <div id="finalSuccess" class="hidden">
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
          <a href="{{ route('student.submit') }}" class="text-slate-500 underline underline-offset-4">⮜ Back to selecting activity</a>
          <div class="mt-6 rounded-2xl bg-white border shadow-sm flex flex-col items-center justify-center py-24 px-8 text-center min-h-[560px] md:min-h-[640px]">
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#0a1a33] mb-2">
              Activity submitted!
            </h1>
            <p class="text-slate-500 mb-6">Kindly check status page for further confirmation</p>
            <a href="{{ route('student.submit') }}" class="flex items-center gap-2 text-[#0a1a33] font-semibold hover:opacity-80 transition">
              <img src="{{ asset('images/submit-icon.png') }}" class="w-5 h-5" alt="">
              Add Another
            </a>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    // Default date (dd/mm/YYYY)
    (function () {
      const el = document.getElementById('dateField');
      if (el && !el.value) {
        const now = new Date();
        const dd = String(now.getDate()).padStart(2,'0');
        const mm = String(now.getMonth()+1).padStart(2,'0');
        const yyyy = now.getFullYear();
        el.value = `${dd}/${mm}/${yyyy}`;
      }
    })();

    // Proof & Cert preview
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

    // Required fields & button state
    const isOther  = {{ $isOther ? 'true' : 'false' }};
    const actTitle = document.getElementById('activityTitle');
    const actHidden= document.getElementById('activityHidden');
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

      let okActivity = true;
      if (isOther) {
        const val = (actTitle?.value || '').trim();
        actHidden.value = val;
        okActivity = val.length > 0;
      }
      const allOk = okDate && okDur && okPlace && okProof && okActivity;
      submitBtn.disabled = !allOk;
    }

    [dateEl, durEl, placeEl, actTitle].forEach(el => {
      if (!el) return;
      el.addEventListener('input', updateSubmitState);
      el.addEventListener('change', updateSubmitState);
    });
    updateSubmitState();

    // Calendar init: format dd/mm/Y & block future date
    (function(){
      const el = document.getElementById('dateField');
      if(!el) return;
      const initial = el.value || 'today';
      flatpickr(el, {
        dateFormat: "d/m/Y",
        defaultDate: initial,
        altInput: false,
        allowInput: true,
        maxDate: "today",
        position: "below left",
        disableMobile: false
      });
    })();

    // Modal logic
    const completeModal = document.getElementById('completeModal');
    const completeOverlay = document.getElementById('completeOverlay');
    const completeCard = document.getElementById('completeCard');
    const closeComplete = document.getElementById('closeComplete');

    function openComplete(){
      completeModal.classList.remove('hidden');
      requestAnimationFrame(() => {
        completeOverlay.classList.add('opacity-100');
        completeCard.classList.remove('opacity-0','scale-95');
        completeCard.classList.add('opacity-100','scale-100');
      });
    }
    function closeCompleteFn(){
      completeOverlay.classList.remove('opacity-100');
      completeCard.classList.remove('opacity-100','scale-100');
      completeCard.classList.add('opacity-0','scale-95');
      setTimeout(()=> completeModal.classList.add('hidden'), 200);
    }
    completeOverlay.addEventListener('click', closeCompleteFn);
    closeComplete.addEventListener('click', closeCompleteFn);
    document.addEventListener('keydown', (e)=>{ if(e.key==='Escape') closeCompleteFn(); });
    submitBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (submitBtn.disabled) return;
      openComplete();
    });
  </script>

  <!-- Redirect to success -->
  <script>
    (function(){
      const mainSection  = document.querySelector('main');
      const successBlock = document.getElementById('finalSuccess');
      const closeBtn     = document.getElementById('closeComplete');
      const overlayEl    = document.getElementById('completeOverlay');
      function showFinalSuccess(){
        if (mainSection) mainSection.classList.add('hidden');
        if (successBlock) successBlock.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      closeBtn?.addEventListener('click', () => { setTimeout(showFinalSuccess, 220); });
      overlayEl?.addEventListener('click', () => { setTimeout(showFinalSuccess, 220); });
    })();
  </script>

  <!-- Added: Logout overlay toggle -->
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
