<!-- resources/views/profile/partials/update-profile-picture-form.blade.php -->

<form id="avatar-upload-form" enctype="multipart/form-data">
    @csrf

    <div class="flex items-center gap-6">
        <!-- Pašreizējais avatārs -->
        <div class="flex-shrink-0">
            <img id="current-avatar" 
                 src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('avatars/default-avatar.png') }}" 
                 alt="Avatar" 
                 class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-md avatar-img">
        </div>

        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                {{ __('Mainīt profila bildi') }}
            </label>
            <input type="file" id="avatar" name="avatar" accept="image/*" 
                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            <p id="upload-status" class="mt-2 text-sm text-gray-500"></p>
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" id="upload-btn" 
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
            {{ __('Augšupielādēt jaunu bildi') }}
        </button>
    </div>
</form>

<script>
document.getElementById('avatar-upload-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const statusEl = document.getElementById('upload-status');
    const btn = document.getElementById('upload-btn');
    const currentAvatar = document.getElementById('current-avatar');

    btn.disabled = true;
    statusEl.textContent = 'Augšupielādē...';
    statusEl.className = 'mt-2 text-sm text-indigo-600';

    try {
        const response = await fetch('{{ route("profile.avatar.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            const text = await response.text();
            console.error('Server response:', text);
            throw new Error(`HTTP kļūda: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            const newAvatarSrc = data.avatar_url + '?t=' + Date.now();

            // Atjauninām visus avatarus pašreizējā lapā
            document.querySelectorAll('.avatar-img').forEach(img => {
                img.src = newAvatarSrc;
            });

            if (currentAvatar) currentAvatar.src = newAvatarSrc;

            statusEl.innerHTML = '✅ Profila bilde veiksmīgi nomainīta!<br>Pārlādējam lapu...';
            statusEl.className = 'mt-2 text-sm text-green-600 font-medium';

            // Pārej uz dashboard, lai redzētu jauno bildi arī user-side-panel
            setTimeout(() => {
                window.location.href = '{{ route("user.index") }}';
            }, 1200);

        } else {
            throw new Error(data.message || 'Neizdevās nomainīt bildi');
        }
    } catch (error) {
        console.error(error);
        statusEl.innerHTML = '❌ Kļūda: ' + error.message;
        statusEl.className = 'mt-2 text-sm text-red-600';
    } finally {
        btn.disabled = false;
    }
});
</script>