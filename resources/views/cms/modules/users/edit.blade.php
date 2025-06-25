{{-- File: resources/views/cms/dashboard.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Edit User')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Edit User</h3>
                <h6 class="op-7 mb-2">Fill out the form to edit user.</h6>
            </div>
        </div>
        
        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input
                                            type="text"
                                            class="form-control @error('fname') is-invalid @enderror"
                                            id="fname"
                                            name="fname"
                                            value="{{ old('fname', $user->fname) }}"
                                            placeholder="Enter First Name"
                                        />
                                        @error('fname')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input
                                            type="text"
                                            class="form-control @error('mname') is-invalid @enderror"
                                            id="mname"
                                            name="mname"
                                            value="{{ old('mname', $user->mname) }}"
                                            placeholder="Enter Middle Name"
                                        />
                                        @error('mname')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input
                                            type="text"
                                            class="form-control @error('lname') is-invalid @enderror"
                                            id="lname"
                                            name="lname"
                                            value="{{ old('lname', $user->lname) }}"
                                            placeholder="Enter Last Name"
                                        />
                                        @error('lname')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    
                                    <div class="form-group">
                                        <label for="email2">Email Address</label>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email2"
                                            name="email"
                                            value="{{ old('email', $user->email) }}"
                                            placeholder="Enter Email"
                                        />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input
                                            type="text"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            placeholder="Enter Phone Number"
                                        />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input
                                            type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password"
                                            name="password"
                                            placeholder="Password"
                                        />
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="defaultSelect">Role</label>
                                        <select
                                            class="form-select form-control @error('role') is-invalid @enderror"
                                            id="defaultSelect"
                                            name="role"
                                        >
                                            <option value="">Select Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}" {{ $user->roles->first()->name == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn btn-info" type="submit">Update</button>
                            <a href="{{ route('users.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-camera me-2"></i>Profile Photo
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Upload Photo</label>
                                <div class="custom-file">
                                    <input
                                        type="file"
                                        class="form-control @error('image') is-invalid @enderror"
                                        id="image"
                                        name="image"
                                        accept="image/*"
                                        onchange="previewImage(this)"
                                    />
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Only image files (JPG, PNG, GIF) up to 2MB are allowed.
                                    </small>
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3" style="display: none;">
                                <div class="text-center">
                                    <h6 class="text-muted mb-2">Image Preview</h6>
                                    <div class="border rounded p-2 bg-light">
                                        <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">
                                    </div>
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                            <i class="fas fa-trash me-1"></i>Remove Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Current User Image -->
                            @if($user->image)
                                <div id="currentImage" class="mt-3 text-center">
                                    <div class="border rounded p-2 bg-light">
                                        <h6 class="text-muted mb-2">Current Photo</h6>
                                        <img src="{{ asset($user->image) }}" alt="Current Photo" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;">
                                        <div class="mt-2">
                                            <small class="text-muted">Upload a new image to replace this one</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Default Avatar -->
                                <div id="defaultAvatar" class="mt-3 text-center">
                                    <div class="border rounded p-3 bg-light">
                                        <i class="fas fa-user-circle fa-4x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">No image selected</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const file = input.files[0];
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('imagePreview');
        const defaultAvatar = document.getElementById('defaultAvatar');
        const currentImage = document.getElementById('currentImage');
        
        // Validate file type
        if (file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Please select a valid image file (JPG, PNG, or GIF).');
                input.value = '';
                return;
            }
            
            // Validate file size (2MB = 2 * 1024 * 1024 bytes)
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('File size must be less than 2MB.');
                input.value = '';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                imagePreview.style.display = 'block';
                if (defaultAvatar) defaultAvatar.style.display = 'none';
                if (currentImage) currentImage.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            // Hide preview and show appropriate default
            imagePreview.style.display = 'none';
            if (defaultAvatar) defaultAvatar.style.display = 'block';
            if (currentImage) currentImage.style.display = 'block';
        }
    }
    
    function removeImage() {
        const input = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const defaultAvatar = document.getElementById('defaultAvatar');
        const currentImage = document.getElementById('currentImage');
        
        input.value = '';
        imagePreview.style.display = 'none';
        if (defaultAvatar) defaultAvatar.style.display = 'block';
        if (currentImage) currentImage.style.display = 'block';
    }
</script>
@endpush
