@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Display session messages like success or info -->
        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif --}}

        <h2 class="text-xl">Admin Dashboard</h2>

        <div class="mt-4">
            <div class="container">
                <h2 class="text-xl">Manage Users</h2>

                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form id="handleRoleChange-{{ $user->id }}" method="POST"
                                        action="{{ route('admin.changeRole', $user->id) }}">
                                        @csrf
                                        <input type="hidden" name="role" id="role-{{ $user->id }}"
                                            value="{{ $user->roles->first() ? $user->roles->first()->name : '' }}">
                                        <select name="role" class="border rounded px-2 py-1"
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
                                <td>
                                    <!-- Delete Button Trigger -->
                                    <button onclick="confirmDelete({{ $user->id }})"
                                        class="text-red-600 underline">Delete</button>

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
    </div>
@endsection


<!-- Modal HTML -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-xl text-center max-w-sm">
        <p id="modalMessage" class="mb-4 text-lg font-semibold"></p>
        <div class="space-x-4">
            <button id="confirmButton" class="bg-blue-500 text-white px-4 py-2 rounded">Yes</button>
            <button onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
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
