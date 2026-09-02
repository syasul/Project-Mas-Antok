<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VerificationLog;
use App\Models\SusResponse;
use App\Models\UsabilitySession;
use App\Models\SensorLog;
use App\Models\SecurityEvent;
use App\Models\DecisionLog;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Letnan Dua Antok as primary operator)
        User::firstOrCreate(
            ['email' => 'operator@poltekad.mil.id'],
            [
                'name' => 'Letnan Dua Antok',
                'password' => bcrypt('poltekad123'),
                'role' => 'operator_pusat',
                'rank_title' => 'Letnan Dua Antok (Perwira Jaga CPS)',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@poltekad.mil.id'],
            [
                'name' => 'Kolonel Cpl Dr. Hendra',
                'password' => bcrypt('poltekad123'),
                'role' => 'super_admin',
                'rank_title' => 'Komandan Pusat Kendali & Siber',
            ]
        );

        // 2. Seed Real-time Face Verification Logs
        $this->seedVerificationLogs();

        // 3. Seed SUS Usability Test Benchmark Responses
        $this->seedSusResponses();

        // 4. Seed Task Completion Time (TCT) Usability Sessions
        $this->seedUsabilitySessions();

        // 5. Seed Legacy Gateway Sensor Logs
        $this->seedLegacySensors();
    }

    protected function seedVerificationLogs()
    {
        $subjects = [
            [
                'name' => 'Sersan Mayor Dua Taruna Arya Pratama',
                'nim' => '2024.01.0042',
                'category' => 'Taruna',
                'photo' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=300&auto=format&fit=crop&q=80',
                'status' => 'verified',
                'confidence' => 98.4,
                'device' => 'CAM_GATE_UTAMA_01',
                'loc' => 'Gate Utama (Pos 1 Poltekad)',
                'latency' => 22.4,
                'reason' => null
            ],
            [
                'name' => 'Sersan Taruna Dimas Wahyu Hidayat',
                'nim' => '2024.01.0089',
                'category' => 'Taruna',
                'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80',
                'status' => 'verified',
                'confidence' => 96.8,
                'device' => 'CAM_GATE_UTAMA_01',
                'loc' => 'Gate Utama (Pos 1 Poltekad)',
                'latency' => 19.8,
                'reason' => null
            ],
            [
                'name' => 'Individu Tidak Dikenal (Topi & Kacamata Hitam)',
                'nim' => 'UNREGISTERED',
                'category' => 'Tamu',
                'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80',
                'status' => 'failed',
                'confidence' => 48.2,
                'device' => 'CAM_POS_BARAT_02',
                'loc' => 'Pos Penjagaan Barat',
                'latency' => 31.2,
                'reason' => 'Wajah tidak terdaftar di database biometrik personel Poltekad'
            ],
            [
                'name' => 'Mayor Chb Denny Kurniawan',
                'nim' => 'NRP.110200847',
                'category' => 'Dosen',
                'photo' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=300&auto=format&fit=crop&q=80',
                'status' => 'verified',
                'confidence' => 99.1,
                'device' => 'CAM_LAB_KOMPUTER_04',
                'loc' => 'Gedung Lab Cyber & Rekayasa',
                'latency' => 16.5,
                'reason' => null
            ],
            [
                'name' => 'Kopral Taruna Rizky Ramadhan',
                'nim' => '2024.01.0112',
                'category' => 'Taruna',
                'photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&auto=format&fit=crop&q=80',
                'status' => 'verified',
                'confidence' => 94.7,
                'device' => 'CAM_BARAK_TARUNA_03',
                'loc' => 'Pintu Masuk Barak Taruna',
                'latency' => 24.1,
                'reason' => null
            ],
            [
                'name' => 'Sersan Satu Bambang Trihatmojo',
                'nim' => 'NRP.211900142',
                'category' => 'Staf Militer',
                'photo' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=300&auto=format&fit=crop&q=80',
                'status' => 'verified',
                'confidence' => 97.6,
                'device' => 'CAM_GATE_UTAMA_01',
                'loc' => 'Gate Utama (Pos 1 Poltekad)',
                'latency' => 20.3,
                'reason' => null
            ],
            [
                'name' => 'Sersan Taruna Fajar Nugraha',
                'nim' => '2024.01.0067',
                'category' => 'Taruna',
                'photo' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=300&auto=format&fit=crop&q=80',
                'status' => 'pending',
                'confidence' => 81.5,
                'device' => 'CAM_POS_BARAT_02',
                'loc' => 'Pos Penjagaan Barat',
                'latency' => 45.8,
                'reason' => 'Pencahayaan rendah pada kamera tepi, menunggu konfirmasi visual operator'
            ],
        ];

        foreach ($subjects as $idx => $s) {
            VerificationLog::create([
                'subject_name' => $s['name'],
                'nim' => $s['nim'],
                'category' => $s['category'],
                'photo_url' => $s['photo'],
                'status' => $s['status'],
                'confidence_score' => $s['confidence'],
                'device_id' => $s['device'],
                'location' => $s['loc'],
                'latency_ms' => $s['latency'],
                'failure_reason' => $s['reason'],
                'metadata' => [
                    'fps' => 30,
                    'liveness_detected' => $s['status'] === 'verified',
                    'illumination_lux' => rand(400, 750),
                    'pitch' => rand(-5, 5),
                    'yaw' => rand(-8, 8),
                ],
                'created_at' => Carbon::now()->subMinutes(($idx + 1) * 4),
            ]);
        }
    }

    protected function seedSusResponses()
    {
        // 5 Sample SUS responses from Poltekad operators
        $samples = [
            [
                'name' => 'Letnan Dua Antok',
                'role' => 'Perwira Jaga Komando',
                'answers' => ['q1'=>5, 'q2'=>1, 'q3'=>5, 'q4'=>1, 'q5'=>4, 'q6'=>1, 'q7'=>5, 'q8'=>1, 'q9'=>5, 'q10'=>1],
                'feedback' => 'Tata letak thumb zone di tablet sangat membantu patroli. Indikator status warna hijau/merah langsung terbaca tanpa bingung.'
            ],
            [
                'name' => 'Sersan Kepala M. Yusuf',
                'role' => 'Operator Pos Gerbang',
                'answers' => ['q1'=>4, 'q2'=>2, 'q3'=>4, 'q4'=>1, 'q5'=>5, 'q6'=>1, 'q7'=>4, 'q8'=>2, 'q9'=>4, 'q10'=>1],
                'feedback' => 'Respon WebSocket sangat cepat tanpa reload halaman. Informasi confidence score jelas.'
            ],
            [
                'name' => 'Kopral Satu Rian Pratama',
                'role' => 'Operator Patroli Lapangan',
                'answers' => ['q1'=>5, 'q2'=>1, 'q3'=>5, 'q4'=>2, 'q5'=>4, 'q6'=>2, 'q7'=>5, 'q8'=>1, 'q9'=>4, 'q10'=>2],
                'feedback' => 'Tombol aksi cepat di bagian bawah layar HP sangat ergonomis.'
            ],
            [
                'name' => 'Letnan Satu Cpl Heri Santoso',
                'role' => 'Perwira Siber & Komunikasi',
                'answers' => ['q1'=>4, 'q2'=>2, 'q3'=>4, 'q4'=>1, 'q5'=>4, 'q6'=>1, 'q7'=>4, 'q8'=>1, 'q9'=>4, 'q10'=>2],
                'feedback' => 'Sistem integrasi stabil dan data latency real-time tercatat dengan akurat.'
            ],
            [
                'name' => 'Sersan Satu Dedy Wahyudi',
                'role' => 'Operator Barak Taruna',
                'answers' => ['q1'=>5, 'q2'=>1, 'q3'=>5, 'q4'=>1, 'q5'=>5, 'q6'=>1, 'q7'=>5, 'q8'=>1, 'q9'=>5, 'q10'=>1],
                'feedback' => 'Sangat mudah dipelajari oleh operator baru dalam waktu kurang dari 5 menit.'
            ],
        ];

        foreach ($samples as $s) {
            $calc = SusResponse::calculateScore($s['answers']);
            SusResponse::create([
                'respondent_name' => $s['name'],
                'respondent_role' => $s['role'],
                'q1' => $s['answers']['q1'],
                'q2' => $s['answers']['q2'],
                'q3' => $s['answers']['q3'],
                'q4' => $s['answers']['q4'],
                'q5' => $s['answers']['q5'],
                'q6' => $s['answers']['q6'],
                'q7' => $s['answers']['q7'],
                'q8' => $s['answers']['q8'],
                'q9' => $s['answers']['q9'],
                'q10' => $s['answers']['q10'],
                'final_score' => $calc['score'],
                'grade' => $calc['grade'],
                'adjective_rating' => $calc['adjective'],
                'feedback' => $s['feedback'],
            ]);
        }
    }

    protected function seedUsabilitySessions()
    {
        $sessions = [
            ['op' => 'Letnan Dua Antok', 'code' => 'T1', 'name' => 'Identifikasi Log Verifikasi Gagal Terkini', 'time' => 3.4, 'err' => 0],
            ['op' => 'Letnan Dua Antok', 'code' => 'T2', 'name' => 'Filter Riwayat Verifikasi Berdasarkan Sektor Gate Utama', 'time' => 4.8, 'err' => 0],
            ['op' => 'Sersan Kepala M. Yusuf', 'code' => 'T1', 'name' => 'Identifikasi Log Verifikasi Gagal Terkini', 'time' => 4.2, 'err' => 0],
            ['op' => 'Sersan Kepala M. Yusuf', 'code' => 'T3', 'name' => 'Lakukan Tindakan Manual Override / Verifikasi Akses', 'time' => 5.1, 'err' => 0],
            ['op' => 'Kopral Satu Rian Pratama', 'code' => 'T4', 'name' => 'Melihat Rekapitulasi Rata-rata Latensi WebSocket', 'time' => 3.8, 'err' => 1],
        ];

        foreach ($sessions as $s) {
            UsabilitySession::create([
                'operator_name' => $s['op'],
                'task_code' => $s['code'],
                'task_name' => $s['name'],
                'start_time' => Carbon::now()->subMinutes(30),
                'end_time' => Carbon::now()->subMinutes(30)->addSeconds($s['time']),
                'completion_time_sec' => $s['time'],
                'error_count' => $s['err'],
                'clicks_count' => rand(2, 5),
                'status' => 'completed',
            ]);
        }
    }

    protected function seedLegacySensors()
    {
        $sensorTypes = ['camera', 'drone', 'perimeter', 'iot', 'turret'];
        foreach ($sensorTypes as $type) {
            SensorLog::create([
                'sensor_type' => $type,
                'sensor_name' => strtoupper($type) . '_CPS_NODE_01',
                'protocol' => 'WEBSOCKET',
                'data' => ['status' => 'normal', 'health' => 'optimal', 'sector' => 'Alpha'],
                'latency_ms' => rand(12, 35),
            ]);
        }
    }
}
