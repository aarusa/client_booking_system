{{-- File: resources/views/cms/modules/users/permissions/create.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Create Permission')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Create New Permission</h3>
                <h6 class="op-7 mb-2">Add a new permission to the system</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="{{ route('permissions.index') }}" class="btn btn-label-info btn-round me-2">Back to Permissions</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permission Details</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permissions.store') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="name">Permission Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" 
                                       placeholder="Enter permission name (e.g., manage users)">
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
                                <button type="submit" class="btn btn-info">Save</button>
                                <a href="{{ route('permissions.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Permission Guidelines</div>
                    </div>
                    <div class="card-body">
                        <h6>Naming Conventions:</h6>
                        <ul class="list-unstyled">
                            <li>• Use lowercase letters</li>
                            <li>• Separate words with spaces</li>
                            <li>• Be descriptive and specific</li>
                        </ul>
                        
                        <h6 class="mt-3">Examples:</h6>
                        <ul class="list-unstyled">
                            <li>• view dashboard</li>
                            <li>• manage users</li>
                            <li>• edit roles</li>
                            <li>• delete permissions</li>
                            <li>• view reports</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
@endsection

{{-- Session messages are now handled centrally in master layout --}} 