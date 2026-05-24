<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 lg:px-8">
            <a href="{{ route('student.index') }}" class="inline-block mb-6 text-sm font-medium text-gray-600 hover:text-gray-900">
                Atpakaļ uz dashboard
            </a>

            <div class="bg-white rounded-2xl shadow p-8 mb-6"
                 style="border-left: 8px solid {{ $class->color ?? '#3b82f6' }}">
                <h1 class="text-3xl font-bold text-gray-900">{{ $class->class_name }}</h1>
                <p class="mt-2 text-gray-600">Skolotājs: {{ $class->teacher?->name ?? 'Nav norādīts' }}</p>
                <p class="mt-1 text-sm text-gray-500">Klases kods: {{ $class->class_code }}</p>
            </div>

            <div class="bg-white rounded-2xl shadow p-8">
                <h2 class="text-xl font-semibold mb-6">Uzdevumi</h2>

                @if($class->tasks->isEmpty())
                    <p class="text-gray-500">Šai klasei vēl nav uzdevumu.</p>
                @else
                    <div class="space-y-4">
                        @foreach($class->tasks as $task)
                            <a href="{{ route('student.tasks.show', $task) }}"
                               class="block border border-gray-200 rounded-2xl p-5 hover:shadow-md transition"
                               style="border-left: 6px solid {{ $task->color ?? '#3b82f6' }}">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                    <div>
                                        <h3 class="font-semibold text-lg text-gray-900">{{ $task->name }}</h3>
                                        @if($task->description)
                                            <p class="text-gray-600 mt-2 line-clamp-2">{{ $task->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-600' }}">
                                        {{ $task->formatted_due_date }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
