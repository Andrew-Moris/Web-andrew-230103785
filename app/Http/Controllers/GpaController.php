<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GpaController extends Controller
{
    /**
     * Display the GPA simulation page
     */
    public function index()
    {
        return view('gpa.index');
    }

    /**
     * Calculate GPA based on submitted grades
     */
    public function calculate(Request $request): JsonResponse
    {
        $request->validate([
            'subjects' => 'required|array|min:1',
            'subjects.*.name' => 'required|string|max:255',
            'subjects.*.grade' => 'required|numeric|min:0|max:100',
            'subjects.*.credit_hours' => 'required|integer|min:1|max:6',
        ]);

        $subjects = $request->input('subjects');
        $totalPoints = 0;
        $totalCreditHours = 0;
        $processedSubjects = [];

        foreach ($subjects as $subject) {
            $grade = (float) $subject['grade'];
            $creditHours = (int) $subject['credit_hours'];
            
            // Convert percentage to GPA points (4.0 scale)
            $gradePoints = $this->convertToGpaPoints($grade);
            $letterGrade = $this->getLetterGrade($grade);
            
            $totalPoints += $gradePoints * $creditHours;
            $totalCreditHours += $creditHours;
            
            $processedSubjects[] = [
                'name' => $subject['name'],
                'grade' => $grade,
                'credit_hours' => $creditHours,
                'grade_points' => round($gradePoints, 2),
                'letter_grade' => $letterGrade,
                'quality_points' => round($gradePoints * $creditHours, 2)
            ];
        }

        $gpa = $totalCreditHours > 0 ? $totalPoints / $totalCreditHours : 0;
        
        return response()->json([
            'success' => true,
            'gpa' => round($gpa, 2),
            'total_credit_hours' => $totalCreditHours,
            'total_quality_points' => round($totalPoints, 2),
            'subjects' => $processedSubjects,
            'gpa_classification' => $this->getGpaClassification($gpa),
            'percentage_equivalent' => $this->gpaToPercentage($gpa)
        ]);
    }

    /**
     * Convert percentage grade to GPA points (4.0 scale)
     */
    private function convertToGpaPoints(float $percentage): float
    {
        if ($percentage >= 97) return 4.0;
        if ($percentage >= 93) return 3.7;
        if ($percentage >= 90) return 3.3;
        if ($percentage >= 87) return 3.0;
        if ($percentage >= 83) return 2.7;
        if ($percentage >= 80) return 2.3;
        if ($percentage >= 77) return 2.0;
        if ($percentage >= 73) return 1.7;
        if ($percentage >= 70) return 1.3;
        if ($percentage >= 67) return 1.0;
        if ($percentage >= 65) return 0.7;
        return 0.0;
    }

    /**
     * Get letter grade from percentage
     */
    private function getLetterGrade(float $percentage): string
    {
        if ($percentage >= 97) return 'A+';
        if ($percentage >= 93) return 'A';
        if ($percentage >= 90) return 'A-';
        if ($percentage >= 87) return 'B+';
        if ($percentage >= 83) return 'B';
        if ($percentage >= 80) return 'B-';
        if ($percentage >= 77) return 'C+';
        if ($percentage >= 73) return 'C';
        if ($percentage >= 70) return 'C-';
        if ($percentage >= 67) return 'D+';
        if ($percentage >= 65) return 'D';
        return 'F';
    }

    /**
     * Get GPA classification
     */
    private function getGpaClassification(float $gpa): string
    {
        if ($gpa >= 3.8) return 'امتياز مع مرتبة الشرف (Summa Cum Laude)';
        if ($gpa >= 3.5) return 'امتياز (Magna Cum Laude)';
        if ($gpa >= 3.2) return 'جيد جداً مرتفع (Cum Laude)';
        if ($gpa >= 2.8) return 'جيد جداً (Very Good)';
        if ($gpa >= 2.5) return 'جيد (Good)';
        if ($gpa >= 2.0) return 'مقبول (Acceptable)';
        if ($gpa >= 1.0) return 'ضعيف (Weak)';
        return 'راسب (Fail)';
    }

    /**
     * Convert GPA to percentage equivalent
     */
    private function gpaToPercentage(float $gpa): float
    {
        // Convert 4.0 scale GPA to percentage (approximate)
        return round(($gpa / 4.0) * 100, 1);
    }

    /**
     * Get sample data for demonstration
     */
    public function getSampleData(): JsonResponse
    {
        $sampleSubjects = [
            [
                'name' => 'الرياضيات المتقدمة',
                'grade' => 85,
                'credit_hours' => 3
            ],
            [
                'name' => 'الفيزياء العامة',
                'grade' => 78,
                'credit_hours' => 4
            ],
            [
                'name' => 'الكيمياء التحليلية',
                'grade' => 92,
                'credit_hours' => 3
            ],
            [
                'name' => 'البرمجة المتقدمة',
                'grade' => 88,
                'credit_hours' => 3
            ],
            [
                'name' => 'اللغة الإنجليزية',
                'grade' => 82,
                'credit_hours' => 2
            ]
        ];

        return response()->json([
            'success' => true,
            'sample_subjects' => $sampleSubjects
        ]);
    }
}
