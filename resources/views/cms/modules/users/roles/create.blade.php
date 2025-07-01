{{-- File: resources/views/cms/dashboard.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Create Role')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Add New Role</h3>
                <h6 class="op-7 mb-2">Fill out the form to create a new system role.</h6>
            </div>
        </div>
        
        <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    
                                    <div class="form-group">
                                        <label for="role">Role Name</label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="role"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Enter role name"
                                        />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn btn-info" type="submit">Save</button>
                            <a href="{{ route('roles.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

{{-- Session messages are now handled centrally in master layout --}}

@endsection


