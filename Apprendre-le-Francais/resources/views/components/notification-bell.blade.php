<div x-data="{
        open: false,
        unreadCount: {{ auth()->user()->unreadNotifications->count() }},
        notifications: [],
        isLoading: true,
        
        init() {
            this.fetchNotifications();
        },
        
        fetchNotifications() {
            axios.get('{{ route('notifications.fetch') }}')
                .then(response => {
                    this.notifications = response.data;
                    this.isLoading = false;
                });
        },
        
        markAsRead(id) {
            axios.post(`/notifications/${id}/read`);
            this.unreadCount--;
        },
        
        markAllAsRead() {
            axios.post('{{ route('notifications.read-all') }}');
            this.unreadCount = 0;
        }
    }" 
    class="relative"
>
    <!-- Bell Icon -->
     <button @click="open = !open" class="nav-link position-relative">
        <i class="fas fa-bell fs-5"></i>
        <span x-show="unreadCount > 0" 
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              x-text="unreadCount">
        </span>
    </button>

    <!-- Dropdown -->
     <div 
        x-show="open" 
        @click.away="open = false"
        class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
        style="display: none; position: fixed; top: 60px;"
    >
        <div class="py-1">
            <!-- Header -->
            <div class="flex justify-between items-center px-4 py-2 border-b">
                <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                <button @click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-900" :disabled="unreadCount === 0">Tout marquer comme lu</button>
            </div>

            <!-- Loading -->
            <div x-show="isLoading" class="px-4 py-6 text-center">
                <svg class="animate-spin h-5 w-5 mx-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <!-- Empty State -->
            <div x-show="!isLoading && notifications.length === 0" class="px-4 py-6 text-center">
                <p class="text-sm text-gray-500">Aucune notification</p>
            </div>

            <!-- Notifications List -->
            <div x-show="!isLoading && notifications.length > 0" class="max-h-96 overflow-y-auto">
                <template x-for="notification in notifications" :key="notification.id">
                    <a 
                        :href="notification.url" 
                        class="flex px-4 py-3 hover:bg-gray-50 transition"
                        :class="{ 'bg-gray-50': notification.read_at === null }"
                        @click="markAsRead(notification.id)"
                    >
                        <div class="flex-shrink-0">
                            <span :class="notification.color" class="h-8 w-8 rounded-full flex items-center justify-center">
                                <i :class="notification.icon" class="text-lg"></i>
                            </span>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900" x-text="notification.message"></p>
                            <p class="text-xs text-gray-500" x-text="notification.time_ago"></p>
                        </div>
                        <div x-show="notification.read_at === null" class="flex-shrink-0">
                            <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                        </div>
                    </a>
                </template>
            </div>

            <!-- Footer -->
<div x-show="!isLoading" class="px-4 py-2 border-t">
                <a href="{{ route('notifications.index') }}" class="block text-center text-sm font-medium text-indigo-600 hover:text-indigo-900">
                    Voir toutes les notifications
                </a>
            </div>
        </div>
    </div>
</div>