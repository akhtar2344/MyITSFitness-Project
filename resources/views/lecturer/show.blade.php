<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submission Detail | myITS Fitness</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial; }
    .send-btn { transition: all .2s ease; }
    .send-btn:disabled { opacity:.5; cursor:not-allowed; }
    .send-btn:not(:disabled):hover { filter:brightness(1.1); transform:translateY(-1px); }
    .send-btn:not(:disabled):active { filter:brightness(.95); transform:translateY(1px); }
  </style>
</head>
<body class="bg-[#f3f6fb] text-slate-800">
  <!-- Topbar -->
  <header class="h-16 bg-white border-b">
    <div class="max-w-7xl mx-auto h-full px-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('lecturer.dashboard') }}">
          <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-7 w-auto">
        </a>
      </div>
      <div id="topbarUser" class="flex items-center gap-4 relative">
        <button class="text-sm text-slate-600">EN ▾</button>
        <img id="userAvatar" src="{{ asset('images/icon-user.png') }}" alt="User" class="w-9 h-9 rounded-full object-cover cursor-pointer">
        <div id="logoutOverlay" class="hidden absolute right-0 top-[2.9rem] z-50">
          <a href="{{ route('login') }}" class="block cursor-pointer">
            <img src="{{ asset('images/logout-overlay.png') }}" alt="Logout" class="block w-[130px] max-w-none h-auto drop-shadow-xl transition-transform duration-200 hover:scale-105">
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
        <!-- Sidebar -->
        <aside class="col-span-12 md:col-span-3">
          <nav class="space-y-3">
            <a href="{{ route('lecturer.dashboard') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/icon-home.png') }}" class="w-6 h-6" alt="">
              <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('lecturer.reviews.sublec') }}" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm ring-1 ring-blue-100">
              <img src="{{ asset('images/submission-page.png') }}" class="w-6 h-6" alt="">
              <span class="font-semibold text-slate-900">Submission</span>
            </a>
            <a href="{{ route('lecturer.index') }}#" class="flex items-center gap-3 rounded-xl border bg-white px-4 py-3 shadow-sm hover:bg-slate-50">
              <img src="{{ asset('images/student-navigator-page-icon.png') }}" class="h-6 w-auto object-contain" alt="">
              <span class="font-medium">Students</span>
            </a>
          </nav>
        </aside>

        <!-- Content -->
        <section class="col-span-12 md:col-span-9">
          <button onclick="history.back()" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 mb-2">
            <span class="text-lg">‹</span> <span class="text-sm">Back to submissions</span>
          </button>

          <h1 class="text-4xl font-extrabold tracking-tight">{{ $submission->student->name ?? 'Student' }}</h1>

          <div class="mt-4 grid grid-cols-12 gap-6">
            <!-- Left card -->
            <div class="col-span-12 xl:col-span-8 rounded-2xl bg-white border shadow-sm overflow-hidden">
              <div class="p-6">
                <!-- Header -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                  <div class="flex items-center gap-4">
                    <img src="{{ asset('images/icon-user.png') }}"
                         alt="Student Avatar"
                         class="w-16 h-16 rounded-full object-cover border shadow-sm select-none">
                    <div>
                      <div class="text-xl font-semibold">{{ $submission->student->name ?? 'N/A' }}</div>
                      <div class="text-slate-500 text-sm">NRP {{ $submission->student->nrp ?? 'N/A' }}</div>
                    </div>
                  </div>

                  <!-- ACTION BUTTONS: Always visible, disabled when locked -->
                  @php
                    $isLocked = in_array($submission->status, ['Accepted', 'Rejected', 'NeedRevision']);
                  @endphp
                  <div class="flex items-center gap-2.5" id="actionButtons">
                    <!-- Accept: GREEN solid -->
                    <form method="POST" action="{{ route('lecturer.reviews.accept', $submission->id) }}" style="display:inline;" id="acceptForm">
                      @csrf
                      <button type="button" id="acceptBtn" class="grading-btn inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-emerald-500 text-white font-semibold shadow-sm hover:bg-emerald-600 active:translate-y-px transition-all duration-200 {{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isLocked ? 'disabled' : '' }}>
                        Accept
                      </button>
                    </form>
                    <!-- Reject: RED solid -->
                    <form method="POST" action="{{ route('lecturer.reviews.reject', $submission->id) }}" style="display:inline;" id="rejectForm">
                      @csrf
                      <button type="button" id="rejectBtn" class="grading-btn inline-flex items-center justify-center h-11 px-6 rounded-2xl bg-rose-500 text-white font-semibold shadow-sm hover:bg-rose-600 active:translate-y-px transition-all duration-200 {{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isLocked ? 'disabled' : '' }}>
                        Reject
                      </button>
                    </form>
                    <!-- Request Revision: ORANGE outline -->
                    <form method="POST" action="{{ route('lecturer.reviews.requestRevision', $submission->id) }}" style="display:inline;" id="revisionForm">
                      @csrf
                      <button type="button" id="revisionBtn" class="grading-btn inline-flex items-center justify-center h-11 px-6 rounded-2xl border-2 border-amber-400 text-amber-600 font-semibold bg-white hover:bg-amber-50 active:translate-y-px transition-all duration-200 {{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $isLocked ? 'disabled' : '' }}>
                        Request Revision
                      </button>
                    </form>
                  </div>

                  <!-- STATUS BADGE: Display current status for reference -->
                  <div class="mt-4 inline-flex items-center gap-2">
                    <span class="text-slate-500 text-sm">Current status:</span>
                    @if ($submission->status === 'Accepted')
                      <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-sm">✓ Accepted</span>
                    @elseif ($submission->status === 'Rejected')
                      <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-100 text-rose-700 font-semibold text-sm">✕ Rejected</span>
                    @elseif ($submission->status === 'NeedRevision')
                      <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-semibold text-sm">⟲ Need Revision</span>
                    @else
                      <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">⏳ Pending</span>
                    @endif
                  </div>
                </div>

                <!-- Meta -->
                <div class="mt-6 grid grid-cols-2 gap-y-3 text-sm md:text-base">
                  <div class="text-slate-500">Submitted:</div><div class="font-medium">{{ $submission->created_at->format('M d, Y') }}</div>
                  <div class="text-slate-500">Activity:</div><div class="font-medium">{{ $submission->activity->name ?? 'N/A' }}</div>
                  <div class="text-slate-500">Location:</div><div class="font-medium">{{ $submission->activity->location ?? 'N/A' }}</div>
                  <div class="text-slate-500">Duration:</div><div class="font-medium">{{ round(($submission->duration_minutes ?? 0) / 60, 2) }} Hours</div>
                </div>

                <hr class="my-6">

                <!-- Proof & Attachment -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <div class="font-semibold mb-3">Proof</div>
                    @if ($submission->fileAttachments && $submission->fileAttachments->count() > 0)
                      <img
                        src="{{ $submission->fileAttachments->first()->display_url }}"
                        alt="Proof"
                        class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl object-cover border shadow-sm"
                        onerror="this.src='{{ asset('images/icon-user.png') }}'; this.style.background='#f1f5f9';"
                      />
                    @else
                      <div class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl border bg-slate-100/70 flex items-center justify-center text-slate-500">
                        No proof uploaded
                      </div>
                    @endif
                  </div>
                  <div>
                    <div class="font-semibold mb-3">Attachment</div>
                    <div class="max-w-sm w-full h-44 md:h-48 lg:h-52 rounded-xl border bg-slate-100/70"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right: Private comments -->
            <aside class="col-span-12 xl:col-span-4 rounded-2xl bg-white border shadow-sm p-6 h-full flex flex-col">
              <div class="flex items-center gap-2 font-semibold">
                <img src="{{ asset('images/Private comment-icon.png') }}" class="w-5 h-5 object-contain shrink-0" alt="">
                <span>Private Comments</span>
              </div>

              <div class="mt-4 flex-1 overflow-y-auto space-y-4 pr-1">
                @forelse ($comments as $comment)
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
                  @csrf
                  <input
                    id="commentInput"
                    type="text"
                    placeholder="Type your comment"
                    class="flex-1 min-w-0 rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-100"
                  >
                  <button
                    id="sendBtn"
                    type="submit"
                    class="send-btn px-4 py-2 rounded-lg bg-[#7b61ff] text-white font-medium shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Send
                  </button>
                </form>
              </div>
            </aside>
          </div>
        </section>
      </div>
    </div>

    <!-- Custom Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 z-50 hidden">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-black/40"></div>
      
      <!-- Modal Content -->
      <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-auto">
          <!-- Header -->
          <div class="px-6 py-4 border-b border-slate-100">
            <div class="text-center">
              <span class="font-semibold text-slate-800">Confirmation</span>
            </div>
          </div>
          
          <!-- Body -->
          <div class="px-6 py-6">
            <p id="confirmationMessage" class="text-slate-700 text-center">Are you sure you want to proceed?</p>
          </div>
          
          <!-- Footer -->
          <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex items-center justify-end gap-3">
            <button id="cancelBtn" class="px-4 py-2 text-slate-600 hover:text-slate-800 font-medium transition-colors">
              Cancel
            </button>
            <button id="confirmBtn" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors">
              Confirm
            </button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Logout Overlay logic + send button interactions -->
  <script>
    (function () {
      const avatar  = document.getElementById('userAvatar');
      const overlay = document.getElementById('logoutOverlay');
      const wrap    = document.getElementById('topbarUser');
      if (!avatar || !overlay || !wrap) return;
      avatar.addEventListener('click', function(e) {
        e.stopPropagation();
        overlay.classList.toggle('hidden');
      });
      document.addEventListener('click', function(e) {
        if (overlay.classList.contains('hidden')) return;
        if (!wrap.contains(e.target)) overlay.classList.add('hidden');
      });
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') overlay.classList.add('hidden');
      });
    })();

    (function () {
      const input = document.getElementById('commentInput');
      const btn = document.getElementById('sendBtn');
      const form = document.getElementById('commentForm');
      const commentsContainer = document.querySelector('.mt-4.flex-1.overflow-y-auto.space-y-4.pr-1');

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

        modal.querySelector('.cancel-btn').addEventListener('click', () => {
          modal.remove();
        });

        modal.querySelector('.confirm-btn').addEventListener('click', async () => {
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

    // Grading buttons confirmation and greyed out effect
    (function() {
      const acceptBtn = document.getElementById('acceptBtn');
      const rejectBtn = document.getElementById('rejectBtn');
      const revisionBtn = document.getElementById('revisionBtn');
      const acceptForm = document.getElementById('acceptForm');
      const rejectForm = document.getElementById('rejectForm');
      const revisionForm = document.getElementById('revisionForm');
      
      // Modal elements
      const modal = document.getElementById('confirmationModal');
      const confirmMessage = document.getElementById('confirmationMessage');
      const confirmBtn = document.getElementById('confirmBtn');
      const cancelBtn = document.getElementById('cancelBtn');
      
      let currentAction = null;
      let currentForm = null;
      
      // Greyed out styles
      const greyedOutClasses = [
        'opacity-50', 'cursor-not-allowed', 'pointer-events-none'
      ];
      
      function greyOutOthers(selectedBtn) {
        const allBtns = [acceptBtn, rejectBtn, revisionBtn];
        allBtns.forEach(btn => {
          if (btn !== selectedBtn) {
            btn.classList.add(...greyedOutClasses);
          }
        });
      }
      
      function resetButtons() {
        const allBtns = [acceptBtn, rejectBtn, revisionBtn];
        allBtns.forEach(btn => {
          btn.classList.remove(...greyedOutClasses);
        });
      }
      
      function showModal(action, form) {
        const messages = {
          accept: 'Are you sure you want to accept this submission?',
          reject: 'Are you sure you want to reject this submission?', 
          revision: 'Are you sure you want to request a revision for this submission?'
        };
        
        currentAction = action;
        currentForm = form;
        confirmMessage.textContent = messages[action];
        modal.classList.remove('hidden');
      }
      
      function hideModal() {
        modal.classList.add('hidden');
        currentAction = null;
        currentForm = null;
        resetButtons();
      }
      
      // Modal event listeners
      cancelBtn.addEventListener('click', hideModal);
      
      confirmBtn.addEventListener('click', function() {
        if (currentForm) {
          // Simple and reliable: create form data and send via fetch
          const formData = new FormData(currentForm);
          
          fetch(currentForm.action, {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          }).then(response => {
            // Always reload page after submission to show updated status
            window.location.reload();
          }).catch(error => {
            console.error('Submission error:', error);
            // Even if error, try reload to see if it worked
            window.location.reload();
          });
        }
      });
      
      // Close modal on backdrop click
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          hideModal();
        }
      });
      
      // Close modal on ESC key
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
          hideModal();
        }
      });
      
      acceptBtn.addEventListener('click', function(e) {
        e.preventDefault();
        greyOutOthers(acceptBtn);
        showModal('accept', acceptForm);
      });
      
      rejectBtn.addEventListener('click', function(e) {
        e.preventDefault();
        greyOutOthers(rejectBtn);
        showModal('reject', rejectForm);
      });
      
      revisionBtn.addEventListener('click', function(e) {
        e.preventDefault();
        greyOutOthers(revisionBtn);
        showModal('revision', revisionForm);
      });
    })();
  </script>
</body>
</html>