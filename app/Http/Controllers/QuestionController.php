<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Inertia\Inertia;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Exam $exam)
    {
        return Inertia::render('Question/Create', [
            'exam' => $exam
        ]);
    }

    public function store(Request $request, Exam $exam)
    {
        $request->validate([
            'type' => 'required|in:pilihan_ganda,isian,essay',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
        ]);

        $question = $exam->questions()->create([
            'type' => $request->type,
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);

        if ($request->type === 'pilihan_ganda') {
            $request->validate([
                'answers' => 'required|array|min:2',
                'correct_answer' => 'required|integer'
            ]);

            foreach ($request->answers as $index => $answerText) {
                if (!empty($answerText)) {
                    $question->answers()->create([
                        'answer_text' => $answerText,
                        'is_correct' => (int) $request->correct_answer === $index
                    ]);
                }
            }
        }

        elseif ($request->type === 'isian') {
            $request->validate([
                'keyword_answer' => 'required|string'
            ]);

            $question->answers()->create([
                'answer_text' => $request->keyword_answer,
                'is_correct' => true
            ]);
        }


        return redirect()->route('exams.show', $exam->id);
    }

    public function edit(Exam $exam, Question $question)
    {
        // Tarik soal beserta jawabannya untuk diisi ke form
        $question->load('answers');

        return Inertia::render('Question/Edit', [
            'exam' => $exam,
            'question' => $question
        ]);
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
        ]);

        $question->update([
            'question_text' => $request->question_text,
            'points' => $request->points,
        ]);

        if ($question->type === 'pilihan_ganda') {
            $request->validate([
                'answers' => 'required|array|min:2',
                'correct_answer' => 'required|integer'
            ]);

            $question->answers()->delete();

            foreach ($request->answers as $index => $answerText) {
                if (!empty($answerText)) {
                    $question->answers()->create([
                        'answer_text' => $answerText,
                        'is_correct' => (int) $request->correct_answer === $index
                    ]);
                }
            }
        }
        elseif ($question->type === 'isian') {
            $request->validate([
                'keyword_answer' => 'required|string'
            ]);

            $question->answers()->delete();

            $question->answers()->create([
                'answer_text' => $request->keyword_answer,
                'is_correct' => true
            ]);
        }

        return redirect()->route('exams.show', $exam->id);
    }

    public function destroy(Exam $exam, Question $question)
    {
        $question->delete();
        return redirect()->back();
    }
}
