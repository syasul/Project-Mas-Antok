<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Evaluasi Usability & SUS — Poltekad</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
<body class="h-full font-sans text-slate-800 bg-slate-50 antialiased selection:bg-indigo-100 selection:text-indigo-800">

    <!-- Topbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="font-extrabold text-sm sm:text-base text-slate-900 tracking-tight">REKAPITULASI HASIL PENGUJIAN USABILITY</h1>
                    <p class="text-[11px] text-slate-500 font-medium">Data Kuantitatif Deskriptif: System Usability Scale (SUS) &amp; Task Completion Time (TCT)</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('usability.sus') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition-colors">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Responden SUS</span>
                </a>
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- 1. EXECUTIVE KPI BENCHMARK CARDS -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- Average SUS Score -->
            <div class="bg-white p-5 rounded-2xl border {{ $avgSus >= 75 ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200' }} shadow-xs">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-bold text-slate-500 uppercase font-mono">RATA-RATA SKOR SUS</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800 font-mono">TARGET &gt; 75</span>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-3xl font-extrabold font-mono text-slate-900">{{ $avgSus }}</span>
                    <span class="text-xs text-slate-400 font-mono">/ 100</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-xs font-semibold {{ $avgSus >= 75 ? 'text-emerald-700' : 'text-slate-600' }}">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span>
                        @if($avgSus >= 85) Grade A+ (Best Imaginable)
                        @elseif($avgSus >= 80.3) Grade A (Excellent)
                        @elseif($avgSus >= 74) Grade B (Good / Acceptable)
                        @else Grade C (OK)
                        @endif
                    </span>
                </div>
            </div>

            <!-- Total Responden -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-bold text-slate-500 uppercase font-mono">JUMLAH RESPONDEN OPERATOR</span>
                    <i class="fa-solid fa-users text-slate-400 text-xs"></i>
                </div>
                <div class="text-3xl font-extrabold font-mono text-slate-900 mt-2">
                    {{ $susResponses->count() }}
                </div>
                <div class="mt-2 text-xs text-slate-500">
                    Operator Poltekad terdaftar
                </div>
            </div>

            <!-- Average Task Completion Time (TCT) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-bold text-slate-500 uppercase font-mono">RATA-RATA WAKTU TUGAS (TCT)</span>
                    <i class="fa-solid fa-stopwatch text-indigo-500 text-xs"></i>
                </div>
                <div class="flex items-baseline gap-1 mt-2">
                    <span class="text-3xl font-extrabold font-mono text-slate-900">{{ $avgTct }}</span>
                    <span class="text-xs text-slate-500 font-mono">detik</span>
                </div>
                <div class="mt-2 text-xs text-indigo-700 font-medium flex items-center gap-1">
                    <i class="fa-solid fa-bolt text-[10px]"></i>
                    <span>Tingkat efisiensi interaksi tinggi</span>
                </div>
            </div>

            <!-- User Error Rate -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
                <div class="flex justify-between items-start">
                    <span class="text-[10px] font-bold text-slate-500 uppercase font-mono">TOTAL KESALAHAN (MISCLICK)</span>
                    <i class="fa-solid fa-arrow-pointer text-amber-500 text-xs"></i>
                </div>
                <div class="text-3xl font-extrabold font-mono text-slate-900 mt-2">
                    {{ $totalErrors }}
                </div>
                <div class="mt-2 text-xs text-slate-500">
                    Dari {{ $sessions->count() }} total sesi tugas teruji
                </div>
            </div>

        </section>

        <!-- 2. SUS RESPONSES BREAKDOWN TABLE -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs">
                        SUS
                    </div>
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 font-mono">TABEL REKAP SKOR KUESIONER SYSTEM USABILITY SCALE (SUS)</h2>
                        <p class="text-[11px] text-slate-400">Rincian jawaban 10 pertanyaan standar per responden</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-mono text-[10px] border-b border-slate-200">
                            <th class="py-3 px-3.5">Nama Responden & Peran</th>
                            <th class="py-3 px-2 text-center">Q1</th>
                            <th class="py-3 px-2 text-center">Q2</th>
                            <th class="py-3 px-2 text-center">Q3</th>
                            <th class="py-3 px-2 text-center">Q4</th>
                            <th class="py-3 px-2 text-center">Q5</th>
                            <th class="py-3 px-2 text-center">Q6</th>
                            <th class="py-3 px-2 text-center">Q7</th>
                            <th class="py-3 px-2 text-center">Q8</th>
                            <th class="py-3 px-2 text-center">Q9</th>
                            <th class="py-3 px-2 text-center">Q10</th>
                            <th class="py-3 px-3.5 text-right font-bold">Skor SUS</th>
                            <th class="py-3 px-3.5">Kategori / Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700 font-mono">
                        @forelse($susResponses as $res)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-3.5 font-sans">
                                    <div class="font-bold text-slate-900">{{ $res->respondent_name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $res->respondent_role }}</div>
                                </td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q1 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q2 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q3 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q4 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q5 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q6 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q7 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q8 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q9 }}</td>
                                <td class="py-3 px-2 text-center text-slate-600">{{ $res->q10 }}</td>
                                <td class="py-3 px-3.5 text-right font-bold text-sm text-slate-900">
                                    {{ $res->final_score }}
                                </td>
                                <td class="py-3 px-3.5 font-sans">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $res->final_score >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $res->grade }} ({{ $res->adjective_rating }})
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-6 text-slate-400 font-sans">Belum ada respon kuesioner SUS yang tersimpan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- 3. TASK COMPLETION TIME (TCT) SESSIONS TABLE -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs">
                        TCT
                    </div>
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 font-mono">RIWAYAT PENGUJIAN TASK COMPLETION TIME (TCT)</h2>
                        <p class="text-[11px] text-slate-400">Pencatatan waktu penyelesaian tugas dan tingkat kesalahan klik operator</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-mono text-[10px] border-b border-slate-200">
                            <th class="py-3 px-3.5">Waktu Uji</th>
                            <th class="py-3 px-3.5">Operator</th>
                            <th class="py-3 px-3.5">Kode</th>
                            <th class="py-3 px-3.5">Skenario Tugas</th>
                            <th class="py-3 px-3.5 text-right">Durasi (TCT)</th>
                            <th class="py-3 px-3.5 text-center">Misclick / Error</th>
                            <th class="py-3 px-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($sessions as $sess)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-3.5 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                                    {{ $sess->created_at ? $sess->created_at->format('d/m/Y H:i:s') : '' }}
                                </td>
                                <td class="py-3 px-3.5 font-bold text-slate-900 whitespace-nowrap">{{ $sess->operator_name }}</td>
                                <td class="py-3 px-3.5 font-mono font-bold text-indigo-700">{{ $sess->task_code }}</td>
                                <td class="py-3 px-3.5 text-slate-800">{{ $sess->task_name }}</td>
                                <td class="py-3 px-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap">{{ $sess->completion_time_sec }} dtk</td>
                                <td class="py-3 px-3.5 text-center font-mono font-bold {{ $sess->error_count > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $sess->error_count }}</td>
                                <td class="py-3 px-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">
                                        <i class="fa-solid fa-check text-[10px]"></i> COMPLETED
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-400">Belum ada data sesi pengujian tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>

</body>
</html>
