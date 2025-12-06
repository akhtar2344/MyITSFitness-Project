<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | myITS Fitness</title>

  {{-- FEATURE: Tailwind CSS framework integration for responsive design --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- FEATURE: Google Fonts Poppins integration for consistent typography --}}
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    html, body {
      font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial;
    }

    /* FEATURE: Custom CSS animations for snackbar notifications - slideIn/slideOut effects */
    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }

    /* FEATURE: Animation classes for toast notification system */
    .snackbar-enter {
      animation: slideIn 0.3s ease-in-out;
    }

    .snackbar-exit {
      animation: slideOut 0.3s ease-in-out;
    }
  </style>
</head>

<body class="bg-white text-slate-800">
  <!-- FEATURE: Responsive two-column layout for login page - hidden illustration on mobile -->
  <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- FEATURE: Login illustration panel - responsive visibility --}}
    <div class="hidden lg:flex items-end justify-center bg-[#f6f9ff]">
      <img
        src="{{ asset('images/login-illustration-lecturer.png') }}"
        alt="Login Illustration"
        class="max-w-full h-auto object-contain translate-y-4"
      />
    </div>

    {{-- FEATURE: Login form container with responsive padding --}}
    <div class="flex items-center justify-center p-8 lg:p-16">
      <div class="w-full max-w-sm space-y-6">

        {{-- FEATURE: Centered logo display with branding text --}}
        <div class="flex flex-col items-center text-center">
          <img
            src="{{ asset('images/myitsfitness-logo.png.png') }}"
            alt="myITS Fitness Logo"
            class="h-14 sm:h-16 w-auto"
          />
          <p class="mt-1 text-sm text-slate-500 leading-tight">
            Stay active, stay healthy with myITS.
          </p>
        </div>

        {{-- FEATURE: Welcome greeting with accent color branding --}}
        <div class="pt-2">
          <h2 class="text-2xl font-extrabold text-slate-900">
            Hi, <span class="text-[#2d6df6]">Welcome</span>
          </h2>
        </div>

        {{-- FEATURE: Authentication form with CSRF protection and validation --}}
        <form action="{{ route('login.process') }}" method="POST" class="space-y-4">
          @csrf
          <!-- FEATURE: Email input field with validation and error display -->
          <div>
            <label class="block text-sm font-medium mb-1">MyITS Email</label>
            <input
              type="email"
              name="email"
              placeholder="enter your MyITS email"
              value="{{ old('email') }}"
              class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#2d6df6]/20 outline-none @error('email') border-red-500 @enderror"
              required
            >
            @error('email')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- FEATURE: Password field with toggle visibility and validation -->
          <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <div class="relative">
              <input
                id="password"
                type="password"
                name="password"
                placeholder="enter your password"
                class="w-full border rounded-lg px-3 py-2 pr-10 focus:ring-2 focus:ring-[#2d6df6]/20 outline-none @error('password') border-red-500 @enderror"
                required
              >
              <!-- FEATURE: Password visibility toggle button with eye icon -->
              <button type="button" id="togglePassword" class="absolute right-3 top-2.5 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 
                    7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
            @error('password')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- FEATURE: Sign in button with hover effects and focus states -->
          <button
            type="submit"
            class="w-full bg-[#2d6df6] hover:bg-[#1f56d8] text-white font-semibold py-2.5 rounded-lg shadow-sm transition"
          >
            Sign in
          </button>
        </form>

        {{-- FEATURE: Visual divider element with horizontal lines and "OR" text --}}
        <div class="flex items-center justify-center gap-3 text-slate-400 text-sm">
          <span class="block h-px bg-slate-200 w-16"></span>
          <span>OR</span>
          <span class="block h-px bg-slate-200 w-16"></span>
        </div>

        {{-- FEATURE: MyITS OAuth integration button (placeholder for future SSO) --}}
        <a
          href="{{ route('login.myits') }}"
          class="w-full flex items-center justify-center gap-2 border rounded-lg py-2.5 text-slate-700 hover:bg-slate-50 transition"
        >
          <img src="{{ asset('images/myits-icon.png') }}" alt="myITS Icon" class="h-5 w-5">
          <span>Login with MyITS</span>
        </a>
      </div>
    </div>
  </div>

  {{-- FEATURE: Toast notification container for login feedback messages --}}
  <div id="snackbarContainer" class="fixed bottom-4 right-4 space-y-2 z-50"></div>

  <script>
    // FEATURE: Password visibility toggle functionality with eye icon switching
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    if (togglePassword) {
      togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        eyeIcon.innerHTML = type === 'password'
          ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 
              8.268 2.943 9.542 7-1.274 4.057-5.064 
              7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`
          : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 
              0-8.268-2.943-9.542-7a9.965 9.965 0 012.803-4.362M9.88 
              9.88A3 3 0 0114.12 14.12M6.1 6.1l11.8 11.8"/>`;
      });
    }

    // Snackbar functionality
    function showSnackbar(message, type = 'info', duration = 5000) {
      const container = document.getElementById('snackbarContainer');
      
      const snackbar = document.createElement('div');
      snackbar.className = `snackbar-enter p-4 rounded-lg text-white text-sm font-medium shadow-lg max-w-xs w-full`;
      
      // Set background color based on type
      if (type === 'success') {
        snackbar.classList.add('bg-green-500');
      } else if (type === 'error') {
        snackbar.classList.add('bg-red-500');
      } else if (type === 'warning') {
        snackbar.classList.add('bg-yellow-500');
      } else {
        snackbar.classList.add('bg-blue-500');
      }
      
      snackbar.textContent = message;
      container.appendChild(snackbar);
      
      // Auto remove after duration
      setTimeout(() => {
        snackbar.classList.remove('snackbar-enter');
        snackbar.classList.add('snackbar-exit');
        
        setTimeout(() => {
          snackbar.remove();
        }, 300);
      }, duration);
    }

    // Show snackbar on page load if there's a success message
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      const successMessage = '{{ session("success") }}';
      const errorMessage = '{{ $errors->first() }}';
      
      if (successMessage) {
        showSnackbar(successMessage, 'success');
      }
      
      if (errorMessage) {
        showSnackbar(errorMessage, 'error');
      }
    });
  </script>
</body>
</html>
