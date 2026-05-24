<?php

namespace App\Http\Controllers;

use App\Models\ClassInfo;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentClassController extends Controller
{
    public function index(Request $request): View
    {
        $joinedClasses = $request->user()
            ->joinedClasses()
            ->with(['tasks', 'teacher'])
            ->orderBy('class_name')
            ->get();

        $classIds = $joinedClasses->pluck('id');

        $tasks = Task::with('classInfo')
            ->whereIn('class_info_id', $classIds)
            ->orderByRaw('due_date is null')
            ->orderBy('due_date')
            ->latest()
            ->get();

        $dueDates = $tasks
            ->whereNotNull('due_date')
            ->groupBy(fn(Task $task) => $task->due_date->format('Y-m-d'))
            ->map(fn($group) => $group->values()->map(fn(Task $task) => [
                'title' => $task->name,
                'description' => $task->description ?? '',
                'class_name' => $task->classInfo?->class_name,
                'url' => route('student.tasks.show', $task),
                'due_date' => $task->due_date,
            ])->values())
            ->toArray();

        return view('student.index', compact('joinedClasses', 'tasks', 'dueDates'));
    }

    public function showClass(Request $request, ClassInfo $classInfo): View
    {
        $this->authorizeJoinedClass($request, $classInfo);

        $classInfo->load(['teacher', 'tasks']);

        return view('student.class-show', ['class' => $classInfo]);
    }

    public function showTask(Request $request, Task $task): View
    {
        $task->load('classInfo.teacher');

        if (! $task->classInfo) {
            abort(404);
        }

        $this->authorizeJoinedClass($request, $task->classInfo);

        $submission = $task->submissionFor($request->user());

        return view('student.task-show', compact('task', 'submission'));
    }

    public function submitTask(Request $request, Task $task): RedirectResponse
    {
        $task->load('classInfo');

        if (! $task->classInfo) {
            abort(404);
        }

        $this->authorizeJoinedClass($request, $task->classInfo);

        if (! $task->canBeSubmitted()) {
            return back()->withErrors([
                'submission' => 'Termiņš ir beidzies, darbu vairs nevar iesniegt.',
            ]);
        }

        $validated = $request->validate([
            'submission_type' => ['required', Rule::in(['text', 'image', 'drive', 'finished'])],
            'text_answer' => ['required_if:submission_type,text', 'nullable', 'string'],
            'image' => ['required_if:submission_type,image', 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'drive_url' => ['required_if:submission_type,drive', 'nullable', 'url', 'max:2048'],
        ]);

        $submission = TaskSubmission::firstOrNew([
            'task_id' => $task->id,
            'user_id' => $request->user()->id,
        ]);

        if ($submission->image_path && ($validated['submission_type'] !== 'image' || $request->hasFile('image'))) {
            Storage::disk('public')->delete($submission->image_path);
        }

        $submission->fill([
            'submission_type' => $validated['submission_type'],
            'status' => 'completed',
            'text_answer' => $validated['submission_type'] === 'text' ? $validated['text_answer'] : null,
            'drive_url' => $validated['submission_type'] === 'drive' ? $validated['drive_url'] : null,
            'submitted_at' => now(),
        ]);

        if ($validated['submission_type'] !== 'image') {
            $submission->image_path = null;
        }

        if ($validated['submission_type'] === 'image' && $request->hasFile('image')) {
            $submission->image_path = $request->file('image')->store('task-submissions', 'public');
        }

        $submission->save();

        return redirect()
            ->route('student.tasks.show', $task)
            ->with('status', 'task-submitted');
    }

    public function join(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'class_code' => ['required', 'string', 'max:20'],
        ]);

        $classCode = strtoupper(trim($validated['class_code']));

        $class = ClassInfo::where('class_code', $classCode)->first();

        if (! $class) {
            return back()
                ->withInput()
                ->withErrors(['class_code' => 'Klase ar sadu kodu netika atrasta.']);
        }

        if ($request->user()->joinedClasses()->whereKey($class->id)->exists()) {
            return back()->with('status', 'class-already-joined');
        }

        $request->user()->joinedClasses()->attach($class->id);

        return redirect()
            ->route('student.index')
            ->with('status', 'class-joined');
    }

    private function authorizeJoinedClass(Request $request, ClassInfo $classInfo): void
    {
        if (! $request->user()->joinedClasses()->whereKey($classInfo->id)->exists()) {
            abort(403);
        }
    }
}
