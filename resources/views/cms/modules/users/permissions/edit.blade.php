{{-- File: resources/views/cms/modules/users/permissions/edit.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Edit Permission')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Edit Permission</h3>
                <h6 class="op-7 mb-2">Modify permission details</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-round">Back to Permissions</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permission Details</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="name">Permission Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $permission->name) }}" 
                                       placeholder="Enter permission name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">
                                    Use descriptive names like "view dashboard", "manage users", "edit roles", etc.
                                </small>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-info">Update Permission</button>
                                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permission Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Current Name:</strong><br>
                            <span class="badge bg-primary">{{ $permission->name }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Guard Name:</strong><br>
                            <span class="badge bg-info">{{ $permission->guard_name }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Created:</strong><br>
                            <small class="text-muted">{{ $permission->created_at->format('M d, Y H:i') }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Last Updated:</strong><br>
                            <small class="text-muted">{{ $permission->updated_at->format('M d, Y H:i') }}</small>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Assigned to Roles:</strong><br>
                            @if($permission->roles->count() > 0)
                                @foreach($permission->roles as $role)
                                    <span class="badge bg-success me-1">{{ $role->name }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No roles assigned</span>
                            @endif
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
        showAlertOnce('success', "{{ session('success') }}");
    @endif

    // Check for error message
    @if (session('error'))
        showAlertOnce('error', "{{ session('error') }}");
    @endif
</script>
@endpush 