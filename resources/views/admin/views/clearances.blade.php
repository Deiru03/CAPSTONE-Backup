<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clearances') }}
        </h2>
    </x-slot>
    <head>
        <!-- Other head elements -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Clearances</h3>
                    <p class="mt-2">Here you can view and manage clearances.</p>
                    <!-- Add your Clearances management content here -->
                </div>
            </div>            
        </div>

         <!-- Search and Filter Form -->
         <form method="GET" action="{{ route('admin.views.clearances') }}" class="mb-4 flex items-center">
            <input type="text" name="search" placeholder="Search by name, email, program, or status..." value="{{ request('search') }}" class="border rounded p-2 mr-2 w-1/2">
            <select name="sort" class="border rounded p-2 mr-2 w-40">
                <option value="" disabled {{ request('sort') ? '' : 'selected' }}>Sort by Name</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A to Z</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z to A</option>
            </select>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Apply</button>
        </form>
    
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="py-2 px-3 text-left text-sm">ID</th>
                    <th class="py-2 px-3 text-left text-sm">Name</th>
                    <th class="py-2 px-3 text-left text-sm">Email</th>
                    <th class="py-2 px-3 text-left text-sm">Program</th>
                    <th class="py-2 px-3 text-center text-sm">Clearance Status</th>
                    <th class="py-2 px-3 text-left text-sm">Checked By</th>
                    <th class="py-2 px-3 text-left text-sm">Last Updated</th>
                    <th class="py-2 px-3 text-left text-sm">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($clearance as $user)
                <tr class="hover:bg-gray-50" data-id="{{ $user->id }}">
                    <td class="py-2 px-3 text-sm">{{ $user->id }}</td>
                    <td class="py-2 px-3 text-sm">{{ $user->name }}</td>
                    <td class="py-2 px-3 text-sm">{{ $user->email }}</td>
                    <td class="py-2 px-3 text-sm">{{ $user->program }}</td>
                    <td class="py-2 px-3 clearances_status text-center text-sm">{{ $user->clearances_status }}</td>
                    <td class="py-2 px-3 checked_by text-sm">{{ $user->checked_by }}</td>
                    <td class="py-2 px-3 last_clearance_update text-sm">
                        {{ $user->last_clearance_update ? \Carbon\Carbon::parse($user->last_clearance_update)->format('M d, Y H:i') : 'N/A' }}
                    </td>
                    <td class="py-2 px-3 text-sm">
                        <button onclick="openModal({{ $user->id }})" class="text-blue-500 hover:text-blue-700 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-2xl font-bold mb-6 text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Clearance
            </h3>
            <form id="editForm" method="post" action="{{ route('admin.views.update-clearance') }}">
                @csrf
                <input type="hidden" name="id" id="editId">
                <div class="mb-4">
                    <label for="editFaculty" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Faculty Name
                    </label>
                    <input type="text" id="editFaculty" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                </div>
                <div class="mb-4">
                    <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Clearance Status
                    </label>
                    <select name="clearances_status" id="editStatus" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="pending">Pending</option>
                        <option value="complete">Complete</option>
                        <option value="return">Return</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="editCheckedBy" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Checked By
                    </label>
                    <input type="text" name="checked_by" id="editCheckedBy" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <label for="editLastUpdate" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Last Updated
                    </label>
                    {{-- Temporary hidden field to store the last_clearance_update value 
                    <input type="text" name="last_clearance_update" id="editLastUpdate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ now()->format('M d, Y H:i:s') }}" readonly>
                    --}}
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-md shadow-lg transform transition-all duration-500 opacity-0 translate-y-[-100%] pointer-events-none">
        Clearance updated successfully!
    </div>

    <!-- Script to handle modal and notification -->
    <script>
        function openModal(id) {
            console.log('Opening modal for user ID:', id);
            // Fetch clearance data and populate the modal fields
            const user = @json($clearance->toArray()).find(user => user.id === id);
            document.getElementById('editId').value = user.id;
            document.getElementById('editFaculty').value = user.name; // Display faculty name
            document.getElementById('editStatus').value = user.clearances_status;
            document.getElementById('editCheckedBy').value = user.checked_by;
            document.getElementById('editModal').classList.remove('hidden');
        }
 
        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Remove 'last_clearance_update' if it's present
            formData.delete('last_clearance_update');

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    closeModal();
                    showNotification('Clearance updated successfully'); // Show success notification
                    updateTableRow(data.user); // Update the table row with new data
                } else {
                    showNotification('Error updating clearance', 'error'); // Show error notification
                }
            })
            .catch(error => {
                console.error('Error updating clearance:', error);
                showNotification('An unexpected error occurred.', 'error'); // Show error notification
            });
        });

        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            
            // Remove existing background classes
            notification.classList.remove('bg-green-500', 'bg-red-500');
            
            // Add new background class based on type
            if (type === 'success') {
                notification.classList.add('bg-green-500');
            } else {
                notification.classList.add('bg-red-500');
            }
            
            // Show notification with animation
            notification.style.transform = 'translateY(0)';
            notification.style.opacity = '1';
            
            // Hide notification after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateY(-100%)';
                notification.style.opacity = '0';
            }, 3000);
        }
        function updateTableRow(user) {
            const row = document.querySelector(`tr[data-id="${user.id}"]`);
            if (row) {
                row.querySelector('.clearances_status').textContent = user.clearances_status;
                row.querySelector('.checked_by').textContent = user.checked_by;
                row.querySelector('.last_clearance_update').textContent = new Date(user.last_clearance_update).toLocaleString();
            } else {
                console.error('Row not found for user ID:', user.id);
            }
        }
    </script>


</x-admin-layout>