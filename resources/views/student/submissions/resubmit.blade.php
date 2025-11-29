<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Resubmit Activity | myITS Fitness</title>

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

    /* Snackbar animations */
    @keyframes slideInSnackbar {
      from { transform: translateX(400px); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutSnackbar {
      from { transform: translateX(0); opacity: 1; }
      to { transform: translateX(400px); opacity: 0; }
    }
    .animate-slide-in { animation: slideInSnackbar 0.3s ease-in-out; }
    .animate-slide-out { animation: slideOutSnackbar 0.3s ease-in-out; }

    /* Red tint overlay for current proof */
    .proof-with-red-tint {
      position: relative;
    }
    .proof-with-red-tint::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(239, 68, 68, 0.2);
      pointer-events: none;
      z-index: 1;
    }

    /* Upload hint on hover - MUST NOT BLOCK CLICKS */
    .proof-zone:hover .proof-hint {
      opacity: 1;
    }
    .proof-hint {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.3);
      opacity: 0;
      transition: opacity 0.2s ease;
      border-radius: 12px;
      pointer-events: none;
      z-index: 2;
    }
  </style>
</head>

<body class="bg-[#f7f9fc] text-slate-800">
@php
  // Get activity name without overwriting the activity object
  $activityName = $activity->name ?? 'Activity';
  $activityLocation = $activity->location ?? 'N/A';
  $isOther = in_array(strtolower($activityName), ['other','others']);
  
  // Map ikon untuk judul (kecuali Other)
  $iconMap = [
    'Gym'        => 'gym-icon.png',
    'Running'    => 'Running-icon.png',
    'Soccer'     => 'soccer-icon.png',
    'Cycling'    => 'cycling-icon.png',
    'Basketball' => 'basketball-icon.png',
  ];
  $iconFile = $iconMap[$activityName] ?? null;
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
          <a href="{{ route('login') }}" class="block cursor-pointer">
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
        <a href="{{ route('student.status') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 transition-colors font-medium">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span>Back to status</span>
        </a>

        {{-- Title --}}
        <h1 class="mt-2 flex items-center gap-3 text-4xl font-extrabold tracking-tight">
          @if($iconFile)
            <img src="{{ asset('images/'.$iconFile) }}" alt="{{ $activityName }} icon" class="w-12 h-12 object-contain select-none">
          @endif
          <span>{{ $activityName }}</span>
        </h1>
        <p class="mt-2 text-sm text-slate-600">Resubmit your activity with updated proof. Greyed out fields cannot be changed.</p>

        <div class="mt-6 rounded-2xl bg-white border shadow-sm p-6">
          <form id="resubmitForm" class="grid grid-cols-12 gap-6">
            @csrf

            <!-- Left -->
            <div class="col-span-12 lg:col-span-7">
              <label class="block text-sm font-semibold text-slate-600">Date of Occurrence</label>
              <div class="mt-2">
                <input type="text" placeholder="dd/mm/yyyy" disabled
                       value="{{ $submission->created_at->format('d/m/Y') }}"
                       class="w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-500 bg-slate-50 cursor-not-allowed"/>
              </div>

              <div class="mt-6 grid grid-cols-12 gap-3">
                <div class="col-span-6">
                  <label class="block text-sm font-semibold text-slate-600">Session Duration</label>
                  <input type="number" min="1" step="1" disabled
                         value="{{ $submission->duration_minutes ?? 1 }}"
                         class="mt-2 w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-500 bg-slate-50 cursor-not-allowed"/>
                </div>
                <div class="col-span-6">
                  <label class="block text-sm font-semibold text-transparent select-none">.</label>
                  <input value="Minutes" disabled class="mt-2 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-500"/>
                </div>
              </div>

              <div class="mt-6">
                <label class="block text-sm font-semibold text-slate-600">Place of Issue</label>
                <input type="text" placeholder="ex: KONI" disabled
                       value="{{ $activity->location ?? 'N/A' }}"
                       class="mt-2 w-full rounded-xl border border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 px-4 py-3 text-slate-500 bg-slate-50 cursor-not-allowed"/>
              </div>

              <div class="mt-6">
              <label class="block text-sm font-semibold text-slate-600">Certificate or Membership (Optional)</label>
              <div for="certInput" class="dropzone mt-2 flex h-44 w-full cursor-pointer items-center justify-center rounded-xl border-2 border-dashed border-slate-300 text-center overflow-hidden bg-slate-50">
                <div id="certPreviewContainer" class="space-y-1 w-full h-full flex items-center justify-center">
                  <div class="space-y-1">
                    <p class="text-slate-600 font-semibold">PNG, JPEG, JPG</p>
                    <p class="text-xs text-slate-400">10 MB MAX</p>
                    <p id="certName" class="text-xs text-slate-500"></p>
                  </div>
                </div>
              </div>
              <input id="certInput" type="file" name="certificate_image" accept="image/png,image/jpeg" class="hidden">
              </div>
            </div>

            <!-- Right -->
            <div class="col-span-12 lg:col-span-5">
              <label class="block text-sm font-semibold text-slate-600">Activity Proof <span class="text-red-500">*</span></label>
              
              <!-- PROOF UPLOAD ZONE - SIMPLIFIED STRUCTURE -->
              <div id="proofZone" class="proof-zone dropzone mt-2 flex h-64 w-full cursor-pointer items-center justify-center rounded-xl border-2 border-dashed border-slate-300 text-center overflow-hidden bg-slate-50 relative">
                
                <!-- PREVIEW CONTAINER - NO NESTED OVERLAY -->
                <div id="proofPreviewContainer" class="w-full h-full flex items-center justify-center relative">
                  @if($proofImage && !empty($proofImage))
                    <div class="w-full h-full proof-with-red-tint">
                      <img src="{{ $proofImage }}" alt="Current proof" class="w-full h-full object-cover">
                    </div>
                  @else
                    <div class="space-y-1">
                      <p class="text-slate-600 font-semibold">PNG, JPEG, JPG</p>
                      <p class="text-xs text-slate-400">10 MB MAX</p>
                      <p id="proofName" class="text-xs text-slate-500"></p>
                    </div>
                  @endif
                </div>
                
                <!-- HOVER OVERLAY - POINTER-EVENTS NONE -->
                <div class="proof-hint">
                  <div class="text-center">
                    <p class="text-white font-semibold text-lg">📸 Upload another proof</p>
                  </div>
                </div>
              </div>
              
              <!-- HIDDEN FILE INPUT -->
              <input id="proofInput" type="file" name="proof_image" accept="image/png,image/jpeg" class="hidden">
              
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
                  Resubmit
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
          <a href="{{ route('student.submit') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800 transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Back to selecting activity</span>
          </a>
          <div class="mt-6 rounded-2xl bg-white border shadow-sm flex flex-col items-center justify-center py-24 px-8 text-center min-h-[560px] md:min-h-[640px]">
            <h1 class="text-2xl md:text-3xl font-extrabold text-[#0a1a33] mb-2">
              Activity resubmitted!
            </h1>
            <p class="text-slate-500 mb-6">Kindly check status page for further confirmation</p>
            <a href="{{ route('student.status') }}" class="flex items-center gap-2 text-[#0a1a33] font-semibold hover:opacity-80 transition">
              <img src="{{ asset('images/status-page.png') }}" class="w-5 h-5" alt="">
              Check Status
            </a>
          </div>
        </section>
      </div>
    </main>
  </div>

  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    // Snackbar notification system
    function showSnackbar(message, type = 'info', duration = 5000) {
      const container = document.getElementById('snackbarContainer') || createSnackbarContainer();
      const snackbar = document.createElement('div');
      snackbar.className = `p-4 rounded-lg text-white text-sm font-medium shadow-lg max-w-xs w-full animate-slide-in`;
      
      if (type === 'success') snackbar.classList.add('bg-green-500');
      else if (type === 'error') snackbar.classList.add('bg-red-500');
      else if (type === 'warning') snackbar.classList.add('bg-yellow-500');
      else snackbar.classList.add('bg-blue-500');
      
      snackbar.textContent = message;
      container.appendChild(snackbar);
      
      setTimeout(() => {
        snackbar.classList.add('animate-slide-out');
        setTimeout(() => snackbar.remove(), 300);
      }, duration);
    }

    function createSnackbarContainer() {
      const container = document.createElement('div');
      container.id = 'snackbarContainer';
      container.className = 'fixed bottom-4 right-4 space-y-2 z-50';
      document.body.appendChild(container);
      return container;
    }

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

    // ========================================
    // PROOF IMAGE UPLOAD HANDLER
    // ========================================
    (function () {
      const proofInput = document.getElementById('proofInput');
      const proofZone = document.getElementById('proofZone');
      const proofPreviewContainer = document.getElementById('proofPreviewContainer');
      const submitBtn = document.getElementById('submitBtn');

      // Validation: Check if elements exist
      if (!proofInput) {
        console.error('❌ proofInput element not found');
        return;
      }
      if (!proofZone) {
        console.error('❌ proofZone element not found');
        return;
      }

      console.log('✅ Proof upload initialized');

      // ========================================
      // CLICK HANDLER - OPEN FILE PICKER
      // ========================================
      proofZone.addEventListener('click', function(e) {
        console.log('📸 Proof zone clicked');
        proofInput.value = ''; // Reset untuk allow re-select
        proofInput.click();    // Trigger file picker
      });

      // ========================================
      // DRAG & DROP HANDLERS
      // ========================================
      proofZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        proofZone.classList.add('dragover');
      });

      proofZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        proofZone.classList.remove('dragover');
      });

      proofZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        proofZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          proofInput.files = files;
          handleProofChange();
        }
      });

      // ========================================
      // FILE CHANGE HANDLER
      // ========================================
      proofInput.addEventListener('change', handleProofChange);

      function handleProofChange() {
        const file = proofInput.files[0];
        if (!file) {
          console.log('⚠️ No file selected');
          return;
        }

        console.log('📂 File selected:', file.name);

        const reader = new FileReader();
        reader.onload = (e) => {
          console.log('🖼️ Preview ready');
          
          // Reconstruct the preview container - CLEAN SLATE
          proofPreviewContainer.innerHTML = `
            <div class="w-full h-full">
              <img src="${e.target.result}" alt="New proof" class="w-full h-full object-cover">
            </div>
          `;
          
          // Enable submit button
          submitBtn.disabled = false;
          console.log('✅ Submit button enabled');
        };
        
        reader.onerror = () => {
          console.error('❌ Failed to read file');
        };
        
        reader.readAsDataURL(file);
      }
    })();

    // ========================================
    // CERTIFICATE IMAGE UPLOAD HANDLER
    // ========================================
    (function () {
      const certInput = document.getElementById('certInput');
      const certZone = document.querySelector('div[for="certInput"]');
      const certPreviewContainer = document.getElementById('certPreviewContainer');
      const certName = document.getElementById('certName');

      // Validation
      if (!certInput) {
        console.error('❌ certInput element not found');
        return;
      }
      if (!certZone) {
        console.error('❌ certZone element not found');
        return;
      }

      console.log('✅ Certificate upload initialized');

      // Click handler
      certZone.addEventListener('click', function(e) {
        console.log('📄 Cert zone clicked');
        certInput.value = ''; // Reset
        certInput.click();    // Trigger file picker
      });

      // Drag & drop
      certZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        certZone.classList.add('dragover');
      });

      certZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        certZone.classList.remove('dragover');
      });

      certZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        certZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          certInput.files = files;
          handleCertChange();
        }
      });

      // File change handler
      certInput.addEventListener('change', handleCertChange);

      function handleCertChange() {
        const file = certInput.files[0];
        if (!file) {
          console.log('⚠️ No certificate selected');
          return;
        }

        console.log('📂 Certificate selected:', file.name);
        certName.textContent = file.name;
        
        createImagePreview(file, certPreviewContainer);
      }

      function createImagePreview(file, container) {
        const reader = new FileReader();
        reader.onload = (e) => {
          console.log('🖼️ Certificate preview ready');
          container.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover rounded-xl">`;
        };
        reader.readAsDataURL(file);
      }
    })();

    // Form submission
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (submitBtn.disabled) return;
      
      const proofInput = document.getElementById('proofInput');
      
      // Note: Proof image is optional - user can resubmit without new image
      // The old image will be preserved on the backend
      
      if (proofInput.files && proofInput.files.length > 0) {
        const proofFile = proofInput.files[0];
        console.log('📸 Proof file selected:', proofFile.name, proofFile.size, proofFile.type);
      } else {
        console.log('ℹ️ No new proof image selected - old proof will be preserved');
      }
      
      // Create FormData from form
      const form = document.getElementById('resubmitForm');
      const formData = new FormData(form);
      
      // DEBUG: Log FormData contents
      console.log('📋 FormData contents:');
      for (let [key, value] of formData.entries()) {
        if (value instanceof File) {
          console.log(`  ${key}: File(${value.name}, ${value.size} bytes, ${value.type})`);
        } else {
          console.log(`  ${key}: ${value}`);
        }
      }
      
      console.log('📤 Submitting resubmission...');
      
      // Open modal first
      openComplete();
      
      // Submit to server
      fetch("{{ route('student.submissions.resubmitStore', $submission->id) }}", {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
        }
      })
      .then(response => {
        console.log('📬 Response status:', response.status);
        
        // Try to parse JSON regardless of status
        return response.json().then(data => ({
          status: response.status,
          data: data
        }));
      })
      .then(({ status, data }) => {
        console.log('📊 Response data:', data);
        
        if (data.success && status === 200) {
          console.log('✅ Resubmission successful');
          // Modal stays open, show success screen after delay
          setTimeout(() => {
            closeCompleteFn();
            setTimeout(() => {
              document.querySelector('main').classList.add('hidden');
              document.getElementById('finalSuccess').classList.remove('hidden');
              window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 220);
          }, 2000);
        } else {
          console.error('❌ Submission failed:', data);
          // Close modal and show error
          closeCompleteFn();
          
          // Handle validation errors
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat().join(', ');
            showSnackbar('Validation error: ' + errorMessages, 'error');
          } else {
            showSnackbar(data.message || 'Error resubmitting activity', 'error');
          }
          submitBtn.disabled = false;
        }
      })
      .catch(error => {
        console.error('❌ Fetch error:', error);
        closeCompleteFn();
        showSnackbar('Error resubmitting activity: ' + error.message, 'error');
        submitBtn.disabled = false;
      });
    });

    // Logout overlay toggle
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
