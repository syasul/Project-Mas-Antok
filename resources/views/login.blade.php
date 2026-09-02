<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Operator — Sistem Autentikasi CPS Poltekad Kodiklatad</title>

    <!-- Google Fonts: Poppins & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Poppins"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans text-slate-800 bg-slate-100 flex items-center justify-center p-4 selection:bg-slate-900 selection:text-white">

    <div class="w-full max-w-md">
        
        <!-- Institution Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-slate-900 text-emerald-400 border border-slate-800 shadow-sm mb-3">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>
            <h1 class="text-base font-extrabold text-slate-900 uppercase font-mono tracking-tight">POLTEKAD KODIKLATAD</h1>
            <p class="text-xs text-slate-500 font-mono mt-0.5">Sistem Autentikasi Biometrik Real-Time (CPS)</p>
        </div>

        <!-- Solid Professional Login Card -->
        <div class="bg-white rounded-xl border border-slate-300/80 shadow-sm p-6 sm:p-8">
            
            <div class="mb-5 pb-4 border-b border-slate-100">
                <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 font-mono">AUTENTIKASI OPERATOR JAGA</h2>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan kredensial resmi untuk mengakses pusat kendali</p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-lg font-medium flex items-start gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 shrink-0 text-rose-500"></i>
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
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1.5">Email Operator</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email', 'operator@poltekad.mil.id') }}" required autofocus placeholder="operator@poltekad.mil.id"
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 pl-9 pr-3 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 font-medium text-slate-900 font-mono">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" id="password" name="password" value="poltekad123" required placeholder="••••••••"
                            class="w-full bg-slate-50 border border-slate-300 rounded-lg py-2 pl-9 pr-3 text-xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 font-medium text-slate-900">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer font-medium text-slate-600 select-none">
                        <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                        <span>Ingat Sesi Perangkat</span>
                    </label>
                </div>

                <button type="submit" class="w-full mt-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 rounded-lg transition-colors shadow-xs flex items-center justify-center gap-2 font-mono uppercase tracking-wider">
                    <span>MASUK PUSAT KENDALI</span>
                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                </button>
            </form>

            <!-- Quick Preset Credentials -->
            <div class="mt-6 border-t border-slate-100 pt-4">
                <div class="text-[10px] font-bold uppercase font-mono text-slate-400 mb-2">AKUN DEFAULT PENGUJIAN:</div>
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-2.5 text-[11px] font-mono space-y-1 text-slate-700">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Operator:</span>
                        <span class="font-bold text-slate-900">Letnan Dua Antok</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Email:</span>
                        <span class="font-bold text-slate-900">operator@poltekad.mil.id</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Password:</span>
                        <span class="font-bold text-slate-900">poltekad123</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Footer Notice -->
        <p class="text-center text-[11px] text-slate-400 mt-6 font-mono">
            &copy; 2026 Laboratorium Siber Poltekad Kodiklatad. Hak Cipta Dilindungi.
        </p>

    </div>

</body>
</html>
