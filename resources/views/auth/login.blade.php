<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ (!empty($isMainDomain) ? 'Platform Portal Access — Nooryak' : (isset($agency) && $agency ? $agency->name . ' Portal Access' : 'Agency Portal Access')) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-slate-950 to-slate-950">

    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            @if(empty($isMainDomain) && isset($agency) && !empty($agency->logo))
                <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-slate-900/80 border border-slate-800 shadow-xl mb-4">
                    <img src="{{ asset($agency->logo) }}" alt="{{ $agency->name }}" class="h-10 w-auto object-contain">
                </div>
            @else
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 shadow-xl shadow-indigo-500/30 mb-4">
                    <i data-lucide="shield-check" class="w-7 h-7 text-white"></i>
                </div>
            @endif
            
            <h1 class="text-2xl font-bold text-white font-heading tracking-tight">
                {{ !empty($isMainDomain) ? 'Platform Portal Access' : (isset($agency) && $agency ? $agency->name . ' Portal Access' : 'Agency Portal Access') }}
            </h1>
            <p class="text-slate-400 text-sm mt-1">
                {{ !empty($isMainDomain) ? 'Authenticate for Super Admin, Master Agency, or White Label' : (isset($agency) && $agency ? 'Authenticate for ' . $agency->name . ' White Label Agency Portal' : 'Authenticate for White Label Agency Portal') }}
            </p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/70 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl shadow-indigo-950/50">
            
            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center space-x-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center space-x-2">
                    <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <p class="flex items-center space-x-2">
                            <i data-lucide="x-circle" class="w-3.5 h-3.5 text-rose-400 flex-shrink-0"></i>
                            <span>{{ $error }}</span>
                        </p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <input type="hidden" name="is_agency_portal" value="{{ empty($isMainDomain) ? '1' : '0' }}">

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Account Email</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-500"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="e.g. owner@agency.com" required 
                               class="w-full bg-slate-800/80 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">Security Key / Password</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-3.5 text-slate-500"></i>
                        <input type="password" id="password" name="password" placeholder="Enter password" required 
                               class="w-full bg-slate-800/80 border border-slate-700 rounded-xl pl-10 pr-11 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <button type="button" onclick="togglePasswordVisibility()" 
                                class="absolute right-3.5 top-3.5 text-slate-400 hover:text-white transition-colors focus:outline-none"
                                title="Toggle password visibility">
                            <i data-lucide="eye" id="eyeIcon" class="w-4 h-4"></i>
                            <i data-lucide="eye-off" id="eyeOffIcon" class="w-4 h-4 hidden"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                        <span>Remember credentials</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 transition-all duration-200 flex items-center justify-center space-x-2">
                    <span>Secure Sign In</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            @if(!empty($isMainDomain))
                <!-- Demo Quick Fill ONLY shown on main domain (nooryak.in / localhost) -->
                <div class="mt-6 pt-6 border-t border-slate-800 space-y-3">
                    <p class="text-xs text-slate-400 font-medium text-center">Click a Demo Account to Fill Credentials:</p>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <button type="button" onclick="fillCredentials('admin@platform.com', 'password')" 
                                class="p-2.5 rounded-xl bg-indigo-950/50 hover:bg-indigo-900/60 border border-indigo-700/40 text-indigo-200 transition text-left space-y-0.5 group">
                            <div class="font-semibold text-indigo-400 flex items-center justify-between text-[11px]">
                                <span>Super Admin</span>
                                <i data-lucide="shield" class="w-3 h-3"></i>
                            </div>
                            <div class="text-[10px] text-slate-300 truncate">admin@platform.com</div>
                            <div class="text-[9px] text-slate-400 font-mono">Pass: <span class="text-indigo-300">password</span></div>
                        </button>

                        <button type="button" onclick="fillCredentials('abdulbahad.dev@gmail.com', 'password')" 
                                class="p-2.5 rounded-xl bg-purple-950/50 hover:bg-purple-900/60 border border-purple-700/40 text-purple-200 transition text-left space-y-0.5 group">
                            <div class="font-semibold text-purple-400 flex items-center justify-between text-[11px]">
                                <span>KKK Master</span>
                                <i data-lucide="building" class="w-3 h-3"></i>
                            </div>
                            <div class="text-[10px] text-slate-300 truncate">abdulbahad...</div>
                            <div class="text-[9px] text-slate-400 font-mono">Pass: <span class="text-purple-300">password</span></div>
                        </button>

                        <button type="button" onclick="fillCredentials('priya@abcdigital.com', 'password')" 
                                class="p-2.5 rounded-xl bg-emerald-950/50 hover:bg-emerald-900/60 border border-emerald-700/40 text-emerald-200 transition text-left space-y-0.5 group">
                            <div class="font-semibold text-emerald-400 flex items-center justify-between text-[11px]">
                                <span>White Label</span>
                                <i data-lucide="layers" class="w-3 h-3"></i>
                            </div>
                            <div class="text-[10px] text-slate-300 truncate">priya@abcdigital...</div>
                            <div class="text-[9px] text-slate-400 font-mono">Pass: <span class="text-emerald-300">password</span></div>
                        </button>
                    </div>
                </div>
            @else
                <!-- 1-Click Auto Assign Credentials Button for Agency Domain -->
                <div class="mt-6 pt-6 border-t border-slate-800 space-y-3">
                    @php
                        $targetEmail = isset($agencyUser) && !empty($agencyUser->email) ? $agencyUser->email : 'priya@abcdigital.com';
                    @endphp
                    <button type="button" onclick="fillCredentials('{{ $targetEmail }}', 'password')" 
                            class="w-full p-3.5 rounded-xl bg-gradient-to-r from-emerald-950/70 to-teal-950/70 hover:from-emerald-900/80 hover:to-teal-900/80 border border-emerald-700/50 text-emerald-200 transition text-left flex items-center justify-between group shadow-lg">
                        <div class="space-y-0.5">
                            <div class="font-bold text-emerald-400 flex items-center space-x-1.5 text-xs">
                                <i data-lucide="zap" class="w-4 h-4 text-emerald-400"></i>
                                <span>Auto Assign {{ isset($agency) && $agency ? $agency->name : 'Agency' }} Credentials</span>
                            </div>
                            <div class="text-[11px] text-slate-300">{{ $targetEmail }}</div>
                        </div>
                        <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-300 font-extrabold rounded-lg text-xs border border-emerald-500/30 group-hover:bg-emerald-500/30 transition">1-Click Apply</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        lucide.createIcons();
    </script>
</body>
</html>
