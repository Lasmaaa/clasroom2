@props(['name' => 'auth_method', 'default' => 'numeric'])

<div x-data="{ currentType: '{{ old($name, session('code_type', $default)) }}' }" 
     class="w-full max-w-md mx-auto">

    <input type="hidden" name="{{ $name }}" :value="currentType">

    <!-- Switch -->
    <div class="flex justify-center mb-8">
        <div class="bg-gray-200 rounded-3xl p-1 flex">
            <button type="button" @click="currentType = 'numeric'"
                    :class="currentType === 'numeric' ? 'bg-white shadow' : ''"
                    class="px-8 py-3 rounded-3xl text-sm font-medium transition">
                Ciparu Kods
            </button>
            <button type="button" @click="currentType = 'qr'"
                    :class="currentType === 'qr' ? 'bg-white shadow' : ''"
                    class="px-8 py-3 rounded-3xl text-sm font-medium transition">
                QR Kods
            </button>
        </div>
    </div>

    <!-- CIPARU KODS -->
    <div x-show="currentType === 'numeric'">
        <label class="block text-sm font-medium mb-2">Ciparu Kods</label>
        @if(session('generated_code') && session('code_type') === 'numeric')
            <div class="p-10 bg-white border-2 border-blue-500 rounded-3xl text-center shadow">
                <div class="text-5xl font-bold font-mono text-blue-600">
                    {{ session('generated_code') }}
                </div>
            </div>
        @else
            <div class="p-20 border-2 border-dashed border-gray-300 rounded-3xl text-center text-gray-400">
                Spied "Ģenerēt jaunu kodu"
            </div>
        @endif
    </div>

    <!-- QR KODS -->
    <div x-show="currentType === 'qr'">
        <label class="block text-sm font-medium mb-2">QR Kods</label>
        @if(session('generated_code') && session('code_type') === 'qr')
            <div class="p-6 bg-white border rounded-3xl flex flex-col items-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ session('generated_code') }}" 
                     alt="QR Code" class="rounded-2xl">
                <p class="mt-4 font-mono">{{ session('generated_code') }}</p>
            </div>
        @else
            <div class="p-20 border-2 border-dashed border-gray-300 rounded-3xl text-center text-gray-400">
                Spied "Ģenerēt jaunu kodu"
            </div>
        @endif
    </div>
</div>