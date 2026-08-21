<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Launching {{ $title ?? 'Admin Portal' }}...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/40 via-slate-950 to-slate-950">

    <div class="max-w-md w-full text-center space-y-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 shadow-xl shadow-indigo-600/30 text-white animate-bounce">
            <i data-lucide="key" class="w-8 h-8"></i>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-white font-heading">Direct Admin SSO Launch</h1>
            <p class="text-xs text-indigo-300 mt-1">Authenticating & Logging into <b>{{ $title ?? 'Admin Console' }}</b> without password prompts...</p>
        </div>

        <div class="p-6 rounded-2xl bg-slate-900/80 border border-slate-800 backdrop-blur-xl shadow-2xl text-xs space-y-3">
            <div class="flex items-center justify-between text-slate-400">
                <span>Target Portal:</span>
                <span class="font-mono text-indigo-400 font-bold truncate max-w-[200px]">{{ $targetUrl }}</span>
            </div>
            <div class="flex items-center justify-between text-slate-400">
                <span>SSO Username:</span>
                <span class="font-mono text-white font-bold">{{ $username }}</span>
            </div>

            <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden mt-4">
                <div class="bg-indigo-500 h-full w-full animate-pulse"></div>
            </div>
        </div>

        <!-- Hidden Auto-Submitting SSO Form -->
        <form id="autoLoginForm" action="{{ $targetUrl }}" method="POST" class="hidden">
            <input type="hidden" name="username" value="{{ $username }}">
            <input type="hidden" name="email" value="{{ $username }}">
            <input type="hidden" name="user" value="{{ $username }}">
            <input type="hidden" name="login" value="1">
            <input type="hidden" name="password" value="{{ $password }}">
            <input type="hidden" name="pwd" value="{{ $password }}">
            <input type="hidden" name="pass" value="{{ $password }}">
        </form>
    </div>

    <script>
        lucide.createIcons();
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById('autoLoginForm').submit();
            }, 300);
        });
    </script>
</body>
</html>
