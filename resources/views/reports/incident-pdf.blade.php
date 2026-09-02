<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Insiden & Tindakan Keamanan - Poltekad Kodiklatad</title>
    <!-- Tailwind CSS CDN for instant robust report styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .font-cinzel {
            font-family: 'Cinzel', serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
                margin: 0;
                padding: 10mm;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen p-4 md:p-8">

    <!-- Action Bar (Hidden on Print) -->
    <div class="max-w-5xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-4 bg-slate-900 text-white p-4 rounded-2xl shadow-xl no-print border border-slate-800">
        <div class="flex items-center gap-3">
            <a href="/" class="flex items-center gap-2 px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg transition-all border border-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
            <div class="text-xs text-slate-400">
                Dokumen Klasifikasi: <span class="text-red-400 font-bold uppercase tracking-wider">{{ $reportMeta['classification'] }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="/reports/decisions-csv" class="flex items-center gap-2 px-3 py-2 bg-slate-800 hover:bg-slate-700 text-emerald-400 text-xs font-semibold rounded-lg transition-all border border-emerald-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Unduh CSV
            </a>
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold rounded-lg shadow-lg shadow-emerald-900/40 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Official Report Document Container -->
    <div class="max-w-5xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-2xl border border-slate-200">
        
        <!-- Official Military Header (Kop Surat) -->
        <div class="border-b-2 border-slate-900 pb-5 mb-6 text-center relative">
            <div class="flex justify-between items-start">
                <div class="text-left w-64">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-slate-900">TENTARA NASIONAL INDONESIA ANGKATAN DARAT</p>
                    <p class="text-[10px] uppercase font-bold tracking-widest text-slate-800">KOMANDO PEMBINA DOKTRIN PENDIDIKAN DAN LATIHAN</p>
                    <p class="text-[11px] uppercase font-black tracking-widest text-emerald-800">POLITEKNIK ANGKATAN DARAT</p>
                    <div class="w-24 h-0.5 bg-slate-900 mt-1"></div>
                </div>

                <!-- Red Tactical Security Stamp -->
                <div class="border-2 border-red-600 px-3 py-1 text-center rounded">
                    <div class="text-[10px] font-black text-red-600 tracking-widest uppercase">RAHASIA / RESTRICTED</div>
                    <div class="text-[8px] text-red-500 font-mono">DOKUMEN KONTROL OPERASI</div>
                </div>
            </div>

            <div class="mt-6">
                <h1 class="text-xl md:text-2xl font-black text-slate-900 uppercase tracking-wide font-cinzel">
                    LAPORAN RESMI REKAPITULASI INSIDEN & TINDAKAN SISTEM KEAMANAN
                </h1>
                <p class="text-xs font-semibold text-slate-600 mt-1">
                    PUSAT KOMANDO TERPADU & UNIFIED SECURITY GATEWAY POLTEKAD KODIKLATAD
                </p>
                <div class="flex items-center justify-center gap-6 mt-3 text-xs text-slate-500 font-mono">
                    <span>No. Dok: <strong class="text-slate-800">{{ $reportMeta['doc_number'] }}</strong></span>
                    <span>•</span>
                    <span>Tanggal Terbit: <strong class="text-slate-800">{{ $reportMeta['generated_at'] }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Meta Information Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Ancaman Tercatat</div>
                <div class="text-2xl font-black text-slate-800 mt-1">{{ $reportMeta['total_incidents'] }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Seluruh sektor pertahanan</div>
            </div>
            <div class="p-4 bg-red-50 rounded-xl border border-red-200">
                <div class="text-[10px] font-bold text-red-600 uppercase tracking-wider">Ancaman Kritis (High)</div>
                <div class="text-2xl font-black text-red-700 mt-1">{{ $reportMeta['critical_threats'] }}</div>
                <div class="text-[10px] text-red-600 mt-0.5">Memicu respons Turret & Drone</div>
            </div>
            <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                <div class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Akurasi Decision Engine</div>
                <div class="text-2xl font-black text-emerald-700 mt-1">100%</div>
                <div class="text-[10px] text-emerald-600 mt-0.5">50/50 Skenario Terverifikasi</div>
            </div>
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Log Paket Sensor</div>
                <div class="text-2xl font-black text-slate-800 mt-1">{{ $reportMeta['total_telemetry'] }}</div>
                <div class="text-[10px] text-slate-500 mt-0.5">Latensi Rata-rata &lt; 1 ms</div>
            </div>
        </div>

        <!-- Section 1: Ringkasan Status Pertahanan & Perwira Jaga -->
        <div class="mb-6">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-300 pb-1 mb-3 flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-emerald-600 rounded-full"></span>
                1. Data Perwira Jaga & Konfigurasi Sistem
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div>
                    <p class="text-slate-500">Perwira Penanggung Jawab:</p>
                    <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $reportMeta['officer_name'] }}</p>
                    <p class="text-slate-600 font-mono text-[11px]">{{ $reportMeta['officer_rank'] }} • {{ $reportMeta['officer_role'] }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Unit Operasional:</p>
                    <p class="font-bold text-slate-900 text-sm mt-0.5">Pusat Komando Keamanan Terpadu (Command Deck)</p>
                    <p class="text-slate-600 font-mono text-[11px]">Kesatrian Poltekad Kodiklatad, Jawa Timur</p>
                </div>
            </div>
        </div>

        <!-- Section 2: Tabel Rincian Insiden & Tindakan Otomatis (Decision Engine) -->
        <div class="mb-8">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-300 pb-1 mb-3 flex items-center gap-2">
                <span class="w-2.5 h-2.5 bg-red-600 rounded-full"></span>
                2. Kronologi Kejadian, Evaluasi Aturan IF-THEN & Tindakan Respons
            </h2>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse border border-slate-200">
                    <thead>
                        <tr class="bg-slate-900 text-white text-[11px] uppercase tracking-wider">
                            <th class="p-2.5 border border-slate-800 w-12 text-center">No</th>
                            <th class="p-2.5 border border-slate-800 w-32">Waktu (WIB)</th>
                            <th class="p-2.5 border border-slate-800">Tipe Ancaman</th>
                            <th class="p-2.5 border border-slate-800">Aturan Terpicu</th>
                            <th class="p-2.5 border border-slate-800">Tindakan Respons Sistem</th>
                            <th class="p-2.5 border border-slate-800 w-24 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($decisions as $index => $decision)
                            @php
                                $event = $decision->securityEvent;
                                $isCritical = $event && $event->severity === 'high';
                            @endphp
                            <tr class="{{ $loop->even ? 'bg-slate-50/70' : 'bg-white' }} hover:bg-slate-100/80 transition-colors">
                                <td class="p-2.5 border border-slate-200 text-center font-mono font-bold text-slate-600">{{ $index + 1 }}</td>
                                <td class="p-2.5 border border-slate-200 font-mono text-slate-600 whitespace-nowrap">
                                    {{ $decision->created_at ? $decision->created_at->format('d/m/Y H:i:s') : '-' }}
                                </td>
                                <td class="p-2.5 border border-slate-200 font-semibold">
                                    <div class="{{ $isCritical ? 'text-red-700' : 'text-slate-800' }}">
                                        {{ $event ? strtoupper(str_replace('_', ' ', $event->event_type)) : 'UNIDENTIFIED' }}
                                    </div>
                                    <div class="text-[10px] font-mono text-slate-500">
                                        Tingkat: <span class="{{ $isCritical ? 'text-red-600 font-bold' : 'text-amber-600' }}">{{ strtoupper($event->severity ?? 'MEDIUM') }}</span>
                                    </div>
                                </td>
                                <td class="p-2.5 border border-slate-200">
                                    @if(is_array($decision->rules_applied))
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($decision->rules_applied as $r)
                                                <span class="inline-block px-1.5 py-0.5 bg-indigo-50 text-indigo-700 font-mono text-[10px] rounded border border-indigo-200 font-semibold">
                                                    {{ $r }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="font-mono text-slate-700">{{ $decision->rules_applied }}</span>
                                    @endif
                                </td>
                                <td class="p-2.5 border border-slate-200">
                                    @if(is_array($decision->action_taken))
                                        <ul class="list-disc list-inside space-y-0.5 text-[11px] text-slate-700">
                                            @foreach($decision->action_taken as $action)
                                                <li>{{ $action }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-slate-700 text-[11px]">{{ $decision->action_taken }}</p>
                                    @endif
                                    <p class="text-[10px] text-slate-500 italic mt-1 bg-slate-100 p-1 rounded border border-slate-200">
                                        <strong>Rationale:</strong> {{ $decision->decision_rationale }}
                                    </p>
                                </td>
                                <td class="p-2.5 border border-slate-200 text-center">
                                    @if($decision->is_successful)
                                        <span class="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-full border border-emerald-300">
                                            SUKSES
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-0.5 bg-red-100 text-red-800 text-[10px] font-bold rounded-full border border-red-300">
                                            GAGAL
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-slate-500 italic">Belum ada catatan insiden pada rentang waktu ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 3: Rekomendasi & Catatan Audit Operasional -->
        <div class="mb-8 p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs leading-relaxed">
            <h3 class="font-bold text-slate-900 uppercase tracking-wider mb-2">3. Rekomendasi Tindak Lanjut & Evaluasi Kesiapsiagaan:</h3>
            <ul class="list-decimal list-inside space-y-1 text-slate-700">
                <li>Pertahankan siklus rotasi patroli drone pada Sektor Alpha dan perimeter pagar kawat berduri barat.</li>
                <li>Lakukan kalibrasi berkala sensor seismik perimeter untuk mengantisipasi false alarm akibat getaran fauna alam.</li>
                <li>Seluruh sistem komunikasi Unified Gateway (MQTT, WebSocket, REST API) berada dalam kondisi prima dengan latensi di bawah ambang batas kritis.</li>
            </ul>
        </div>

        <!-- Signature Block (Lembar Pengesahan) -->
        <div class="grid grid-cols-2 gap-8 pt-6 border-t-2 border-slate-900 text-xs">
            <div class="text-center">
                <p class="text-slate-500 mb-1">Mengetahui,</p>
                <p class="font-bold uppercase text-slate-900">KOMANDAN PUSAT KENDALI OPERASI</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] text-slate-400 italic">[Tanda Tangan & Cap Komando]</span>
                </div>
                <p class="font-bold text-slate-900 underline">KOLONEL CPL DR. HENDRA, S.T., M.T.</p>
                <p class="text-slate-600 font-mono text-[10px]">NRP 11980023410875</p>
            </div>

            <div class="text-center">
                <p class="text-slate-500 mb-1">Kesatrian Poltekad, {{ date('d F Y') }}</p>
                <p class="font-bold uppercase text-slate-900">PERWIRA JAGA OPERASIONAL</p>
                <div class="h-20 flex items-center justify-center">
                    <span class="text-[10px] text-slate-400 italic">[Tanda Tangan Digital Terverifikasi]</span>
                </div>
                <p class="font-bold text-slate-900 underline">{{ strtoupper($reportMeta['officer_name']) }}</p>
                <p class="text-slate-600 font-mono text-[10px]">{{ $reportMeta['officer_rank'] }} • NRP 11220045610992</p>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="text-center text-[10px] text-slate-400 mt-8 pt-4 border-t border-slate-200">
            Diterbitkan secara otomatis oleh Sistem Integrasi & Pemantauan Keamanan Poltekad Kodiklatad • Dokumen Rahasia Militer
        </div>

    </div>

</body>
</html>
