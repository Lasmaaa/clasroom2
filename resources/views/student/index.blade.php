<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/user-side.css') }}">
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

                    <div class="bg-white rounded-2xl shadow p-8 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Pievienoties klasei</h2>

                        <form method="POST" action="{{ route('student.classes.join') }}" class="flex flex-col sm:flex-row gap-3">
                            @csrf
                            <input type="text"
                                   name="class_code"
                                   value="{{ old('class_code') }}"
                                   placeholder="Ievadi klases kodu"
                                   maxlength="20"
                                   class="flex-1 p-4 border border-gray-300 rounded-2xl uppercase focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button type="submit"
                                    class="px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl">
                                Pievienoties
                            </button>
                        </form>

                        @error('class_code')
                            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if (session('status') === 'class-joined')
                            <p class="mt-3 text-sm text-green-600 font-medium">Tu veiksmigi pievienojies klasei.</p>
                        @elseif (session('status') === 'class-already-joined')
                            <p class="mt-3 text-sm text-gray-600">Tu jau esi pievienojies sai klasei.</p>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow p-8 mb-6">
                        <h2 class="text-xl font-semibold mb-4">Manas klases</h2>

                        @if($joinedClasses->isEmpty())
                            <p class="text-gray-500">Tu vel neesi pievienojies nevienai klasei.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($joinedClasses as $class)
                                    <a href="{{ route('student.classes.show', $class) }}"
                                       class="block border border-gray-200 rounded-2xl p-4 hover:shadow-md transition"
                                       style="border-left: 6px solid {{ $class->color ?? '#3b82f6' }}">
                                        <h3 class="font-semibold text-gray-900">{{ $class->class_name }}</h3>
                                        <p class="text-sm text-gray-500">Skolotājs: {{ $class->teacher?->name ?? 'Nav norādīts' }}</p>
                                        <p class="text-sm text-gray-500">{{ $class->tasks->count() }} uzdevumi</p>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow p-8 min-h-[500px]">
                        <h2 class="text-xl font-semibold mb-6">Uzdevumi</h2>

                        @if($tasks->isEmpty())
                            <p class="text-gray-500">Pagaidam nav pieejamu uzdevumu.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($tasks as $task)
                                    <a href="{{ route('student.tasks.show', $task) }}"
                                       class="block border border-gray-200 rounded-2xl p-5 hover:shadow-md transition"
                                       style="border-left: 6px solid {{ $task->color ?? '#3b82f6' }}">
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                            <div>
                                                <h3 class="font-semibold text-lg text-gray-900">{{ $task->name }}</h3>
                                                <p class="text-sm text-gray-500">
                                                    {{ $task->classInfo?->class_name ?? 'Klase nav noradita' }}
                                                </p>
                                            </div>
                                            <span class="text-sm font-medium {{ $task->isOverdue() ? 'text-red-600' : 'text-gray-600' }}">
                                                {{ $task->formatted_due_date }}
                                            </span>
                                        </div>

                                        @if($task->description)
                                            <p class="text-gray-600 mt-4">{{ $task->description }}</p>
                                        @endif

                                        @if($task->isNotStartedYet())
                                            <p class="mt-4 text-sm text-gray-500">Uzdevums vel nav sacies.</p>
                                        @elseif($task->isOverdue())
                                            <p class="mt-4 text-sm text-red-600">Termins ir beidzies.</p>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="w-full lg:w-1/3 xl:w-[380px] flex-shrink-0 right-container">
                    <x-user-side-panel />

                    @php
                        $completedDates = $tasks
                            ->whereNotNull('completed_at')
                            ->groupBy(fn($task) => $task->completed_at->format('Y-m-d'))
                            ->map(fn($group) => $group->values()->map(fn($task) => [
                                'title' => $task->name,
                                'description' => $task->description ?? '',
                                'completed_at' => $task->completed_at,
                            ])->values())
                            ->toArray();
                    @endphp

                    <x-calendar
                        :completedDates="$completedDates"
                        :dueDates="$dueDates ?? []"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
