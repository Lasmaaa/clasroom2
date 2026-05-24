<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="bg-white rounded-3xl shadow-xl p-8">
            <h1 class="text-2xl font-bold mb-8">Rediget uzdevumu</h1>

            <form method="POST" action="{{ route('teacher.task.update', $task) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Uzdevuma nosaukums</label>
                    <input type="text" name="name" value="{{ old('name', $task->name) }}" required
                           class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Apraksts</label>
                    <textarea name="description" rows="6"
                              class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sakuma datums</label>
                        <input type="datetime-local" name="start_date"
                               value="{{ old('start_date', optional($task->start_date)->format('Y-m-d\TH:i')) }}"
                               class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Termins</label>
                        <input type="datetime-local" name="due_date"
                               value="{{ old('due_date', optional($task->due_date)->format('Y-m-d\TH:i')) }}"
                               class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('due_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Krasa</label>
                        <input type="color" name="color" value="{{ old('color', $task->color ?? '#3b82f6') }}"
                               class="w-full h-12 p-1 border border-gray-300 rounded-2xl cursor-pointer">
                        @error('color')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statuss</label>
                        <select name="status"
                                class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="pending" @selected(old('status', $task->status) === 'pending')>Nav pabeigts</option>
                            <option value="completed" @selected(old('status', $task->status) === 'completed')>Pabeigts</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-between pt-4">
                    <a href="{{ route('teacher.class.show', $task->class_info_id) }}"
                       class="px-6 py-3 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-2xl">
                        Atpakal uz klasi
                    </a>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl">
                            Saglabat izmainas
                        </button>
                        <button type="button"
                                onclick="if (confirm('Vai tiesam izdzest so uzdevumu?')) document.getElementById('delete-form').submit()"
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-2xl">
                            Izdzest
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="{{ route('teacher.task.delete', $task) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</x-app-layout>
