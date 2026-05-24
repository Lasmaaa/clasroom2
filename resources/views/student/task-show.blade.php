<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 lg:px-8">
            <a href="{{ route('student.classes.show', $task->classInfo) }}" class="inline-block mb-6 text-sm font-medium text-gray-600 hover:text-gray-900">
                Atpakaļ uz klasi
            </a>

            <div class="bg-white rounded-2xl shadow p-8"
                 style="border-left: 8px solid {{ $task->color ?? '#3b82f6' }}">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $task->name }}</h1>
                        <p class="mt-2 text-gray-600">{{ $task->classInfo?->class_name }}</p>
                        <p class="mt-1 text-sm text-gray-500">Skolotājs: {{ $task->classInfo?->teacher?->name ?? 'Nav norādīts' }}</p>
                    </div>

                    <span class="text-sm font-semibold {{ $submission?->status === 'completed' ? 'text-green-600' : ($task->isOverdue() ? 'text-red-600' : 'text-gray-700') }}">
                        {{ $submission?->status === 'completed' ? 'Pabeigts' : 'Nav pabeigts' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xs uppercase tracking-widest text-gray-500 font-medium">Sākuma datums</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $task->formatted_start_date }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-xs uppercase tracking-widest text-gray-500 font-medium">Termiņš</p>
                        <p class="mt-1 font-semibold {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $task->formatted_due_date }}
                        </p>
                    </div>
                </div>

                @if($task->description)
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Apraksts</h2>
                        <p class="text-gray-700 whitespace-pre-line">{{ $task->description }}</p>
                    </div>
                @endif

                @if (session('status') === 'task-submitted')
                    <p class="mt-8 text-sm text-green-600 font-medium">Darbs veiksmīgi iesniegts.</p>
                @endif

                @error('submission')
                    <p class="mt-8 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if($submission)
                    <div class="mt-8 bg-green-50 border border-green-200 rounded-2xl p-5">
                        <h2 class="text-lg font-semibold text-green-900">Tavs iesniegums</h2>
                        <p class="mt-1 text-sm text-green-700">Iesniegts: {{ $submission->submitted_at?->format('d.m.Y H:i') }}</p>

                        @if($submission->submission_type === 'text')
                            <p class="mt-4 text-green-900 whitespace-pre-line">{{ $submission->text_answer }}</p>
                        @elseif($submission->submission_type === 'drive')
                            <a href="{{ $submission->drive_url }}" target="_blank" rel="noopener" class="mt-4 inline-block text-blue-700 font-semibold hover:underline">
                                Atvērt Google Drive dokumentu
                            </a>
                        @elseif($submission->submission_type === 'image')
                            <img src="{{ asset('storage/' . $submission->image_path) }}" alt="Iesniegtais darbs" class="mt-4 max-h-96 rounded-2xl border object-contain">
                        @else
                            <p class="mt-4 text-green-900">Atzīmēts kā pabeigts.</p>
                        @endif
                    </div>
                @endif

                @if($task->isNotStartedYet())
                    <p class="mt-8 text-sm text-gray-500">Uzdevums vēl nav sācies.</p>
                @elseif(! $task->canBeSubmitted())
                    <p class="mt-8 text-sm text-red-600">Termiņš ir beidzies, darbu vairs nevar iesniegt.</p>
                @else
                    <div class="mt-8 border-t pt-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Iesniegt darbu</h2>

                        <form method="POST" action="{{ route('student.tasks.submit', $task) }}" enctype="multipart/form-data" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Iesniegšanas veids</label>
                                <select name="submission_type" id="submission_type" class="w-full p-4 border border-gray-300 rounded-2xl">
                                    <option value="text" @selected(old('submission_type', $submission?->submission_type) === 'text')>Teksts</option>
                                    <option value="image" @selected(old('submission_type', $submission?->submission_type) === 'image')>Attēls</option>
                                    <option value="drive" @selected(old('submission_type', $submission?->submission_type) === 'drive')>Google Drive dokuments</option>
                                    <option value="finished" @selected(old('submission_type', $submission?->submission_type) === 'finished')>Tikai atzīmēt kā pabeigtu</option>
                                </select>
                                @error('submission_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div data-submission-field="text">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teksta atbilde</label>
                                <textarea name="text_answer" rows="5" class="w-full p-4 border border-gray-300 rounded-2xl">{{ old('text_answer', $submission?->text_answer) }}</textarea>
                                @error('text_answer')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div data-submission-field="image" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Augšupielādēt attēlu</label>
                                <input type="file" name="image" accept="image/*" class="w-full p-4 border border-gray-300 rounded-2xl">
                                @error('image')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div data-submission-field="drive" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Google Drive saite</label>
                                <input type="url" name="drive_url" value="{{ old('drive_url', $submission?->drive_url) }}" placeholder="https://drive.google.com/..."
                                       class="w-full p-4 border border-gray-300 rounded-2xl">
                                @error('drive_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl">
                                Iesniegt darbu
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('submission_type');
            const fields = document.querySelectorAll('[data-submission-field]');

            function updateFields() {
                fields.forEach(field => {
                    field.classList.toggle('hidden', field.dataset.submissionField !== select.value);
                });
            }

            select?.addEventListener('change', updateFields);
            updateFields();
        });
    </script>
</x-app-layout>
