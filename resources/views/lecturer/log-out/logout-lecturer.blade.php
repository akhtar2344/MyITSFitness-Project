<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Logout Overlay | myITS Fitness</title>

  {{-- Tailwind --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Font --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    html, body { font-family: 'Poppins', system-ui, sans-serif; }
  </style>
</head>
<body class="bg-[#f7f9fc] text-slate-800">

  {{-- Topbar --}}
  <header class="flex justify-between items-center bg-white border-b px-6 py-3">
    <div class="flex items-center gap-2">
      <img src="{{ asset('images/myitsfitness-logo.png.png') }}" alt="myITS Fitness" class="h-8">
      <h1 class="text-lg font-semibold">myITS Fitness</h1>
    </div>

    <div class="flex items-center gap-4 relative">
      <div class="text-sm text-slate-600">EN ▾</div>

      {{-- User Icon --}}
      <button id="userMenuBtn" class="relative focus:outline-none">
        <img src="{{ asset('images/icon-user.png') }}" alt="User Icon" class="h-8 w-8 rounded-full cursor-pointer">
      </button>

      {{-- Logout Overlay --}}
      <div id="logoutOverlay"
           class="hidden absolute right-0 top-12 z-20 bg-transparent">
        <img src="{{ asset('images/logout-overlay.png') }}"
             alt="Logout Overlay"
             class="w-[160px] h-auto select-none pointer-events-none absolute -top-3 right-0" />
        <a href="{{ route('lecturer.login') }}"
           class="absolute right-0 top-0 flex items-center gap-2 justify-center bg-[#f43f5e] hover:bg-[#e11d48] text-white font-medium px-6 py-2.5 rounded-xl shadow-md transition-all"
           style="clip-path: polygon(100% 0%, 100% 100%, 0 100%, 0 0); width: 145px;">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-9V7m0 10h2a2 2 0 002-2V9a2 2 0 00-2-2h-2" />
          </svg>
          Logout
        </a>
      </div>
    </div>
  </header>

  {{-- Script Toggle --}}
  <script>
    const btn = document.getElementById('userMenuBtn');
    const overlay = document.getElementById('logoutOverlay');
    document.addEventListener('click', (e) => {
      if (btn.contains(e.target)) {
        overlay.classList.toggle('hidden');
      } else if (!overlay.contains(e.target)) {
        overlay.classList.add('hidden');
      }
    });
  </script>

</body>
</html>
