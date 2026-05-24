<div class="calendar-container" id="calendar-main"
     data-completed='@json($completedDates ?? [])'
     data-due='@json($dueDates ?? [])'>

    <div class="flex items-center justify-between mb-5">
        <button onclick="prevMonth()" class="text-3xl hover:bg-gray-100 w-11 h-11 rounded-xl transition">←</button>
        <h2 id="calendar-title" class="text-2xl font-bold text-gray-800"></h2>
        <button onclick="nextMonth()" class="text-3xl hover:bg-gray-100 w-11 h-11 rounded-xl transition">→</button>
    </div>

    <div class="calendar-header grid grid-cols-7 text-center font-bold mb-2">
        <div class="day-header">P</div>
        <div class="day-header">O</div>
        <div class="day-header">T</div>
        <div class="day-header">C</div>
        <div class="day-header">Pk</div>
        <div class="day-header">S</div>
        <div class="day-header">Sv</div>
    </div>

    <div id="calendar-grid" class="calendar-grid grid grid-cols-7 gap-1"></div>
</div>

<div id="task-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="p-6 border-b bg-gray-50">
            <h3 id="modal-date" class="text-2xl font-bold text-gray-800"></h3>
        </div>
        <div class="p-6 max-h-[60vh] overflow-y-auto" id="modal-tasks"></div>
        <div class="p-4 border-t flex justify-end bg-gray-50">
            <button onclick="closeModal()" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 rounded-xl font-medium transition">
                Aizvērt
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar-main');
    
    let completedDates = {};
    let dueDates = {};
    let loginHistory = {};

    // 1. Ielādējam datus no servera
    try {
        completedDates = JSON.parse(calendarEl.getAttribute('data-completed') || '{}');
        dueDates = JSON.parse(calendarEl.getAttribute('data-due') || '{}');
    } catch (e) {
        console.error('Kļūda nolasot kalendāra datus', e);
    }

    // 2. Ielādējam ielogošanās vēsturi no localStorage
    const storedHistory = localStorage.getItem('user_login_history');
    if (storedHistory) {
        try {
            loginHistory = JSON.parse(storedHistory);
        } catch(e) {
            loginHistory = {};
        }
    }

    // 3. Atzīmējam šodienu kā apmeklētu
    const today = new Date();
    const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
    
    loginHistory[todayStr] = true;
    localStorage.setItem('user_login_history', JSON.stringify(loginHistory));

    let currentDate = new Date();

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        const monthNames = ["Janvāris","Februāris","Marts","Aprīlis","Maijs","Jūnijs","Jūlijs","Augusts","Septembris","Oktobris","Novembris","Decembris"];
        document.getElementById('calendar-title').textContent = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        let html = '';
        const adjustedFirstDay = firstDay === 0 ? 6 : firstDay - 1;

        // Tukšās šūnas (saglabāts tavs oriģinālais stils)
        for (let i = 0; i < adjustedFirstDay; i++) {
            html += `<div class="calendar-cell"></div>`;
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            
            const isToday = dateStr === todayStr;
            const isCompleted = completedDates.hasOwnProperty(dateStr);
            const isDue = dueDates.hasOwnProperty(dateStr);
            const hasLoggedIn = loginHistory.hasOwnProperty(dateStr);

            // SAGLABĀTS: Tavs oriģinālais klases nosaukums "calendar-cell"
            let cellClass = "calendar-cell";

            // Dinamiski pievienojam klāt tikai krāsu stilus, neaiztiekot izmērus
            if (isDue) {
                cellClass += " bg-red-100 border-red-300 text-red-800 font-bold";
            } else if (isCompleted) {
                cellClass += " bg-pink-100 border-pink-300 text-pink-800 font-bold";
            } else if (hasLoggedIn) {
                cellClass += " bg-green-100 border-green-300 text-green-800 font-bold"; // Zaļš ielogotajām dienām
            } else if (isToday) {
                cellClass += " bg-blue-100 border-blue-400 text-blue-900 font-bold";
            }

            // Šūnā atstāti arī oriģinālie punktiņu elementi pēc tavas vecās loģikas
            html += `
                <div class="${cellClass}" onclick="showTasks('${dateStr}')">
                    <span class="text-xl">${day}</span>
                    ${isDue ? '<span class="text-red-600 text-xs">●</span>' : ''}
                    ${isCompleted && !isDue ? '<span class="text-pink-600 text-xs">●</span>' : ''}
                    ${hasLoggedIn && !isDue && !isCompleted ? '<span class="text-green-600 text-xs">✓</span>' : ''}
                </div>
            `;
        }

        document.getElementById('calendar-grid').innerHTML = html;
    }

    // Modāls
    window.showTasks = function(dateStr) {
        const modal = document.getElementById('task-modal');
        const dateTitle = document.getElementById('modal-date');
        const tasksContainer = document.getElementById('modal-tasks');

        const formattedDate = new Date(dateStr.replace(/-/g, '/'));
        
        dateTitle.textContent = formattedDate.toLocaleDateString('lv-LV', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });

        const dueTasks = dueDates[dateStr] || [];
        const completedTasks = completedDates[dateStr] || [];

        let content = '';

        if (dueTasks.length > 0) {
            content += '<div class="space-y-3">';
            dueTasks.forEach(task => {
                const title = escapeHtml(task.title || 'Uzdevums');
                const className = escapeHtml(task.class_name || 'Klase nav norādīta');
                const description = escapeHtml(task.description || '');
                const url = task.url ? escapeAttribute(task.url) : '#';

                content += `
                    <a href="${url}" class="block border border-red-200 bg-red-50 rounded-2xl p-4 hover:bg-red-100 transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h4 class="font-semibold text-red-900">${title}</h4>
                                <p class="text-sm text-red-700">${className}</p>
                            </div>
                            <span class="text-red-600 text-lg leading-none">●</span>
                        </div>
                        ${description ? `<p class="mt-3 text-sm text-red-800">${description}</p>` : ''}
                    </a>
                `;
            });
            content += '</div>';
        } else if (completedTasks.length > 0) {
            content = '<div class="space-y-3">';
            completedTasks.forEach(task => {
                content += `
                    <div class="border border-green-200 bg-green-50 rounded-2xl p-4">
                        <h4 class="font-semibold text-green-900">${escapeHtml(task.title || 'Pabeigts uzdevums')}</h4>
                        ${task.description ? `<p class="mt-2 text-sm text-green-800">${escapeHtml(task.description)}</p>` : ''}
                    </div>
                `;
            });
            content += '</div>';
        } else if (loginHistory.hasOwnProperty(dateStr)) {
            content = '<p class="text-gray-500 text-center py-10">Šajā dienā tu biji ielogojies sistēmā.</p>';
        } else {
            content = '<p class="text-gray-500 text-center py-10">Šajā datumā nav uzdevumu.</p>';
        }

        tasksContainer.innerHTML = content;
        modal.classList.remove('hidden');
    };

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function escapeAttribute(value) {
        return escapeHtml(value).replace(/`/g, '&#096;');
    }

    window.closeModal = function() {
        document.getElementById('task-modal').classList.add('hidden');
    };

    window.prevMonth = () => { currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar(); };
    window.nextMonth = () => { currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar(); };

    renderCalendar();
});
</script>
