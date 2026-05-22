<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Answer;
use App\Models\ExamAttempt;
use App\Models\StudentResponse;
use Inertia\Inertia;
use Illuminate\Http\Request;

class StudentExamController extends Controller
{
    public function lobby(Exam $exam)
    {
        $hasAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($hasAttempt) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Student/Lobby', [
            'exam' => $exam
        ]);
    }

    public function start(Exam $exam)
    {
        $hasAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($hasAttempt) {
            return redirect()->route('dashboard');
        }

        $questions = $exam->questions()->with(['answers' => function($query) {
            $query->select('id', 'question_id', 'answer_text');
        }])->get();

        return Inertia::render('Student/TakeExam', [
            'exam' => $exam,
            'questions' => $questions
        ]);
    }

    public function submit(Request $request, Exam $exam)
    {
        $responses = $request->input('responses', []);
        $totalScore = 0;
        $hasEssay = false;

        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => auth()->id(),
            'total_score' => 0,
            'status' => 'menunggu_koreksi'
        ]);

        foreach ($responses as $questionId => $studentAnswer) {
            $question = Question::find($questionId);
            if (!$question) continue;

            $score = 0;
            $answerId = null;
            $answerText = null;

            if ($question->type === 'pilihan_ganda') {
                $answerId = $studentAnswer;
                $isCorrect = Answer::where('id', $answerId)->where('is_correct', true)->exists();
                if ($isCorrect) {
                    $score = $question->points;
                }
            }
            elseif ($question->type === 'isian') {
                $answerText = $studentAnswer;
                $correctAnswer = Answer::where('question_id', $question->id)->where('is_correct', true)->first();

                if ($correctAnswer && !empty($studentAnswer)) {
                    $studentAnswerLower = strtolower(trim($studentAnswer));
                    $keywords = explode(',', strtolower($correctAnswer->answer_text));

                    foreach ($keywords as $keyword) {
                        $keyword = trim($keyword);
                        if (!empty($keyword) && str_contains($studentAnswerLower, $keyword)) {
                            $score = $question->points;
                            break;
                        }
                    }
                }
            }
            elseif ($question->type === 'essay') {
                $answerText = $studentAnswer;
                $hasEssay = true;
            }

            $totalScore += $score;

            StudentResponse::create([
                'exam_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer_id' => $answerId,
                'answer_text' => $answerText,
                'score' => $score
            ]);
        }

        $attempt->update([
            'total_score' => $totalScore,
            'status' => $hasEssay ? 'menunggu_koreksi' : 'selesai'
        ]);

        return redirect()->route('dashboard');
    }
}
