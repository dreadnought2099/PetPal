@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl text-center font-semibold mb-6">Admin <span class="text-primary">Dashboard</span></h2>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Manage <span class="text-primary">Users</span></h2>

            <table class="min-w-full table-auto bg-white border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500">ID</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500">Email</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500">Roles</th>
                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 cursor-pointer">
                    @foreach ($users as $user)
                        <tr class="hover:bg-gray-100">
                            <!-- Display user details -->
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $user->id }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                <!-- Form to handle role change -->
                                <form id="handleRoleChange-{{ $user->id }}" method="POST"
                                    action="{{ route('admin.changeRole', $user->id) }}">
                                    @csrf
                                    <input type="hidden" name="role" id="role-{{ $user->id }}"
                                        value="{{ $user->roles->first() ? $user->roles->first()->name : '' }}">
                                    <select name="role" class="border rounded px-2 py-1 text-sm cursor-pointer"
                                        onchange="handleRoleChange(event, {{ $user->id }}, '{{ addslashes($user->name) }}')">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                <!-- Trigger delete confirmation -->
                                <button onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')" title="Delete User"
                                    class="transition-all hover:scale-150 duration-300 cursor-pointer">
                                    <img src="icon/trash-solid.svg" alt="Delete" class="w-5 h-5">
                                </button>

                                <!-- Hidden delete form -->
                                <form id="deleteUserForm-{{ $user->id }}" method="POST"
                                    action="{{ route('admin.deleteUser', $user->id) }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

<div id="confirmationModal" class="modal fixed inset-0 flex items-center justify-center hidden" data-action="delete" onclick="outsideClickEvent(event)">
    <div class="modal-content bg-white p-6 rounded-lg shadow-xl text-center max-w-sm w-full" onclick="event.stopPropagation()">
        <p id="modalMessage" class="mb-4 text-lg text-gray-800"></p>
        <div class="flex space-x-4">
            <button id="confirmButton" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-white hover:text-primary border-1 border-primary hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer">
                Yes
            </button>
            <button onclick="closeModal()" class="bg-white text-gray-700 px-4 py-2 rounded-md hover:text-primary border-1 hover:border-primary hover:scale-105 transition-all duration-300 ease-in-out cursor-pointer">
                Cancel
            </button>
        </div>
    </div>
</div>

<style>
    .modal {
        background-color: rgba(0, 0, 0, 0.4); 
        z-index: 50;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Script Loaded');
        let pendingAction = null;

        // Show delete confirmation modal
        window.confirmDelete = function(userId, userName) {
            console.log(`Confirm deletion for ID: ${userId}, Name: ${userName}`);
            pendingAction = () => {
                document.getElementById(`deleteUserForm-${userId}`).submit();
            };
            showModal(`Are you sure you want to delete <span style="color: #49b451;">${userName}</span>?`);
        };

        // Show role change confirmation modal
        window.handleRoleChange = function(event, userId, userName) {
            event.preventDefault(); // Prevent immediate submission
            const selectedRole = event.target.value;
            document.getElementById(`role-${userId}`).value = selectedRole;
            pendingAction = () => {
                document.getElementById(`handleRoleChange-${userId}`).submit();
            };
            showModal(`Change <span style="color: #49b451;">${userName}'s</span> role to <span style="color: #49b451;">${selectedRole}</span>?`);
        };

        // Show modal with dynamic message
        window.showModal = function(message) {
            const modalMessage = document.getElementById('modalMessage');
            modalMessage.innerHTML = message;
            document.getElementById('confirmationModal').classList.remove('hidden');
        };

        // Close modal and reset pending action
        window.closeModal = function() {
            document.getElementById('confirmationModal').classList.add('hidden');
            pendingAction = null;
        };

        // Execute pending action when confirming
        document.getElementById('confirmButton').addEventListener('click', () => {
            if (pendingAction) {
                pendingAction();
            }
            closeModal();
        });

        // Handle clicks outside the modal content
        window.outsideClickEvent = function(event) {
            const modal = document.getElementById('confirmationModal');
            if (event.target === modal) {
                closeModal();
            }
        };
    });
</script>
