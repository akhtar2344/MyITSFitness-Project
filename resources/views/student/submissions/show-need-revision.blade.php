<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submission Detail (Need Revision) | myITS Fitness</title>

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
          <a href="{{ route('login') }}" class="block cursor-pointer">
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

          <h1 class="text-4xl font-extrabold tracking-tight">{{ $activity->name ?? 'Activity' }}</h1>

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
                      <div class="text-xl font-semibold">{{ $student->name ?? 'Unknown' }}</div>
                      <div class="text-slate-500 text-sm">NRP {{ $student->nrp ?? '—' }}</div>
                    </div>
                  </div>

                  <div class="flex items-center gap-3">
                    <span class="inline-flex items-center h-10 px-4 rounded-2xl text-white font-semibold bg-amber-500">
                      {{ $submission->status }}
                    </span>
                    <a href="{{ route('student.submissions.resubmit', $submission->id) }}"
                       class="h-10 px-5 rounded-lg border border-indigo-400 text-indigo-600 font-semibold bg-white
                              hover:bg-indigo-50 hover:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200
                              active:translate-y-px transition-colors inline-flex items-center">
                      Resubmit
                    </a>
                  </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-y-3 text-sm md:text-base">
                  <div class="text-slate-500">Submitted:</div>
                  <div class="font-medium">{{ $submission->created_at->format('F j, Y') }}</div>
                  
                  <div class="text-slate-500">Activity:</div>
                  <div class="font-medium">{{ $activity->name ?? '—' }}</div>
                  
                  <div class="text-slate-500">Location:</div>
                  <div class="font-medium">{{ $activity->location ?? '—' }}</div>
                  
                  <div class="text-slate-500">Duration:</div>
                  <div class="font-medium">{{ round(($submission->duration_minutes ?? 0) / 60, 2) }} Hours</div>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <div class="font-semibold mb-3">Proof</div>
                    <img src="{{ $proofImage }}"
                         alt="Proof"
                         class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl object-cover border shadow-sm" />
                  </div>
                  <div>
                    <div class="font-semibold mb-3">Attachment</div>
                    @if($certificateFile)
                      <div class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl border bg-slate-100/70 flex items-center justify-center">
                        <a href="{{ $certificateFile->url }}" target="_blank" class="text-center">
                          <div class="text-6xl mb-2">📄</div>
                          <div class="text-sm font-medium text-slate-600">{{ $certificateFile->file_name }}</div>
                        </a>
                      </div>
                    @else
                      <div class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl border bg-slate-100/70"></div>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            {{-- Right: Revision requests & comments --}}
            <aside class="col-span-12 xl:col-span-4 rounded-2xl bg-white border shadow-sm p-6 h-full flex flex-col">
              @if($revisionRequests->count() > 0)
                <div class="flex items-center gap-2 font-semibold text-amber-600 mb-4">
                  <span class="text-lg">⚠️</span>
                  <span>Revision Requests</span>
                </div>

                <div class="mb-4 space-y-3">
                  @foreach($revisionRequests as $req)
                    <div class="rounded-lg bg-amber-50 p-3 border border-amber-200 text-sm">
                      <div class="font-semibold text-amber-900">{{ $req->lecturer?->user?->name ?? 'Lecturer' }}</div>
                      <div class="text-amber-800 mt-1">{{ $req->reason ?? $req->message ?? 'Revision requested' }}</div>
                      <div class="text-xs text-amber-600 mt-2">{{ $req->created_at->diffForHumans() }}</div>
                    </div>
                  @endforeach
                </div>

                <hr class="my-4">
              @endif

              <div class="flex items-center gap-2 font-semibold">
                <img src="{{ asset('images/Private comment-icon.png') }}" class="w-5 h-5 object-contain shrink-0" alt="">
                <span>Comments</span>
              </div>

              <div class="mt-4 flex-1 overflow-y-auto space-y-4 pr-1">
                @forelse($comments as $comment)
                  <div class="rounded-lg bg-slate-50 p-3 text-sm">
                    <div class="font-semibold text-slate-700">{{ $comment->commentUser->name ?? 'Unknown' }}</div>
                    <div class="text-slate-600 mt-1">{{ $comment->content ?? $comment->body ?? '—' }}</div>
                    <div class="text-xs text-slate-400 mt-2">{{ $comment->created_at->diffForHumans() }}</div>
                  </div>
                @empty
                  <div class="text-center py-6 text-slate-400 text-sm">No comments yet</div>
                @endforelse
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

  {{-- Toast --}}
  <div id="toast" class="hidden fixed bottom-6 right-6 z-[110] px-4 py-3 rounded-lg bg-slate-900 text-white shadow-lg">
    <span id="toastText" class="text-sm font-medium">Comment sent successfully.</span>
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
