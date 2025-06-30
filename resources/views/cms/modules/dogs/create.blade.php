{{-- File: resources/views/cms/modules/dogs/create.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Add New Dog')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Add New Dog</h3>
                <h6 class="op-7 mb-2">Register a new dog for a client.</h6>
            </div>
        </div>
        
        <form action="{{ route('dogs.store') }}" method="POST">
        @csrf
        
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="client_id">Owner (Client) <span class="text-danger">*</span></label>
                                        <select
                                            class="form-select form-control @error('client_id') is-invalid @enderror"
                                            id="client_id"
                                            name="client_id"
                                            required
                                        >
                                            <option value="">Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->full_name }} ({{ $client->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="name">Dog Name <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Enter Dog Name"
                                            required
                                        />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="breed">Breed</label>
                                        <input
                                            type="text"
                                            class="form-control @error('breed') is-invalid @enderror"
                                            id="breed"
                                            name="breed"
                                            value="{{ old('breed') }}"
                                            placeholder="e.g., Golden Retriever"
                                        />
                                        @error('breed')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="age">Age (Years)</label>
                                        <input
                                            type="number"
                                            class="form-control @error('age') is-invalid @enderror"
                                            id="age"
                                            name="age"
                                            value="{{ old('age') }}"
                                            placeholder="Age"
                                            min="0"
                                            max="30"
                                        />
                                        @error('age')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="gender">Gender</label>
                                        <select
                                            class="form-select form-control @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender"
                                        >
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="weight">Weight (lbs)</label>
                                        <input
                                            type="number"
                                            step="0.1"
                                            class="form-control @error('weight') is-invalid @enderror"
                                            id="weight"
                                            name="weight"
                                            value="{{ old('weight') }}"
                                            placeholder="Weight"
                                            min="0"
                                            max="200"
                                        />
                                        @error('weight')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="size">Size <span class="text-danger">*</span></label>
                                        <select
                                            class="form-select form-control @error('size') is-invalid @enderror"
                                            id="size"
                                            name="size"
                                            required
                                        >
                                            <option value="">Select Size</option>
                                            <option value="small" {{ old('size') == 'small' ? 'selected' : '' }}>Small (0-20 lbs)</option>
                                            <option value="medium" {{ old('size') == 'medium' ? 'selected' : '' }}>Medium (21-50 lbs)</option>
                                            <option value="large" {{ old('size') == 'large' ? 'selected' : '' }}>Large (51-100 lbs)</option>
                                            <option value="extra_large" {{ old('size') == 'extra_large' ? 'selected' : '' }}>Extra Large (100+ lbs)</option>
                                        </select>
                                        @error('size')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="coat_type">Coat Type</label>
                                        <select
                                            class="form-select form-control @error('coat_type') is-invalid @enderror"
                                            id="coat_type"
                                            name="coat_type"
                                        >
                                            <option value="">Select Coat Type</option>
                                            <option value="short" {{ old('coat_type') == 'short' ? 'selected' : '' }}>Short</option>
                                            <option value="medium" {{ old('coat_type') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="long" {{ old('coat_type') == 'long' ? 'selected' : '' }}>Long</option>
                                            <option value="curly" {{ old('coat_type') == 'curly' ? 'selected' : '' }}>Curly</option>
                                            <option value="wire" {{ old('coat_type') == 'wire' ? 'selected' : '' }}>Wire</option>
                                        </select>
                                        @error('coat_type')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="spayed_neutered">Spayed/Neutered</label>
                                        <select
                                            class="form-select form-control @error('spayed_neutered') is-invalid @enderror"
                                            id="spayed_neutered"
                                            name="spayed_neutered"
                                        >
                                            <option value="">Select Status</option>
                                            <option value="yes" {{ old('spayed_neutered') == 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ old('spayed_neutered') == 'no' ? 'selected' : '' }}>No</option>
                                            <option value="unknown" {{ old('spayed_neutered') == 'unknown' ? 'selected' : '' }}>Unknown</option>
                                        </select>
                                        @error('spayed_neutered')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="behavior">Behavior Notes</label>
                                        <textarea
                                            class="form-control @error('behavior') is-invalid @enderror"
                                            id="behavior"
                                            name="behavior"
                                            rows="3"
                                            placeholder="e.g., Friendly, Aggressive with other dogs, Nervous around strangers"
                                        >{{ old('behavior') }}</textarea>
                                        @error('behavior')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="tags">Tags</label>
                                        <input
                                            type="text"
                                            class="form-control @error('tags') is-invalid @enderror"
                                            id="tags"
                                            name="tags"
                                            value="{{ old('tags') }}"
                                            placeholder="e.g., Senior, Special needs, VIP"
                                        />
                                        @error('tags')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="notes">Additional Notes</label>
                                        <textarea
                                            class="form-control @error('notes') is-invalid @enderror"
                                            id="notes"
                                            name="notes"
                                            rows="4"
                                            placeholder="Enter any additional notes about the dog"
                                        >{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn btn-info" type="submit">Register Dog</button>
                            <a href="{{ route('dogs.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-info-circle me-2"></i>Dog Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Important Notes:</h6>
                                <ul class="mb-0">
                                    <li>Client selection is required</li>
                                    <li>Dog name is required</li>
                                    <li>Age should be in years</li>
                                    <li>Weight should be in pounds</li>
                                    <li>Behavior notes help with service planning</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
@endsection

@push('scripts')
<script>
    // Form validation
    $(document).ready(function() {
        $('form').on('submit', function() {
            var isValid = true;
            
            // Check required fields
            $('input[required], select[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if (!isValid) {
                swal({
                    title: "Validation Error!",
                    text: "Please fill in all required fields correctly.",
                    icon: "error",
                    button: "OK",
                });
                return false;
            }
        });
    });
</script>
@endpush 