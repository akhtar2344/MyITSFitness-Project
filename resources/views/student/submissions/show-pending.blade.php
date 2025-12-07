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
                {{-- Header row --}}
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
                  <div class="text-slate-500">Submitted:</div><div class="font-medium">{{ $submission->created_at->format('F j, Y') }}</div>
                  <div class="text-slate-500">Activity:</div><div class="font-medium">{{ $activity->name ?? '—' }}</div>
                  <div class="text-slate-500">Location:</div><div class="font-medium">{{ $submission->location ?? '—' }}</div>
                  <div class="text-slate-500">Duration:</div><div class="font-medium">{{ round(($submission->duration_minutes ?? 0) / 60, 2) }} Hours</div>
                </div>

                <hr class="my-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <div class="font-semibold mb-3">Proof</div>
                    <img src="{{ $proofImage ?? asset('images/harrystyles-proof3.png') }}"
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
                @forelse($comments ?? [] as $comment)
                  <div class="flex items-start gap-3 group" data-comment-id="{{ $comment->id }}">
                    <img
                      src="{{ asset('images/icon-user.png') }}"
                      alt="User"
                      class="w-9 h-9 rounded-full object-cover border shadow-sm shrink-0"
                    />
                    <div class="flex-1">
                      <div class="flex items-center gap-2 justify-between">
                        <div>
                          <div class="font-medium">{{ $comment->student->name ?? $comment->lecturer->name ?? 'Unknown' }}</div>
                          <div class="text-slate-400 text-xs">{{ $comment->created_at->format('M j, Y \a\t g:i A') }}</div>
                        </div>
                        <button class="delete-comment-btn text-slate-400 hover:text-red-500 transition-colors text-xl leading-none flex-shrink-0" 
                                data-comment-id="{{ $comment->id }}" title="Delete comment">
                          ⋮
                        </button>
                      </div>
                      <div class="text-slate-600 text-sm mt-1">{{ $comment->content ?? $comment->body ?? '—' }}</div>
                    </div>
                  </div>
                @empty
                  <div class="text-center text-slate-400 text-sm py-8">No comments yet</div>
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

  {{-- FEATURE: Cancel submission confirmation modal with backdrop and centered design --}}
  <div id="cancelModal" class="hidden fixed inset-0 z-[100]">
    <!-- FEATURE: Semi-transparent backdrop overlay -->
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative h-full w-full flex items-center justify-center p-4">
      <!-- FEATURE: Modal content container with rounded design and shadow -->
      <div class="w-[540px] max-w-full rounded-2xl bg-white shadow-2xl">
        <form id="cancelForm" method="POST" action="{{ isset($submission) ? route('student.submissions.cancel', $submission->id) : '#' }}" class="p-6 text-center">
          @csrf
          @method('POST')
          <h3 class="text-[22px] font-bold text-[#3b6bff] mb-4">Cancel this submission?</h3>
          <p class="text-slate-700 mb-6">Your current progress will not be saved.</p>

          <!-- FEATURE: Two-column action buttons with visual separation -->
          <div class="grid grid-cols-2 divide-x">
            <button type="submit" id="confirmYes" class="py-3 font-semibold text-[#3b6bff] hover:bg-slate-50 transition">Yes, Cancel</button>
            <button type="button" id="confirmNo" class="py-3 font-semibold text-slate-900 hover:bg-slate-50 transition">No, Keep it</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- FEATURE: Toast notification system for user feedback --}}
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

    // FEATURE: Cancel submission modal system with AJAX submission
    (function () {
      const openBtn  = document.getElementById('cancelBtn');
      const modal    = document.getElementById('cancelModal');
      const yesBtn   = document.getElementById('confirmYes');
      const noBtn    = document.getElementById('confirmNo');
      const toast    = document.getElementById('toast');
      const toastTxt = document.getElementById('toastText');
      const cancelForm = document.getElementById('cancelForm');

      // FEATURE: Modal visibility toggle functions
      function openModal(){ modal.classList.remove('hidden'); }
      function closeModal(){ modal.classList.add('hidden'); }

      // FEATURE: Toast notification system with success/error styling
      function showToast(msg, type = 'success') {
        toastTxt.textContent = msg;
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white font-medium shadow-lg z-50 ${
          type === 'success' ? 'bg-emerald-500' : 'bg-red-500'
        }`;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
      }

      openBtn?.addEventListener('click', openModal);

      // FEATURE: Cancel action - close modal without submission
      noBtn?.addEventListener('click', () => {
        closeModal();
      });

      // FEATURE: Confirm action - AJAX POST with CSRF protection
      yesBtn?.addEventListener('click', async () => {
        closeModal();
        yesBtn.disabled = true;
        yesBtn.textContent = 'Canceling...';

        try {
          const response = await fetch(cancelForm.action, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
          });

          const data = await response.json();

          if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => {
              window.location.href = '{{ route("student.dashboard") }}';
            }, 1500);
          } else {
            showToast(data.message || 'Error canceling submission', 'error');
            yesBtn.disabled = false;
            yesBtn.textContent = 'Yes, Cancel';
          }
        } catch (error) {
          showToast('Network error: ' + error.message, 'error');
          yesBtn.disabled = false;
          yesBtn.textContent = 'Yes, Cancel';
        }
      });

      // FEATURE: Backdrop click and ESC key modal closing functionality
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
      const commentsContainer = document.querySelector('.mt-4.flex-1.overflow-y-auto.space-y-4');

      function updateState() { btn.disabled = !input.value.trim(); }

      input.addEventListener('input', updateState);
      form.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!input.value.trim()) return;
        
        const comment = input.value.trim();
        btn.classList.add('scale-95');
        btn.disabled = true;

        try {
          const response = await fetch('{{ route("student.submissions.comment", $submission->id) }}', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
              'Accept': 'application/json',
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({ content: comment })
          });

          const data = await response.json();

          if (data.success) {
            // Remove "No comments yet" message if exists
            const noComments = commentsContainer.querySelector('.text-center.text-slate-400');
            if (noComments) noComments.remove();

            // Add new comment to container (use server-formatted date)
            const commentHTML = `
              <div class="flex items-start gap-3 group" data-comment-id="${data.comment.id}">
                <img
                  src="{{ asset('images/icon-user.png') }}"
                  alt="User"
                  class="w-9 h-9 rounded-full object-cover border shadow-sm shrink-0"
                />
                <div class="flex-1">
                  <div class="flex items-center gap-2 justify-between">
                    <div>
                      <div class="font-medium">${data.comment.name}</div>
                      <div class="text-slate-400 text-xs">${data.comment.created_at}</div>
                    </div>
                    <button class="delete-comment-btn text-slate-400 hover:text-red-500 transition-colors text-xl leading-none flex-shrink-0" 
                            data-comment-id="${data.comment.id}" title="Delete comment">
                      ⋮
                    </button>
                  </div>
                  <div class="text-slate-600 text-sm mt-1">${data.comment.content}</div>
                </div>
              </div>
            `;
            commentsContainer.insertAdjacentHTML('beforeend', commentHTML);
            
            input.value = '';
            updateState();
          } else {
            alert('Error: ' + (data.message || 'Failed to add comment'));
          }
        } catch (error) {
          alert('Network error: ' + error.message);
        } finally {
          btn.classList.remove('scale-95');
          btn.disabled = false;
        }
      });

      // Handle delete comment
      commentsContainer.addEventListener('click', async (e) => {
        if (!e.target.classList.contains('delete-comment-btn')) return;
        
        const commentId = e.target.dataset.commentId;
        if (!commentId) return;

        // Custom confirmation dialog
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        modal.innerHTML = `
          <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-4">
            <h3 class="text-lg font-semibold mb-4">Delete this comment?</h3>
            <div class="flex gap-3 justify-end">
              <button class="cancel-btn px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 font-medium">Cancel</button>
              <button class="confirm-btn px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 font-medium">Delete</button>
            </div>
          </div>
        `;
        document.body.appendChild(modal);

        let confirmed = false;

        modal.querySelector('.cancel-btn').addEventListener('click', () => {
          modal.remove();
        });

        modal.querySelector('.confirm-btn').addEventListener('click', async () => {
          confirmed = true;
          modal.remove();

          try {
            const response = await fetch('{{ route("student.comments.delete", ":id") }}'.replace(':id', commentId), {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
              }
            });

            const data = await response.json();

            if (data.success) {
              // Remove comment from DOM
              const commentEl = commentsContainer.querySelector(`[data-comment-id="${commentId}"]`);
              if (commentEl) {
                commentEl.remove();
                
                // Show "No comments yet" if no comments remain
                if (commentsContainer.children.length === 0) {
                  commentsContainer.innerHTML = '<div class="text-center text-slate-400 text-sm py-8">No comments yet</div>';
                }
              }
            } else {
              alert('Error: ' + (data.message || 'Failed to delete comment'));
            }
          } catch (error) {
            alert('Network error: ' + error.message);
          }
        });
      });

      updateState();
    })();
  </script>
</body>
</html>
