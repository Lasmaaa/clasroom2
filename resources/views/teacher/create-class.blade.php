<x-app-layout>
    <div class="max-w-lg mx-auto mt-12">
        <h1 class="text-3xl font-bold text-center mb-8">Izveidot Jaunu Klasi</h1>

        <form action="{{ route('teacher.classes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium mb-2">Klases nosaukums</label>
                <input type="text" name="class_name" required
                       class="w-full p-4 border border-gray-300 rounded-2xl focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Izvēlies krāsu</label>
                <input type="color" name="color" value="#3b82f6"
                       class="w-full h-12 p-1 border border-gray-300 rounded-2xl cursor-pointer">
            </div>

            <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-3xl text-lg">
                Saglabāt Klasi
            </button>
        </form>
    </div>
</x-app-layout>