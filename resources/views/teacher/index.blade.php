@php
    // Ja kontrolieris aizmirsa iedot $classes, izveidojam tukšu kolekciju, lai lapa nesabrūk
    if (!isset($classes)) {
        $classes = collect();
    }
@endphp

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/teacher-dashboard.css') }}">
    @endpush
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="mb-8 text-right">
            <a href="{{ url('teacher/create-class') }}" class="btn-create-class">
                 Create Class
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 dark:border-gray-700">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                
                <h3 class="text-xl font-bold mb-2">Tavas vadītās klases:</h3>

                @if($classes->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 py-4">Tu vēl neesi izveidojis nevienu klasi.</p>
                @else
                    <div class="teacher-classes-grid">
                        @foreach($classes as $classItem)
                            
                            <a href="{{ url('/teacher/class/' . $classItem->id) }}" 
                               class="class-card-link" 
                               style="--class-color: {{ $classItem->color ?? '#3b82f6' }};">
                                
                                <div class="class-card-content">
                                    <div class="class-color-dot" style="background-color: var(--class-color);">
                                    </div>
                                    
                                    <div>
                                        <h4 class="class-card-title">
                                            {{ $classItem->class_name }}
                                        </h4>
                                        <p class="class-card-teacher">
                                            Skolotājs: {{ Auth::user()->name }}
                                        </p>
                                    </div>
                                </div>

                                <span class="class-card-arrow">➜</span>
                            </a>

                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>