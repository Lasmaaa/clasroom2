<!-- resources/views/components/user-side-panel.blade.php -->

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-side.css') }}">
@endpush

<div class="user-side-panel">
    <div class="user-side-panel__box">
        
        <div class="user-side-panel__avatar">
            @php
                $defaultAvatar = asset('avatars/default-avatar.png');
                $avatarSrc = Auth::user()->avatar 
                    ? asset('storage/' . Auth::user()->avatar) 
                    : $defaultAvatar;
            @endphp
            
            <img 
                src="{{ $avatarSrc }}?t={{ now()->timestamp }}" 
                id="side-panel-avatar"
                alt="{{ Auth::user()->name ?? 'Lietotājs' }}" 
                class="avatar-img w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-md"
                onerror="this.src = '{{ $defaultAvatar }}';">
        </div>

        <div class="user-side-panel__title">{{ Auth::user()->name ?? 'Lietotājs' }}</div>
        <div class="user-side-panel__subtitle">{{ Auth::user()->email ?? '' }}</div>

        <x-responsive-nav-link 
            :href="route('profile.edit')" 
            :active="request()->routeIs('profile.edit')">
            {{ __('Settings') }}
        </x-responsive-nav-link>
    </div>
</div>