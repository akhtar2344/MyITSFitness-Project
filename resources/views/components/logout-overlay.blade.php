{{-- FEATURE: Reusable logout overlay component for all user types --}}
<div id="logoutOverlay" class="hidden absolute right-0 top-12 z-20 bg-transparent">
  <img src="{{ asset('images/logout-overlay.png') }}"
       alt="Logout Overlay"
       class="w-[160px] h-auto select-none pointer-events-none absolute -top-3 right-0" />
  <a href="{{ route('logout') }}"
     class="absolute right-0 top-0 flex items-center gap-2 justify-center bg-[#f43f5e] hover:bg-[#e11d48] text-white font-medium px-6 py-2.5 rounded-xl shadow-md transition-all"
     style="clip-path: polygon(100% 0%, 100% 100%, 0 100%, 0 0); width: 145px;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
      <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V7m0 10h2a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
    </svg>
    Logout
  </a>
</div>

{{-- FEATURE: Universal logout overlay toggle script --}}
<script>
  // FEATURE: Initialize logout overlay functionality
  (function() {
    const btn = document.getElementById('userMenuBtn') || document.getElementById('userAvatar');
    const overlay = document.getElementById('logoutOverlay');
    
    if (!btn || !overlay) return;
    
    // FEATURE: Toggle overlay on user button click
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      overlay.classList.toggle('hidden');
    });
    
    // FEATURE: Close overlay when clicking outside
    document.addEventListener('click', (e) => {
      if (!overlay.classList.contains('hidden') && !overlay.contains(e.target)) {
        overlay.classList.add('hidden');
      }
    });
    
    // FEATURE: Close overlay with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        overlay.classList.add('hidden');
      }
    });
  })();
</script>