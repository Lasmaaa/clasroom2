<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/user-side.css') }}">
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 lg:px-8">
            
            <div class="flex flex-col lg:flex-row gap-6">

                <!-- KREISĀ PUSE (2/3) -->
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>
                    <!-- Šeit liksi savu galveno saturu vēlāk -->
                    <div class="bg-white rounded-2xl shadow p-8 min-h-[500px]">
                        <p class="text-gray-500">Galvenais saturs būs šeit...</p>
                    </div>
                </div>

                <!-- LABĀ PUSE (1/3) -->
                <div class="w-full lg:w-1/3 xl:w-[380px] flex-shrink-0 right-container">
                    <!-- User Panel -->
                    <x-user-side-panel />

                    <!-- Kalendārs -->
                    @php
                        $tasksByDate = \App\Models\Task::where('user_id', auth()->id())
                            ->whereNotNull('completed_at')
                            ->orderBy('completed_at', 'desc')
                            ->get()
                            ->groupBy(fn($task) => $task->completed_at->format('Y-m-d'))
                            ->map(fn($group) => $group->map(fn($task) => [
                                'title'        => $task->title,
                                'description'  => $task->description ?? '',
                                'completed_at' => $task->completed_at,
                            ]))
                            ->toArray();
                    @endphp

                    <x-calendar 
    :completedDates="$tasksByDate" 
    :dueDates="$dueDates ?? []" 
/>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>