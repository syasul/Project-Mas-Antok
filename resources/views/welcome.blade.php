<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Integrasi Keamanan - Poltekad</title>
    <!-- Google Fonts: Montserrat & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for professional UI Icons -->
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
                        'glow-emerald': '0 0 15px rgba(16, 185, 129, 0.3)',
                        'glow-rose': '0 0 15px rgba(244, 63, 94, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
        /* Modern Premium Light Mode Glassmorphism & Shadows */
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px) saturate(190%);
            -webkit-backdrop-filter: blur(20px) saturate(190%);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 25px -5px rgba(148, 163, 184, 0.08), 0 8px 10px -6px rgba(148, 163, 184, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card-hover:hover {
            border-color: rgba(99, 102, 241, 0.35);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.1), 0 10px 10px -6px rgba(99, 102, 241, 0.05);
            background: rgba(255, 255, 255, 0.85);
            transform: translateY(-2px);
        }
        /* Grid pattern mimicking structural blueprint in light slate */
        .grid-blueprint {
            background-size: 24px 24px;
            background-image: 
                linear-gradient(to right, rgba(148, 163, 184, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(148, 163, 184, 0.04) 1px, transparent 1px);
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Test highlight guides */
        .guide-highlight {
            animation: guide-pulse 1.5s infinite alternate;
        }
        @keyframes guide-pulse {
            0% {
                box-shadow: 0 0 0 0px rgba(99, 102, 241, 0.6);
                border-color: rgba(99, 102, 241, 1);
            }
            100% {
                box-shadow: 0 0 0 8px rgba(99, 102, 241, 0.15);
                border-color: rgba(99, 102, 241, 0.4);
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased min-h-screen flex overflow-x-hidden relative grid-blueprint">

    <!-- Ambient glowing nodes in background -->
    <div class="absolute top-20 left-64 w-96 h-96 bg-indigo-200/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-20 right-10 w-[500px] h-[500px] bg-cyan-200/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- CONNECTION ERROR BANNER -->
    <div id="connection-error-banner" class="hidden fixed top-0 left-0 w-full bg-rose-600 text-white text-center py-2.5 z-50 font-bold border-b border-rose-700 shadow-md transition-all duration-300">
        <i class="fa-solid fa-triangle-exclamation mr-2 animate-bounce"></i> 
        KONEKSI PUTUS: SERVER OFFLINE ATAU DB OVERLOAD. MENJALANKAN SISTEM CADANGAN LOKAL.
    </div>

    <!-- LEFT SIDEBAR -->
    @include('partials.sidebar')

    <!-- RIGHT CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

        <!-- HEADER -->
        @include('partials.header')

        <!-- CONTAINER CONTENT WITH DYNAMIC TABS -->
        <main class="w-full px-4 md:px-8 py-6">

            <!-- TAB 1: OVERVIEW -->
            @include('partials.tab-overview')

            <!-- TAB 2: CAMERA AI -->
            @include('partials.tab-camera')

            <!-- TAB 3: DRONE PATROL -->
            @include('partials.tab-drone')

            <!-- TAB 4: PERIMETER SENSOR -->
            @include('partials.tab-perimeter')

            <!-- TAB 5: GATEWAY IOT -->
            @include('partials.tab-iot')

            <!-- TAB 6: TURRET BATTERY -->
            @include('partials.tab-turret')

            <!-- TAB 7: DECISION LOGS -->
            @include('partials.tab-decision')



        </main>

    </div>



    <!-- MAIN JAVASCRIPT CONTROL -->
    @include('partials.scripts')

</body>
</html>
