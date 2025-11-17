<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Activity Detail (Pending) | myITS Fitness</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Poppins --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
    .send-btn { transition: all .2s ease; }
    .send-btn:disabled { opacity: .5; cursor: not-allowed; }
    .send-btn:not(:disabled):hover { filter: brightness(1.1); transform: translateY(-1px); }
    .send-btn:not(:disabled):active { filter: brightness(.95); transform: translateY(1px); }
  </style>
</head>

<body class="bg-[#f3f6fb] text-slate-800">
  {{-- Topbar --}}
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('student.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
        </a>
      </div>

      {{-- user + logout overlay --}}
      <div id="topbarUser" class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User"
             class="w-9 h-9 rounded-full object-cover cursor-pointer">

        <div id="logoutOverlay" class="hidden absolute right-0 top-[2.9rem] z-50">
          <a href="{{ route('student.login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout"
                 class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
          </a>
        </div>
      </div>
    </div>
  </header>

  <main class="relative">
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed left-4 top-40 h-[460px] opacity-10 select-none pointer-events-none" alt="">
    <img src="{{ asset('images/back-ornament.png') }}" class="hidden lg:block fixed right-4 top-56 h-[460px] opacity-10 select-none pointer-events-none" style="transform:scaleX(-1);" alt="">

    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="grid grid-cols-12 gap-8">
        {{-- Sidebar --}}
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Home</span>
            </a>
            <a href="{{ route('student.submit') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Submit</span>
            </a>
            <a href="{{ route('student.status') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/status-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Status</span>
            </a>
          </nav>
        </aside>

        {{-- Content --}}
        <section class="col-span-12 md:col-span-9">
          <button onclick="history.back()" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 mb-2">
            <span class="text-lg">‹</span> <span class="text-sm">Back</span>
          </button>

          <h1 class="text-4xl font-extrabold tracking-tight">Harry Styles</h1>

          <div class="mt-4 grid grid-cols-12 gap-6">
            {{-- Left card --}}
            <div class="col-span-12 xl:col-span-8 rounded-2xl bg-white border shadow-sm overflow-hidden">
              <div class="p-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <img src="{{ asset('images/icon-user.png') }}"
                         alt="Student Avatar"
                         class="w-16 h-16 rounded-full object-cover border shadow-sm select-none">
                    <div>
                      <div class="text-xl font-semibold">Harry Styles</div>
                      <div class="text-slate-500 text-sm">NRP 5026231000</div>
                    </div>
                  </div>

                  <div class="flex items-center gap-3">
                    <span class="inline-flex items-center h-10 px-4 rounded-2xl text-white font-semibold
                                  bg-gradient-to-r from-[#5b83ff] to-[#7b61ff] select-none">
                      Pending
                    </span>

                    <button type="button"
                            id="cancelBtn"
                            class="h-10 px-5 rounded-lg border border-rose-400 text-rose-500 font-semibold bg-white
                                   hover:bg-rose-50 hover:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-200
                                   active:translate-y-px transition-colors">
                      Cancel Submission
                    </button>
                  </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-y-3 text-sm md:text-base">
                  <div class="text-slate-500">Submitted:</div><div class="font-medium">November 2, 2024</div>
                  <div class="text-slate-500">Activity:</div><div class="font-medium">Running</div>
                  <div class="text-slate-500">Location:</div><div class="font-medium">KONI</div>
                  <div class="text-slate-500">Duration:</div><div class="font-medium">1,5 Hours</div>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <div class="font-semibold mb-3">Proof</div>
                    <img src="{{ asset('images/harrystyles-proof3.png') }}"
                         alt="Proof"
                         class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl object-cover border shadow-sm" />
                  </div>
                  <div>
                    <div class="font-semibold mb-3">Attachment</div>
                    <div class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl border bg-slate-100/70"></div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Right: Private comments --}}
            <aside class="col-span-12 xl:col-span-4 rounded-2xl bg-white border shadow-sm p-6 h-full flex flex-col">
              <div class="flex items-center gap-2 font-semibold">
                <img src="{{ asset('images/Private comment-icon.png') }}" class="w-5 h-5 object-contain shrink-0" alt="">
                <span>Private Comments</span>
              </div>

              <div class="mt-4 flex-1 overflow-y-auto space-y-4 pr-1">
                {{-- kosong --}}
              </div>

              <div class="pt-4 mt-4 border-t">
                <form id="commentForm" class="flex items-center gap-3">
                  <input id="commentInput" type="text" placeholder="Type your comments"
                         class="flex-1 min-w-0 rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
                  <button id="sendBtn" type="submit"
                          class="send-btn px-4 py-2 rounded-lg bg-[#7b61ff] text-white font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Send
                  </button>
                </form>
              </div>
            </aside>
          </div>
        </section>
      </div>
    </div>
  </main>

  {{-- Modal: Cancel Submission --}}
  <div id="cancelModal" class="hidden fixed inset-0 z-[100]">
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative h-full w-full flex items-center justify-center p-4">
      <div class="w-[540px] max-w-full rounded-2xl bg-white shadow-2xl">
        <div class="p-6 text-center">
          <h3 class="text-[22px] font-bold text-[#3b6bff] mb-4">Cancel this submission?</h3>
          <p class="text-slate-700 mb-6">Your current progress will not be saved.</p>

          <div class="grid grid-cols-2 divide-x">
            <button id="confirmYes" class="py-3 font-semibold text-[#3b6bff] hover:bg-slate-50 transition">Yes</button>
            <button id="confirmNo" class="py-3 font-semibold text-slate-900 hover:bg-slate-50 transition">No</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Toast --}}
  <div id="toast" class="hidden fixed bottom-6 right-6 z-[110] px-4 py-3 rounded-lg bg-slate-900 text-white shadow-lg">
    <span id="toastText" class="text-sm font-medium">Activity has been discarded.</span>
  </div>

  <script>
    // Avatar overlay
    (function () {
      const avatar  = document.getElementById('userAvatar');
      const overlay = document.getElementById('logoutOverlay');
      const wrap    = document.getElementById('topbarUser');
      if (!avatar || !overlay || !wrap) return;

      avatar.addEventListener('click', function(e){
        e.stopPropagation();
        overlay.classList.toggle('hidden');
      });

      document.addEventListener('click', function(e){
        if (overlay.classList.contains('hidden')) return;
        if (!wrap.contains(e.target)) overlay.classList.add('hidden');
      });

      document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') overlay.classList.add('hidden');
      });
    })();

    // Cancel modal logic
    (function () {
      const openBtn  = document.getElementById('cancelBtn');
      const modal    = document.getElementById('cancelModal');
      const yesBtn   = document.getElementById('confirmYes');
      const noBtn    = document.getElementById('confirmNo');
      const toast    = document.getElementById('toast');
      const toastTxt = document.getElementById('toastText');

      function openModal(){ modal.classList.remove('hidden'); }
      function closeModal(){ modal.classList.add('hidden'); }

      openBtn?.addEventListener('click', openModal);

      // No → back
      noBtn?.addEventListener('click', () => {
        closeModal();
        history.back();
      });

      // Yes → show toast then redirect
      yesBtn?.addEventListener('click', () => {
        closeModal();
        // show toast
        toastTxt.textContent = 'Activity has been discarded.';
        toast.classList.remove('hidden');
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(8px)';
        requestAnimationFrame(() => {
          toast.style.transition = 'all .25s ease';
          toast.style.opacity = '1';
          toast.style.transform = 'translateY(0)';
        });

        // Redirect after short delay (ubah rute jika beda)
        setTimeout(() => {
          window.location.href = "{{ route('student.status') }}";
        }, 900);
      });

      // close on backdrop / Esc
      modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
      });
      document.addEventListener('keydown', (e) => {
        if (!modal.classList.contains('hidden') && e.key === 'Escape') closeModal();
      });
    })();

    // Interaktif Send Button
    (function () {
      const input = document.getElementById('commentInput');
      const btn = document.getElementById('sendBtn');
      const form = document.getElementById('commentForm');

      function updateState() { btn.disabled = !input.value.trim(); }

      input.addEventListener('input', updateState);
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!input.value.trim()) return;
        btn.classList.add('scale-95');
        setTimeout(() => btn.classList.remove('scale-95'), 150);
        input.value = '';
        updateState();
      });

      updateState();
    })();
  </script>
</body>
</html>
