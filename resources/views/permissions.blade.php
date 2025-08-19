@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-shield"></i> Permissions Management</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
            <i class="fas fa-plus"></i> Create New Role
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Roles Management Card -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Roles</h6>
                    <input type="text" id="roleSearch" class="form-control form-control-sm w-25" placeholder="Search roles...">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="rolesTable">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Roles will be loaded here via AJAX or Blade loop -->
                                @foreach ($roles as $roleName => $rolePermissions)
                                    <tr>
                                        <td>
                                            {{ $roleName }}
                                            @if ($roleName == 'user')
                                                <p class="text-muted mb-0"><small>Regular user with basic access</small></p>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($rolePermissions as $permission)
                                                <span class="badge bg-info text-dark me-1"><i class="fas fa-check-circle"></i> {{ $permission }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm edit-role-btn" data-role-name="{{ $roleName }}" data-role-permissions="{{ implode(',', $rolePermissions) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Role">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-role-btn" data-role-name="{{ $roleName }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Role">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Management Card -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Users</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <input type="text" id="userSearch" class="form-control" placeholder="Search by Name or Email...">
                        </div>
                        <div class="col-md-4">
                            <select id="roleFilter" class="form-select">
                                <option value="">Filter by Role</option>
                                @foreach ($roles as $roleName => $rolePermissions)
                                    <option value="{{ $roleName }}">{{ ucfirst($roleName) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="usersTable">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr id="user-row-{{ $user->id }}">
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="role">
                                            <span class="badge bg-primary"><i class="fas fa-user"></i> {{ $user->userPermissions->role ?? 'N/A' }}</span>
                                            @if ($user->userPermissions && count($user->userPermissions->permissions) > 0)
                                                <br>
                                                <small class="text-muted">Permissions:</small>
                                                @foreach ($user->userPermissions->permissions as $permission)
                                                    <span class="badge bg-secondary">{{ $permission }}</span>
                                                @endforeach
                                            @else
                                                <small class="text-muted">No specific permissions</small>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-user-role-btn" 
                                                    data-id="{{ $user->id }}" 
                                                    data-name="{{ $user->name }}" 
                                                    data-role="{{ $user->role }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Edit User Role">
                                                <i class="fas fa-user-edit"></i> Edit Roles
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Create New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRoleForm">
                        <div class="mb-3">
                            <label for="newRoleName" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="newRoleName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newRolePermissions" class="form-label">Permissions</label>
                            <div id="newRolePermissions" class="form-check">
                                <!-- All available permissions will be loaded here -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role: <span id="currentRoleName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoleForm">
                        <input type="hidden" id="editOriginalRoleName">
                        <div class="mb-3">
                            <label for="editRoleNameInput" class="form-label">Role Name</label>
                            <input type="text" class="form-control" id="editRoleNameInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRolePermissionsCheckboxes" class="form-label">Permissions</label>
                            <div id="editRolePermissionsCheckboxes" class="form-check">
                                <!-- Permissions checkboxes will be loaded here -->
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Role Modal (repurposed from original edit permissions modal) -->
    <div class="modal fade" id="editUserRoleModal" tabindex="-1" aria-labelledby="editUserRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserRoleModalLabel">Edit Role for <span id="editUserRoleName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserRoleForm">
                        <input type="hidden" id="editUserRoleId">
                        <div class="mb-3">
                            <label for="editUserRoleNameInput" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="editUserRoleNameInput" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editUserRoleSelect" class="form-label">Role</label>
                            <select class="form-select" id="editUserRoleSelect">
                                <!-- Roles will be loaded here -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Function to filter roles table
        $('#roleSearch').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#rolesTable tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Function to filter users table
        $('#userSearch').on('keyup', function() {
            filterUsers();
        });

        $('#roleFilter').on('change', function() {
            filterUsers();
        });

        function filterUsers() {
            var searchValue = $('#userSearch').val().toLowerCase();
            var roleFilterValue = $('#roleFilter').val().toLowerCase();

            $('#usersTable tbody tr').filter(function() {
                var userName = $(this).find('td:eq(0)').text().toLowerCase();
                var userEmail = $(this).find('td:eq(1)').text().toLowerCase();
                var userRole = $(this).find('.role .badge').text().toLowerCase();

                var searchMatch = (userName.indexOf(searchValue) > -1 || userEmail.indexOf(searchValue) > -1);
                var roleMatch = (roleFilterValue === '' || userRole.indexOf(roleFilterValue) > -1);

                $(this).toggle(searchMatch && roleMatch);
            });
        }

        // Handle Create Role Modal
        $('#createRoleModal').on('show.bs.modal', function () {
            var allPermissions = ['create', 'edit', 'delete', 'reports', 'users']; // Example permissions
            var permissionsHtml = '';
            allPermissions.forEach(function(permission) {
                permissionsHtml += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${permission}" id="create-${permission}">
                        <label class="form-check-label" for="create-${permission}">
                            ${permission.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                        </label>
                    </div>
                `;
            });
            $('#newRolePermissions').html(permissionsHtml);
        });

        $('#createRoleForm').on('submit', function(e) {
            e.preventDefault();
            var newRoleName = $('#newRoleName').val();
            var newRolePermissions = [];
            $('#newRolePermissions input:checked').each(function() {
                newRolePermissions.push($(this).val());
            });

            $.ajax({
                url: '{{ route("permissions.createRole") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    role_name: newRoleName,
                    permissions: newRolePermissions
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });

        // Handle Edit Role Modal
        $(document).on('click', '.edit-role-btn', function() {
            var roleName = $(this).data('role-name');
            var rolePermissions = $(this).data('role-permissions').split(',');
            
            $('#currentRoleName').text(roleName);
            $('#editRoleNameInput').val(roleName);
            $('#editOriginalRoleName').val(roleName); // Store original name for update

            var allPermissions = ['create', 'edit', 'delete', 'reports', 'users']; // Example permissions
            var permissionsHtml = '';
            allPermissions.forEach(function(permission) {
                var isChecked = rolePermissions.includes(permission) ? 'checked' : '';
                permissionsHtml += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${permission}" id="edit-${permission}" ${isChecked}>
                        <label class="form-check-label" for="edit-${permission}">
                            ${permission.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                        </label>
                    </div>
                `;
            });
            $('#editRolePermissionsCheckboxes').html(permissionsHtml);
            $('#editRoleModal').modal('show');
        });

        $('#editRoleForm').on('submit', function(e) {
            e.preventDefault();
            var originalRoleName = $('#editOriginalRoleName').val();
            var newRoleName = $('#editRoleNameInput').val();
            var newRolePermissions = [];
            $('#editRolePermissionsCheckboxes input:checked').each(function() {
                newRolePermissions.push($(this).val());
            });

            $.ajax({
                url: '{{ route("permissions.updateRole") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    original_role_name: originalRoleName,
                    new_role_name: newRoleName,
                    permissions: newRolePermissions
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });

        // Handle Delete Role
        $(document).on('click', '.delete-role-btn', function() {
            var roleName = $(this).data('role-name');
            if (confirm('Are you sure you want to delete the role "' + roleName + '"?')) {
                $.ajax({
                    url: '{{ route("permissions.deleteRole") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        role_name: roleName
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            }
        });

        // Handle Edit User Role Modal
        $(document).on('click', '.edit-user-role-btn', function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');
            var userRole = $(this).data('role');

            $('#editUserRoleId').val(userId);
            $('#editUserRoleName').text(userName);
            $('#editUserRoleNameInput').val(userName);

            var rolesHtml = '';
            // Get roles array safely using PHP blade syntax
            var roles = {!! json_encode(array_keys($roles ?? [])) !!};
            roles.forEach(function(role) {
                var isSelected = (role === userRole) ? 'selected' : '';
                rolesHtml += `<option value="${role}" ${isSelected}>${role.charAt(0).toUpperCase() + role.slice(1)}</option>`;
            });
            $('#editUserRoleSelect').html(rolesHtml);
            $('#editUserRoleModal').modal('show');
        });

        $('#editUserRoleForm').on('submit', function(e) {
            e.preventDefault();
            var userId = $('#editUserRoleId').val();
            var newRole = $('#editUserRoleSelect').val();

            $.ajax({
                url: '{{ route("permissions.updateUserRole") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    role: newRole
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        });
    });
</script>
@endsection
@section('styles')
<style>
    /* Example for improving button interactions */
    .btn-info:hover {
        background-color: #007bff;
        border-color: #0056b3;
    }

    .btn-danger:hover {
        background-color: #dc3545;
        border-color: #c82333;
    }

    /* Adding transitions to buttons for smooth effect */
    .btn {
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }
</style>
@endsection