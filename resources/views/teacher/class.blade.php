<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="bg-white rounded-3xl shadow-xl p-10">

            <h1 class="text-4xl font-bold">{{ $class->class_name }}</h1>

            <div class="mt-3 flex flex-wrap gap-x-8 text-sm text-gray-600">
                @if($class->tasks->isNotEmpty())
                    @php $firstTask = $class->tasks->first(); @endphp
                    @if($firstTask->start_date)
                        <p><span class="font-medium">Sākums:</span> 
                            {{ $firstTask->formatted_start_date }}
                        </p>
                    @endif
                    @if($firstTask->due_date)
                        <p><span class="font-medium">Termiņš:</span> 
                            {{ $firstTask->formatted_due_date }}
                        </p>
                    @endif
                @endif
            </div>

            <div class="mt-8">
                <p class="text-gray-600 mb-3">Klases pievienošanās kods:</p>
                <div class="bg-gray-100 border-2 border-dashed border-gray-400 rounded-2xl p-8 text-center">
                    <p class="text-6xl font-mono font-bold tracking-widest text-blue-700">
                        {{ $class->class_code }}
                    </p>
                </div>
            </div>

            <div class="mt-12">
                <p class="text-gray-600 mb-4">QR Kods:</p>
                <div class="flex justify-center bg-white p-8 border rounded-3xl">
                    {!! QrCode::size(260)->generate($class->class_code) !!}
                </div>
            </div>

            <!-- ==================== UZDEVUMI ==================== -->
<div class="mt-16">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Uzdevumi</h2>
        <a href="{{ route('teacher.create-task', $class->id) }}" 
           class="inline-flex items-center gap-3 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-3xl text-sm">
            + Jauns uzdevums
        </a>
    </div>

    @if($class->tasks->isEmpty())
        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-3xl p-12 text-center">
            <p class="text-gray-500">Šai klasei vēl nav neviena uzdevuma.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($class->tasks as $task)
    <div class="border border-gray-200 rounded-3xl p-6 hover:shadow-md transition"
         style="border-left: 6px solid">

        <h3 class="font-semibold text-lg mb-3">{{ $task->name }}</h3>
        
        @if($task->description)
            <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                {{ $task->description }}
            </p>
        @endif

        <div class="bg-gray-50 rounded-2xl p-5 grid grid-cols-2 gap-6 text-sm">
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-widest font-medium">Sākuma datums</p>
                <p class="font-semibold text-gray-900 mt-1">
                    {{ $task->formatted_start_date }}
                </p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-widest font-medium">Beigu datums</p>
                <p class="font-semibold mt-1 {{ $task->isOverdue() ? 'text-red-600 line-through' : 'text-gray-900' }}">
                    {{ $task->formatted_due_date }}
                </p>
            </div>
        </div>

        <div class="mt-5 flex justify-between text-xs">
            <span class="text-gray-500">
                Izveidots {{ $task->created_at->diffForHumans() }}
            </span>
            <a href="{{ route('teacher.edit-task', $task->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                Skatīt / Rediģēt →
            </a>
        </div>
    </div>
@endforeach
        </div>
    @endif
</div>
        </div>
    </div>  
</x-app-layout>