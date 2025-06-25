{{-- File: resources/views/cms/modules/users/permissions/role.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Role Permissions - ' . $role->name)

@section('content')

@php
    $loggedInUser = auth()->user();
    $hiddenPermissions = ['add permission', 'edit permission', 'delete permission'];
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Role Permissions: {{ $role->name }}</h3>
                <h6 class="op-7 mb-2">Manage permissions for this role</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('roles.index') }}" class="btn btn-label-info btn-round me-2">Back to Roles</a>
                @can('view permissions')
                    <a href="{{ route('permissions.index') }}" class="btn btn-info btn-round">All Permissions</a>
                @endcan
            </div>
        </div>
        
        {{-- Role Information --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Role Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Role Name:</strong><br>
                                {{ $role->name }}
                                {{-- <span class="badge bg-primary fs-6">{{ $role->name }}</span> --}}
                            </div>
                            <div class="col-md-3">
                                <strong>Total Permissions:</strong><br>
                                <span class="badge bg-success fs-6">{{ $rolePermissions->count() }}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Created:</strong><br>
                                <small class="text-muted">{{ $role->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong><br>
                                @if($role->name === 'Super Admin')
                                    <span class="badge bg-danger">Protected</span>
                                @else
                                    <span class="badge bg-info">Editable</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Current Permissions --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Current Permissions ({{ $rolePermissions->count() }})</div>
                    </div>
                    <div class="card-body">
                        @if($rolePermissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Permission</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rolePermissions as $permission)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $canModifySuperAdmin = $loggedInUser->hasRole('Super Admin');
                                                        $isSuperAdminRole = $role->name === 'Super Admin';
                                                        $canModify = !$isSuperAdminRole || $canModifySuperAdmin;
                                                        $isHiddenPermission = in_array($permission->name, $hiddenPermissions);
                                                    @endphp
                                                    @if($canModify && (!$isHiddenPermission || $loggedInUser->hasRole('Super Admin')))
                                                        <form action="{{ route('permissions.revoke', ['roleId' => $role->id, 'permissionId' => $permission->id]) }}" 
                                                              method="POST" style="display:inline-block" class="revoke-permission-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm revoke-permission-btn"
                                                                    @if($isHiddenPermission && !$loggedInUser->hasRole('Super Admin')) disabled @endif>
                                                                Remove
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-danger btn-sm" disabled>Remove</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-warning fa-2x mb-3"></i>
                                <h6 class="text-muted">No permissions assigned</h6>
                                <p class="text-muted small">This role doesn't have any permissions assigned yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Available Permissions --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Available Permissions</div>
                    </div>
                    <div class="card-body">
                        @php
                            $availablePermissions = $allPermissions->diff($rolePermissions);
                        @endphp
                        
                        @if($availablePermissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Permission</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($availablePermissions as $permission)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $permission->name }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $canModifySuperAdmin = $loggedInUser->hasRole('Super Admin');
                                                        $isSuperAdminRole = $role->name === 'Super Admin';
                                                        $canModify = !$isSuperAdminRole || $canModifySuperAdmin;
                                                        $isHiddenPermission = in_array($permission->name, $hiddenPermissions);
                                                    @endphp
                                                    @if($canModify && (!$isHiddenPermission || $loggedInUser->hasRole('Super Admin')))
                                                        <form action="{{ route('permissions.assign', ['roleId' => $role->id, 'permissionId' => $permission->id]) }}" 
                                                              method="POST" style="display:inline-block" class="assign-permission-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm assign-permission-btn"
                                                                    @if($isHiddenPermission && !$loggedInUser->hasRole('Super Admin')) disabled @endif>
                                                                Assign
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-success btn-sm" disabled>Assign</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-success fa-2x mb-3"></i>
                                <h6 class="text-success">All permissions assigned</h6>
                                <p class="text-muted small">This role has all available permissions assigned.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Permission Summary --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permission Summary</div>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <h4 class="text-primary">{{ $rolePermissions->count() }}</h4>
                                    <p class="text-muted mb-0">Assigned Permissions</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <h4 class="text-info">{{ $availablePermissions->count() }}</h4>
                                    <p class="text-muted mb-0">Available Permissions</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border rounded p-3">
                                    <h4 class="text-success">{{ $allPermissions->count() }}</h4>
                                    <p class="text-muted mb-0">Total Permissions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
@endsection

@push('scripts')
<script>
    // Check for success message
    @if (session('success'))
        swal({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            button: "OK",
        });
    @endif

    // Check for error message
    @if (session('error'))
        swal({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            button: "OK",
        });
    @endif

    // SweetAlert confirmation for revoke permission
    $(document).on('click', '.revoke-permission-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const permissionName = $(this).closest('tr').find('.badge').text();
        const roleName = '{{ $role->name }}';
        
        swal({
            title: 'Remove Permission?',
            text: `Are you sure you want to remove permission '${permissionName}' from ${roleName}?`,
            icon: 'warning',
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                },
                confirm: {
                    text: "Yes, remove it!",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            }
        }).then((value) => {
            if (value) {
                form.submit();
            }
        });
    });

    // SweetAlert confirmation for assign permission
    $(document).on('click', '.assign-permission-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const permissionName = $(this).closest('tr').find('.badge').text();
        const roleName = '{{ $role->name }}';
        
        swal({
            title: 'Assign Permission?',
            text: `Are you sure you want to assign permission '${permissionName}' to ${roleName}?`,
            icon: 'question',
            buttons: {
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "btn btn-danger",
                    closeModal: true,
                },
                confirm: {
                    text: "Yes, assign it!",
                    value: true,
                    visible: true,
                    className: "btn btn-primary",
                    closeModal: true
                }
            }
        }).then((value) => {
            if (value) {
                form.submit();
            }
        });
    });
</script>
@endpush 