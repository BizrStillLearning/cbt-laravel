<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::all();

        return Inertia::render('Exam/Index', [
            'exams' => $exams
        ]);
    }
    public function create()
    {
        return Inertia::render('Exam/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        Exam::create($request->all());

        return redirect()->route('exams.index');
    }

    public function edit(Exam $exam)
    {
        return Inertia::render('Exam/Edit', [
            'exam' => $exam
        ]);
    }

    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'is_published' => 'boolean',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
        ]);

        $exam->update($request->all());

        return redirect()->route('exams.index');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return redirect()->back();
    }

    public function show(Exam $exam)
    {
        $exam->load('questions.answers');

        return Inertia::render('Exam/Show', [
            'exam' => $exam
        ]);
    }


}


