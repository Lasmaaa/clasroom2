<x-app-layout>
    <div class="py-6">
        <h1 class="text-2xl font-bold mb-8 text-center sm:text-left max-w-4xl mx-auto px-6">
            Uzdevuma izveide
        </h1>

        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-6">{{ $class->class_name }}</h2>

                <form action="{{ route('teacher.tasks.store', $class->id) }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uzdevuma nosaukums</label>
                            <input type="text" name="task_name" value="{{ old('task_name') }}" required
                                   class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('task_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uzdevuma apraksts</label>
                            <textarea name="task_description" rows="4"
                                      class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('task_description') }}</textarea>
                            @error('task_description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Krāsa</label>
                            <input type="color" name="color" value="{{ old('color', '#3b82f6') }}"
                                   class="w-full h-12 p-1 border border-gray-300 rounded-2xl cursor-pointer">
                            @error('color')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sakuma datums</label>
                                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}"
                                       class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Termins</label>
                                <input type="datetime-local" name="due_date" value="{{ old('due_date') }}"
                                       class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('due_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-3xl transition shadow-md">
                            Saglabat uzdevumu
                        </button>
                    </div>
                </form>
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('teacher.class', ['id' => $class->id]) }}"
                   class="inline-block px-8 py-3 text-gray-600 hover:text-gray-900 transition font-medium">
                    Atpakal uz klasi
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
