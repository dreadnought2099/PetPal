@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Display session messages like success or info -->
        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif --}}

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
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $user->id }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-sm text-gray-700">
                                <form id="handleRoleChange-{{ $user->id }}" method="POST"
                                    action="{{ route('admin.changeRole', $user->id) }}">
                                    @csrf
                                    <input type="hidden" name="role" id="role-{{ $user->id }}"
                                        value="{{ $user->roles->first() ? $user->roles->first()->name : '' }}">
                                    <select name="role" class="border rounded px-2 py-1 text-sm"
                                        onchange="handleRoleChange(event, {{ $user->id }})">
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
                                <!-- Delete Button Trigger -->
                                <button onclick="confirmDelete({{ $user->id }})"
                                    class="text-red-600 hover-underline-hyperlink hover:text-secondary transition duration-300 cursor-pointer">Delete</button>

                                <!-- Delete Form -->
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

<!-- Modal HTML -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-xl text-center max-w-sm w-full">
        <p id="modalMessage" class="mb-4 text-lg font-semibold text-gray-800"></p>
        <div class="flex space-x-4">
            <button id="confirmButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">Yes</button>
            <button onclick="closeModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-300">Cancel</button>
        </div>
    </div>
</div>

<script>
    let pendingAction = null;

    function confirmDelete(userId) {
        pendingAction = () => {
            document.getElementById(`deleteUserForm-${userId}`).submit();
        };
        showModal("Are you sure you want to delete this user?");
    }

    function handleRoleChange(event, userId) {
        event.preventDefault(); // Prevent immediate form submission

        const selectedRole = event.target.value;

        // Set the role input value to the selected role
        document.getElementById(`role-${userId}`).value = selectedRole;

        // Show confirmation modal before submitting
        pendingAction = () => {
            document.getElementById(`handleRoleChange-${userId}`).submit(); // Submit the form
        };

        showModal(`Are you sure you want to change this user's role to "${selectedRole}"?`);
    }

    function showModal(message) {
        document.getElementById('modalMessage').innerText = message;
        document.getElementById('confirmationModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
        pendingAction = null;
    }

    document.getElementById('confirmButton').addEventListener('click', () => {
        if (pendingAction) {
            pendingAction();
        }
        closeModal();
    });
</script>
