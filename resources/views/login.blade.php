<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Sistem Integrasi Keamanan Poltekad</title>
    <!-- Google Fonts: Montserrat & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        mono: ['Montserrat', 'monospace'],
                    },
                    boxShadow: {
                        'soft-indigo': '0 8px 30px rgba(99, 102, 241, 0.08)',
                    }
                }
            }
        }
    </script>
    <style>
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 20px 50px -12px rgba(148, 163, 184, 0.15);
        }
        .grid-blueprint {
            background-size: 24px 24px;
            background-image: 
                linear-gradient(to right, rgba(148, 163, 184, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(148, 163, 184, 0.04) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased min-h-screen flex items-center justify-center p-4 relative grid-blueprint">

    <!-- Ambient glowing nodes in background -->
    <div class="absolute top-1/4 left-1/4 w-[300px] h-[300px] bg-indigo-200/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[350px] h-[350px] bg-cyan-200/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Header logo -->
        <div class="flex flex-col items-center mb-6">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-soft-indigo mb-3">
                <i class="fa-solid fa-shield-halved text-2xl"></i>
            </div>
            <h1 class="text-sm font-bold tracking-tight text-slate-900 uppercase">POLTEKAD KODIKLATAD</h1>
            <p class="text-[10px] text-slate-400 font-mono tracking-widest uppercase mt-0.5">SISTEM INTEGRASI KEAMANAN</p>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-2xl p-6 md:p-8">
            <h2 class="text-base font-bold text-slate-800 mb-2">Autentikasi Operator</h2>
            <p class="text-xs text-slate-400 mb-6 font-medium">Masuk untuk mengakses portal pusat kendali taktis.</p>

            <!-- Errors alert -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-700 text-xs rounded-xl font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email Operator</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="operator@poltekad.mil.id"
                            class="w-full bg-white border border-slate-200/80 rounded-xl py-2 pl-10 pr-4 text-xs focus:outline-none focus:border-indigo-500 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full bg-white border border-slate-200/80 rounded-xl py-2 pl-10 pr-4 text-xs focus:outline-none focus:border-indigo-500 font-medium">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1.5">
                    <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-500 select-none">
                        <input type="checkbox" name="remember" class="rounded accent-indigo-500">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                    <span>MASUK KENDALI</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <!-- Login Hint -->
            <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                <span class="text-[10px] bg-indigo-50/50 border border-indigo-150/40 text-indigo-700 px-3 py-1.5 rounded-xl inline-block font-mono">
                    <span class="font-bold">Hint:</span> operator@poltekad.mil.id | poltekad123
                </span>
            </div>
        </div>

    </div>
</body>
</html>
