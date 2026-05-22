<!-- QR Kods -->
<div class="mt-12">
    <p class="text-gray-600 text-lg mb-4">QR Kods (skenēšanai):</p>
    <div class="flex justify-center bg-white p-6 border border-gray-200 rounded-3xl inline-block">
        @if($class->class_code)
            {!! QrCode::size(280)->generate($class->class_code) !!}
        @else
            <p class="text-red-500">Kods vēl nav ģenerēts</p>
        @endif
    </div>
</div>