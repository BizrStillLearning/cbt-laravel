<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradingController extends Controller
{
    public function index(Exam $exam)
    {
        $attempts = ExamAttempt::where('exam_id', $exam->id)
            ->with('user')
            ->latest()
            ->get();

        return Inertia::render('Grading/Index', [
            'exam' => $exam,
            'attempts' => $attempts
        ]);
    }

    public function show(ExamAttempt $attempt)
    {
        $attempt->load(['user', 'exam', 'responses.question']);

        return Inertia::render('Grading/Show', [
            'attempt' => $attempt
        ]);
    }

    public function update(Request $request, ExamAttempt $attempt)
    {
        $request->validate([
            'scores' => 'required|array'
        ]);

        $totalScore = 0;

        foreach ($attempt->responses as $response) {
            $questionId = $response->question_id;

            if (isset($request->scores[$questionId])) {
                $score = (int) $request->scores[$questionId];

                $maxPoints = $response->question->points;
                $score = $score > $maxPoints ? $maxPoints : $score;

                $response->update(['score' => $score]);
                $totalScore += $score;
            } else {
                $totalScore += $response->score;
            }
        }

        $attempt->update([
            'total_score' => $totalScore,
            'status' => 'selesai'
        ]);

        return redirect()->route('grading.index', $attempt->exam_id);
    }

    public function export(Exam $exam)
    {
        $attempts = ExamAttempt::where('exam_id', $exam->id)
            ->with('user')
            ->orderBy('total_score', 'desc')
            ->get();

        $filename = "Rekap_Nilai_" . str_replace(' ', '_', $exam->title) . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($attempts) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['No', 'Nama Siswa', 'Email', 'Status Koreksi', 'Tanggal Mengerjakan', 'Nilai Akhir']);

            $no = 1;
            foreach ($attempts as $attempt) {
                fputcsv($file, [
                    $no++,
                    $attempt->user->name,
                    $attempt->user->email,
                    str_replace('_', ' ', strtoupper($attempt->status)),
                    $attempt->updated_at->format('d-m-Y H:i'),
                    $attempt->total_score
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
