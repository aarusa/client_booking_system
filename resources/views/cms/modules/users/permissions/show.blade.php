{{-- File: resources/views/cms/modules/users/permissions/show.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Permission Details')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Permission Details</h3>
                <h6 class="op-7 mb-2">View detailed information about this permission</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('permissions.index') }}" class="btn btn-label-info btn-round me-2">Back to Permissions</a>
                @can('edit permission')
                    <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-warning btn-round">Edit Permission</a>
                @endcan
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permission Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Permission Name:</strong></div>
                            <div class="col-sm-8">
                                <span class="badge bg-primary fs-6">{{ $permission->name }}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Guard Name:</strong></div>
                            <div class="col-sm-8">
                                <span class="badge bg-info">{{ $permission->guard_name }}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Created:</strong></div>
                            <div class="col-sm-8">
                                <small class="text-muted">{{ $permission->created_at->format('F d, Y \a\t H:i') }}</small>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Last Updated:</strong></div>
                            <div class="col-sm-8">
                                <small class="text-muted">{{ $permission->updated_at->format('F d, Y \a\t H:i') }}</small>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-users me-2"></i>Role Assignments
                        </div>
                    </div>
                    <div class="card-body">
                        @if($permission->roles->count() > 0)
                            <div class="mb-3">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    This permission is assigned to {{ $permission->roles->count() }} role(s):
                                </h6>
                            </div>
                            <div class="row">
                                @foreach($permission->roles as $role)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center p-3 border rounded bg-light">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-shield text-primary me-2"></i>
                                                    <span class="fw-bold text-primary">{{ $role->name }}</span>
                                                </div>
                                            </div>
                                            @if($role->name === 'Super Admin')
                                                <span class="badge bg-danger ms-2">
                                                    <i class="fas fa-shield-alt me-1"></i>Protected
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle text-warning fa-3x"></i>
                                </div>
                                <h6 class="text-muted mb-2">No roles assigned</h6>
                                <p class="text-muted small mb-0">This permission is not currently assigned to any roles.</p>
                                <small class="text-muted">Use the table below to assign this permission to roles.</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- All Roles Table --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-table me-2"></i>All Roles Status
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">
                                            <i class="fas fa-user-shield me-2"></i>Role Name
                                        </th>
                                        <th width="20%">
                                            <i class="fas fa-info-circle me-2"></i>Status
                                        </th>
                                        <th width="50%">
                                            <i class="fas fa-cogs me-2"></i>Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-shield text-primary me-2"></i>
                                                    <strong>{{ $role->name }}</strong>
                                                    @if($role->name === 'Super Admin')
                                                        <span class="badge bg-danger ms-2">
                                                            <i class="fas fa-shield-alt me-1"></i>Protected
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($role->hasPermissionTo($permission->name))
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Assigned
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-times me-1"></i>Not Assigned
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $canModifySuperAdmin = $loggedInUser->hasRole('Super Admin');
                                                    $isSuperAdminRole = $role->name === 'Super Admin';
                                                    $canModify = !$isSuperAdminRole || $canModifySuperAdmin;
                                                @endphp
                                                
                                                @if($canModify)
                                                    @if($role->hasPermissionTo($permission->name))
                                                        <form action="{{ route('permissions.revoke', ['roleId' => $role->id, 'permissionId' => $permission->id]) }}" 
                                                              method="POST" style="display:inline-block" class="revoke-permission-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            @can('manage permission')
                                                            <button type="submit" class="btn btn-danger btn-sm revoke-permission-btn">
                                                                <i class="fas fa-minus-circle me-1"></i>Remove
                                                            </button>
                                                            @endcan
                                                        </form>
                                                    @else
                                                        <form action="{{ route('permissions.assign', ['roleId' => $role->id, 'permissionId' => $permission->id]) }}" 
                                                              method="POST" style="display:inline-block" class="assign-permission-form">
                                                            @csrf
                                                            @can('manage permission')
                                                            <button type="submit" class="btn btn-success btn-sm assign-permission-btn">
                                                                <i class="fas fa-plus-circle me-1"></i>Assign
                                                            </button>
                                                            @endcan
                                                        </form>
                                                    @endif
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-lock me-1"></i>No actions available
                                                    </span>
                                                @endif
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

    </div>
    
@endsection

@push('scripts')
<script>
    // Session messages are now handled centrally in master layout

    // SweetAlert confirmation for revoke permission
    $(document).on('click', '.revoke-permission-btn', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const roleName = $(this).closest('tr').find('strong').text();
        const permissionName = '{{ $permission->name }}';
        
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
        const roleName = $(this).closest('tr').find('strong').text();
        const permissionName = '{{ $permission->name }}';
        
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