<div id="sus-modal-backdrop" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-xl rounded-2xl p-6 border border-slate-200 max-h-[90vh] overflow-y-auto bg-white">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
            <h2 class="text-xs md:text-sm font-bold text-slate-850">Evaluasi Skala Kebergunaan Sistem (SUS)</h2>
            <button onclick="closeSusModal()" class="text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        
        <form id="sus-survey-form" onsubmit="calculateSusScore(event)">
            <p class="text-[10px] text-slate-400 mb-4 uppercase tracking-wider font-semibold">Tanggapan: 1 (STS - Sangat Tidak Setuju) s/d 5 (SS - Sangat Setuju)</p>
            
            <div class="space-y-4 text-[11px] md:text-xs">
                <div>
                    <p class="font-bold text-slate-700 mb-2">1. Saya merasa ingin menggunakan sistem ini lagi dalam tugas operasional harian.</p>
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-[10px] text-slate-400 font-bold">STS</span>
                        <div class="flex gap-4">
                            <label class="cursor-pointer"><input type="radio" name="sus_q1" value="1" required class="accent-indigo-500"> 1</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q1" value="2" class="accent-indigo-500"> 2</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q1" value="3" class="accent-indigo-500"> 3</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q1" value="4" class="accent-indigo-500"> 4</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q1" value="5" class="accent-indigo-500" checked> 5</label>
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold">SS</span>
                    </div>
                </div>
                <div>
                    <p class="font-bold text-slate-700 mb-2">2. Saya merasa sistem ini terlalu rumit untuk kebutuhan operator.</p>
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-[10px] text-slate-400 font-bold">STS</span>
                        <div class="flex gap-4">
                            <label class="cursor-pointer"><input type="radio" name="sus_q2" value="1" required class="accent-indigo-500" checked> 1</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q2" value="2" class="accent-indigo-500"> 2</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q2" value="3" class="accent-indigo-500"> 3</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q2" value="4" class="accent-indigo-500"> 4</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q2" value="5" class="accent-indigo-500"> 5</label>
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold">SS</span>
                    </div>
                </div>
                <div>
                    <p class="font-bold text-slate-700 mb-2">3. Saya rasa sistem ini sangat mudah untuk dioperasikan.</p>
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-[10px] text-slate-400 font-bold">STS</span>
                        <div class="flex gap-4">
                            <label class="cursor-pointer"><input type="radio" name="sus_q3" value="1" required class="accent-indigo-500"> 1</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q3" value="2" class="accent-indigo-500"> 2</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q3" value="3" class="accent-indigo-500"> 3</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q3" value="4" class="accent-indigo-500"> 4</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q3" value="5" class="accent-indigo-500" checked> 5</label>
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold">SS</span>
                    </div>
                </div>
                <div>
                    <p class="font-bold text-slate-700 mb-2">4. Saya merasa memerlukan panduan teknis yang intensif untuk menggunakannya.</p>
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-[10px] text-slate-400 font-bold">STS</span>
                        <div class="flex gap-4">
                            <label class="cursor-pointer"><input type="radio" name="sus_q4" value="1" required class="accent-indigo-500" checked> 1</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q4" value="2" class="accent-indigo-500"> 2</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q4" value="3" class="accent-indigo-500"> 3</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q4" value="4" class="accent-indigo-500"> 4</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q4" value="5" class="accent-indigo-500"> 5</label>
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold">SS</span>
                    </div>
                </div>
                <div>
                    <p class="font-bold text-slate-700 mb-2">5. Berbagai fungsi pertahanan dalam sistem ini terintegrasi dengan sangat baik.</p>
                    <div class="flex justify-between items-center gap-3">
                        <span class="text-[10px] text-slate-400 font-bold">STS</span>
                        <div class="flex gap-4">
                            <label class="cursor-pointer"><input type="radio" name="sus_q5" value="1" required class="accent-indigo-500"> 1</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q5" value="2" class="accent-indigo-500"> 2</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q5" value="3" class="accent-indigo-500"> 3</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q5" value="4" class="accent-indigo-500"> 4</label>
                            <label class="cursor-pointer"><input type="radio" name="sus_q5" value="5" class="accent-indigo-500" checked> 5</label>
                        </div>
                        <span class="text-[10px] text-slate-400 font-bold">SS</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeSusModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">Batal</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all">Kirim Tanggapan</button>
            </div>
        </form>

        <div id="sus-result-display" class="hidden mt-4 border border-indigo-100 bg-indigo-50/50 rounded-xl p-4 text-center">
            <p class="text-xs text-slate-500 mb-1">Skor SUS Terhitung:</p>
            <p class="text-3xl font-extrabold text-indigo-600" id="sus-calculated-score">90 / 100</p>
            <p class="text-[10px] text-emerald-600 mt-2 font-bold"><i class="fa-solid fa-circle-check mr-1"></i> SKOR SUS DI ATAS RATA-RATA (&ge; 80 / Target SUS &ge; 4.0 out of 5 terpenuhi!)</p>
        </div>
    </div>
</div>
