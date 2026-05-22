

    <div class="py-6">
        <h1 class="text-2xl font-bold mb-8 text-center sm:text-left max-w-4xl mx-auto px-6">Uzdevuma Izveide</h1>

        <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 px-6">

            {{-- 1. Uzdevuma izveide --}}
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold mb-6">🆕 Izveidot jaunu uzdevumu</h2>
                
                <form action="{{ route('teacher.tasks.store') }}" method="POST">
    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uzdevuma nosaukums</label>
                            <input 
                                type="text" 
                                name="task_name" 
                                required
                                class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Uzdevuma apraksts</label>
                            <input 
                                type="text" 
                                name="task_description" 
                                class="w-full p-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Krāsa</label>
                            <input 
                                type="color" 
                                name="color" 
                                value="#3b82f6"
                                class="w-full h-12 p-1 border border-gray-300 rounded-2xl cursor-pointer">
                        </div>

                        <button 
                            type="submit"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-3xl transition shadow-md">
                            Saglabāt Uzdevumu
                        </button>
                    </div>
                </form>
            </div>

        <div class="text-center mt-10">
            <a href="{{ route('teacher.class', ['id' => $class->id]) }}" 
               class="inline-block px-8 py-3 text-gray-600 hover:text-gray-900 transition font-medium">
                ← Atpakaļ uz Teacher paneli
            </a>
        </div>
    </div>