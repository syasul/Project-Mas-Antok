<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SusResponse extends Model
{
    use HasFactory;

    protected $table = 'sus_responses';

    protected $fillable = [
        'respondent_name',
        'respondent_role',
        'q1',
        'q2',
        'q3',
        'q4',
        'q5',
        'q6',
        'q7',
        'q8',
        'q9',
        'q10',
        'final_score',
        'grade',
        'adjective_rating',
        'feedback',
    ];

    protected $casts = [
        'final_score' => 'float',
        'q1' => 'integer',
        'q2' => 'integer',
        'q3' => 'integer',
        'q4' => 'integer',
        'q5' => 'integer',
        'q6' => 'integer',
        'q7' => 'integer',
        'q8' => 'integer',
        'q9' => 'integer',
        'q10' => 'integer',
    ];

    /**
     * Calculate SUS Score and Grade based on John Brooke's standard algorithm.
     * Odd questions (1, 3, 5, 7, 9): score contribution = response - 1
     * Even questions (2, 4, 6, 8, 10): score contribution = 5 - response
     * Total SUS Score = sum of contributions * 2.5 (0 to 100)
     */
    public static function calculateScore(array $answers): array
    {
        $oddSum = 0;
        $evenSum = 0;

        for ($i = 1; $i <= 10; $i++) {
            $val = intval($answers['q' . $i] ?? 3);
            if ($i % 2 === 1) {
                // Odd
                $oddSum += ($val - 1);
            } else {
                // Even
                $evenSum += (5 - $val);
            }
        }

        $finalScore = ($oddSum + $evenSum) * 2.5;

        // Determine Grade and Adjective Rating
        $grade = 'F';
        $adjective = 'Poor';

        if ($finalScore >= 85.0) {
            $grade = 'A+';
            $adjective = 'Best Imaginable';
        } elseif ($finalScore >= 80.3) {
            $grade = 'A';
            $adjective = 'Excellent';
        } elseif ($finalScore >= 74.0) {
            $grade = 'B';
            $adjective = 'Good';
        } elseif ($finalScore >= 68.0) {
            $grade = 'C';
            $adjective = 'OK';
        } elseif ($finalScore >= 51.0) {
            $grade = 'D';
            $adjective = 'Poor';
        } else {
            $grade = 'F';
            $adjective = 'Worst Imaginable';
        }

        return [
            'score' => round($finalScore, 2),
            'grade' => $grade,
            'adjective' => $adjective,
        ];
    }
}
