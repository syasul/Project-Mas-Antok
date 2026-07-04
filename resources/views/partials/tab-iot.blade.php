<div id="tab-content-iot" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Unified Gateway status (Span 7) -->
        <div class="lg:col-span-7">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Status Node Gateway & Rute Komunikasi</h3>
                
                <div class="grid grid-cols-3 gap-4 text-center font-mono mb-4 text-xs">
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-slate-400 uppercase mb-1">MQTT Traffic</span>
                        <span id="tab-iot-mqtt" class="font-bold text-slate-700">35%</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-slate-400 uppercase mb-1">WS Traffic</span>
                        <span id="tab-iot-ws" class="font-bold text-slate-700">40%</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-slate-400 uppercase mb-1">REST Traffic</span>
                        <span id="tab-iot-rest" class="font-bold text-slate-700">25%</span>
                    </div>
                </div>

                <p class="text-xs text-slate-500 font-medium">Dashboard komunikasi menyatukan seluruh paket protokol IoT militer dalam satu gerbang penerimaan (Unified Gateway). Seluruh paket dari Kamera AI, Drone, Perimeter Sensor, dan Turret dirutekan secara otomatis.</p>
            </div>
        </div>

        <!-- DDoS mitigation controls (Span 5) -->
        <div class="lg:col-span-5">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Pengendalian & Keamanan DDoS</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-semibold">Firewall DDoS Mitigation:</span>
                        <button onclick="toggleDdosSimulation()" id="btn-toggle-ddos-tab" class="px-3 py-1.5 border border-slate-200 rounded-xl font-bold font-mono transition-all text-[11px] shadow-sm bg-white hover:bg-slate-50">
                            Mulai Simulasi DDoS
                        </button>
                    </div>

                    <div class="text-xs p-3 border border-slate-100 bg-slate-50 rounded-xl">
                        <span class="block font-bold text-slate-700 mb-1">Log DDoS firewall:</span>
                        <span id="tab-ddos-log-info" class="text-slate-500 font-medium font-mono text-[10px]">Normal state. No malicious packets detected.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
