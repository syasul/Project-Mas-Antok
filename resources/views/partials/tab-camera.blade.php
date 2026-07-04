<div id="tab-content-camera" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- CCTV Grid Panel (Span 8) -->
        <div class="lg:col-span-8">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Kisi Pemantauan Kamera</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative bg-slate-900 border border-slate-200 rounded-xl overflow-hidden aspect-video shadow-inner">
                        <canvas id="tab-camera-canvas-1" class="w-full h-full block bg-slate-950"></canvas>
                        <div class="absolute top-2 left-2 bg-slate-900/80 text-[9px] text-rose-500 font-bold px-2 py-0.5 rounded border border-rose-500/20 font-mono">CAM_NORTH_ALPHA</div>
                    </div>
                    <div class="relative bg-slate-900 border border-slate-200 rounded-xl overflow-hidden aspect-video shadow-inner flex items-center justify-center">
                        <i class="fa-solid fa-video-slash text-slate-700 text-2xl"></i>
                        <div class="absolute top-2 left-2 bg-slate-900/80 text-[9px] text-slate-500 font-bold px-2 py-0.5 rounded border border-slate-800 font-mono">CAM_SOUTH_BETA (STB)</div>
                    </div>
                    <div class="relative bg-slate-900 border border-slate-200 rounded-xl overflow-hidden aspect-video shadow-inner flex items-center justify-center">
                        <i class="fa-solid fa-video-slash text-slate-700 text-2xl"></i>
                        <div class="absolute top-2 left-2 bg-slate-900/80 text-[9px] text-slate-500 font-bold px-2 py-0.5 rounded border border-slate-800 font-mono">CAM_HANGAR_03 (STB)</div>
                    </div>
                    <div class="relative bg-slate-900 border border-slate-200 rounded-xl overflow-hidden aspect-video shadow-inner flex items-center justify-center">
                        <i class="fa-solid fa-video-slash text-slate-700 text-2xl"></i>
                        <div class="absolute top-2 left-2 bg-slate-900/80 text-[9px] text-slate-500 font-bold px-2 py-0.5 rounded border border-slate-800 font-mono">CAM_HQ_CENTRAL (STB)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Config Controls (Span 4) -->
        <div class="lg:col-span-4">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Konfigurasi Pengenal AI</h3>
                <div class="space-y-4">
                    <div class="text-xs">
                        <label class="block text-slate-500 font-semibold mb-1">Ambang Batas Pengenalan (Confidence):</label>
                        <input type="range" min="50" max="99" value="85" class="w-full accent-indigo-600">
                        <div class="flex justify-between text-[10px] text-slate-400 font-mono mt-1">
                            <span>50%</span>
                            <span>Current: 85%</span>
                            <span>99%</span>
                        </div>
                    </div>
                    <div class="text-xs">
                        <span class="block text-slate-500 font-semibold mb-2">Model AI:</span>
                        <label class="flex items-center gap-2 mb-1.5 cursor-pointer">
                            <input type="radio" name="ai_model" value="yolo" checked class="accent-indigo-500">
                            <span class="font-medium">YOLOv8-Tactical (Military Target Model)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="ai_model" value="ssd" class="accent-indigo-500">
                            <span class="font-medium">MobileNet-SSD (Low Power Mode)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
