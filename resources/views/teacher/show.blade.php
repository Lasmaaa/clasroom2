@php
    // Ja kontrolieris nav atsūtījis mainīgos, Blade neizmetīs erroru, bet paņems zilo krāsu
    $themeColor = $classColor ?? ($class?->color ?? '#3b82f6');
@endphp

<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/teacher-show.css') }}">
    @endpush
    
    <div class="max-w-md mx-auto px-4 py-8" style="--theme-color: {{ $themeColor }};">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 bg-white rounded-3xl px-6 py-3 shadow-sm border border-gray-100">
                <div class="w-6 h-6 rounded-full border border-gray-200 shadow-sm teacher-brand-dot"></div>
                <div class="text-left">
                    <p class="text-xs text-gray-500">Skolotājs</p>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
            </div>

            <h2 class="mt-5 text-3xl font-bold teacher-brand-text">
                {{ $class?->class_name ?? 'Klase nav izveidota' }}
            </h2>
        </div>

        <h1 class="text-2xl font-bold text-center mb-6 text-gray-900">Saglabātais Verifikācijas Kods</h1>

        <div class="bg-white p-8 md:p-10 rounded-3xl shadow-lg border border-gray-100">
            
            @if($codeModel->type === 'numeric')
                <p class="text-center text-gray-500 mb-4 font-medium">Ciparu Kods</p>
                <div class="text-6xl font-mono font-bold text-center tracking-widest py-10 rounded-2xl shadow-inner teacher-code-block">
                    {{ $codeModel->code }}
                </div>
            @else
                <p class="text-center text-gray-500 mb-4 font-medium">QR Kods</p>
                <div class="flex justify-center mb-6">
                    <img 
                        src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ $codeModel->code }}" 
                        alt="QR Code" 
                        class="rounded-2xl shadow-md border border-gray-100">
                </div>
                <p class="text-center font-mono text-lg break-all font-semibold teacher-qr-text">
                    {{ $codeModel->code }}
                </p>
            @endif

        </div>

        <div class="text-center mt-10 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('teacher.create-class') }}" 
               class="px-8 py-4 bg-gray-900 text-white font-bold rounded-3xl hover:bg-black transition text-center shadow-md">
                🆕 Izveidot jaunu klasi / kodu
            </a>
            
            <a href="{{ route('teacher.index') }}" 
               class="px-8 py-4 bg-white border border-gray-300 font-bold text-gray-700 rounded-3xl hover:bg-gray-50 transition text-center shadow-sm">
                Atpakaļ uz paneli
            </a>
        </div>
    </div>
</x-app-layout>