{{-- File: resources/views/cms/modules/clients/create.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Create Client')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Add New Client</h3>
                <h6 class="op-7 mb-2">Fill out the form to create a new client and their dogs.</h6>
            </div>
        </div>
        
        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
            @if($errors->has('general'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first('general') }}
                </div>
            @endif
        
            <div class="row">
                <div class="col-md-8">
                    <!-- Client Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-user me-2"></i>Client Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name"
                                            name="first_name"
                                            value="{{ old('first_name') }}"
                                            placeholder="Enter First Name"
                                            required
                                        />
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name"
                                            name="last_name"
                                            value="{{ old('last_name') }}"
                                            placeholder="Enter Last Name"
                                            required
                                        />
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input
                                            type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            id="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            placeholder="Enter Email"
                                            required
                                        />
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input
                                            type="text"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            placeholder="Enter Phone Number"
                                        />
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input
                                            type="text"
                                            class="form-control @error('address') is-invalid @enderror"
                                            id="address"
                                            name="address"
                                            value="{{ old('address') }}"
                                            placeholder="Enter Address"
                                        />
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input
                                            type="text"
                                            class="form-control @error('city') is-invalid @enderror"
                                            id="city"
                                            name="city"
                                            value="{{ old('city') }}"
                                            placeholder="Enter City"
                                        />
                                        @error('city')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input
                                            type="text"
                                            class="form-control @error('state') is-invalid @enderror"
                                            id="state"
                                            name="state"
                                            value="{{ old('state') }}"
                                            placeholder="Enter State"
                                        />
                                        @error('state')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label for="zipcode">Zip Code</label>
                                        <input
                                            type="text"
                                            class="form-control @error('zipcode') is-invalid @enderror"
                                            id="zipcode"
                                            name="zipcode"
                                            value="{{ old('zipcode') }}"
                                            placeholder="Enter Zip Code"
                                        />
                                        @error('zipcode')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="reminder">Reminder Preferences</label>
                                        <input
                                            type="text"
                                            class="form-control @error('reminder') is-invalid @enderror"
                                            id="reminder"
                                            name="reminder"
                                            value="{{ old('reminder') }}"
                                            placeholder="e.g., Text message, Email, Phone call"
                                        />
                                        @error('reminder')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea
                                            class="form-control @error('notes') is-invalid @enderror"
                                            id="notes"
                                            name="notes"
                                            rows="4"
                                            placeholder="Enter any additional notes about the client"
                                        >{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dogs Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-dog me-2"></i>Dog Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="dogs-container">
                                <div class="dog-entry" data-dog-index="0">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[0][name]">Dog Name <span class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    class="form-control @error('dogs.0.name') is-invalid @enderror"
                                                    id="dogs[0][name]"
                                                    name="dogs[0][name]"
                                                    value="{{ old('dogs.0.name') }}"
                                                    placeholder="Enter Dog Name"
                                                    required
                                                />
                                                @error('dogs.0.name')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[0][breed]">Breed</label>
                                                <input
                                                    type="text"
                                                    class="form-control @error('dogs.0.breed') is-invalid @enderror"
                                                    id="dogs[0][breed]"
                                                    name="dogs[0][breed]"
                                                    value="{{ old('dogs.0.breed') }}"
                                                    placeholder="Enter Breed"
                                                />
                                                @error('dogs.0.breed')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="dogs[0][age]">Age (years)</label>
                                                <input
                                                    type="number"
                                                    class="form-control @error('dogs.0.age') is-invalid @enderror"
                                                    id="dogs[0][age]"
                                                    name="dogs[0][age]"
                                                    value="{{ old('dogs.0.age') }}"
                                                    placeholder="Age"
                                                    min="0"
                                                    step="0.1"
                                                />
                                                @error('dogs.0.age')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="dogs[0][gender]">Gender</label>
                                                <select
                                                    class="form-control @error('dogs.0.gender') is-invalid @enderror"
                                                    id="dogs[0][gender]"
                                                    name="dogs[0][gender]"
                                                >
                                                    <option value="">Select Gender</option>
                                                    <option value="Male" {{ old('dogs.0.gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ old('dogs.0.gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                                @error('dogs.0.gender')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="dogs[0][weight]">Weight (lbs)</label>
                                                <input
                                                    type="number"
                                                    class="form-control @error('dogs.0.weight') is-invalid @enderror"
                                                    id="dogs[0][weight]"
                                                    name="dogs[0][weight]"
                                                    value="{{ old('dogs.0.weight') }}"
                                                    placeholder="Weight"
                                                    min="0"
                                                    step="0.1"
                                                />
                                                @error('dogs.0.weight')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[0][size]">Size <span class="text-danger">*</span></label>
                                                <select name="dogs[0][size]" class="form-control" required>
                                                    <option value="">Select Size</option>
                                                    <option value="small">Small (0-20 lbs)</option>
                                                    <option value="medium">Medium (21-50 lbs)</option>
                                                    <option value="large">Large (51-100 lbs)</option>
                                                    <option value="extra_large">Extra Large (100+ lbs)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[0][spayed_neutered]">Spayed/Neutered</label>
                                                <select
                                                    class="form-control @error('dogs.0.spayed_neutered') is-invalid @enderror"
                                                    id="dogs[0][spayed_neutered]"
                                                    name="dogs[0][spayed_neutered]"
                                                >
                                                    <option value="">Select Status</option>
                                                    <option value="Yes" {{ old('dogs.0.spayed_neutered') == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No" {{ old('dogs.0.spayed_neutered') == 'No' ? 'selected' : '' }}>No</option>
                                                    <option value="Unknown" {{ old('dogs.0.spayed_neutered') == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                                </select>
                                                @error('dogs.0.spayed_neutered')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="dogs[0][behavior]">Behavior Notes</label>
                                                <textarea
                                                    class="form-control @error('dogs.0.behavior') is-invalid @enderror"
                                                    id="dogs[0][behavior]"
                                                    name="dogs[0][behavior]"
                                                    rows="3"
                                                    placeholder="Any behavioral notes or special instructions"
                                                >{{ old('dogs.0.behavior') }}</textarea>
                                                @error('dogs.0.behavior')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="dogs[0][notes]">Additional Notes</label>
                                                <textarea
                                                    class="form-control @error('dogs.0.notes') is-invalid @enderror"
                                                    id="dogs[0][notes]"
                                                    name="dogs[0][notes]"
                                                    rows="3"
                                                    placeholder="Any additional notes about this dog"
                                                >{{ old('dogs.0.notes') }}</textarea>
                                                @error('dogs.0.notes')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary" id="add-dog-btn">
                                        <i class="fas fa-plus me-2"></i>Add Another Dog
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action">
                        <button class="btn btn-info" type="submit">Save Client & Dogs</button>
                        <a href="{{ route('clients.index') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-info-circle me-2"></i>Information
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Important Notes:</h6>
                                <ul class="mb-0">
                                    <li>Email address must be unique</li>
                                    <li>At least one dog is required</li>
                                    <li>Dog name is required for each dog</li>
                                    <li>You can add multiple dogs</li>
                                    <li>Phone number is optional but recommended</li>
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
    $(document).ready(function() {
        let dogIndex = 1;
        
        // Add another dog
        $('#add-dog-btn').click(function() {
            const dogEntry = `
                <div class="dog-entry" data-dog-index="${dogIndex}">
                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6>Dog #${dogIndex + 1}</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-dog-btn">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][name]">Dog Name <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="dogs[${dogIndex}][name]"
                                    name="dogs[${dogIndex}][name]"
                                    placeholder="Enter Dog Name"
                                    required
                                />
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][breed]">Breed</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="dogs[${dogIndex}][breed]"
                                    name="dogs[${dogIndex}][breed]"
                                    placeholder="Enter Breed"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][age]">Age (years)</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="dogs[${dogIndex}][age]"
                                    name="dogs[${dogIndex}][age]"
                                    placeholder="Age"
                                    min="0"
                                    step="0.1"
                                />
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][gender]">Gender</label>
                                <select class="form-control" id="dogs[${dogIndex}][gender]" name="dogs[${dogIndex}][gender]">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][weight]">Weight (lbs)</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="dogs[${dogIndex}][weight]"
                                    name="dogs[${dogIndex}][weight]"
                                    placeholder="Weight"
                                    min="0"
                                    step="0.1"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][size]">Size <span class="text-danger">*</span></label>
                                <select name="dogs[${dogIndex}][size]" class="form-control" required>
                                    <option value="">Select Size</option>
                                    <option value="small">Small (0-20 lbs)</option>
                                    <option value="medium">Medium (21-50 lbs)</option>
                                    <option value="large">Large (51-100 lbs)</option>
                                    <option value="extra_large">Extra Large (100+ lbs)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][spayed_neutered]">Spayed/Neutered</label>
                                <select class="form-control" id="dogs[${dogIndex}][spayed_neutered]" name="dogs[${dogIndex}][spayed_neutered]">
                                    <option value="">Select Status</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Unknown">Unknown</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][behavior]">Behavior Notes</label>
                                <textarea
                                    class="form-control"
                                    id="dogs[${dogIndex}][behavior]"
                                    name="dogs[${dogIndex}][behavior]"
                                    rows="3"
                                    placeholder="Any behavioral notes or special instructions"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="dogs[${dogIndex}][notes]">Additional Notes</label>
                                <textarea
                                    class="form-control"
                                    id="dogs[${dogIndex}][notes]"
                                    name="dogs[${dogIndex}][notes]"
                                    rows="3"
                                    placeholder="Any additional notes about this dog"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#dogs-container').append(dogEntry);
            dogIndex++;
        });
        
        // Remove dog
        $(document).on('click', '.remove-dog-btn', function() {
            $(this).closest('.dog-entry').remove();
        });
        
        // Form validation
        $('form').on('submit', function() {
            var isValid = true;
            
            // Check required fields
            $('input[required]').each(function() {
                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            // Email validation
            var email = $('#email').val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                $('#email').addClass('is-invalid');
                isValid = false;
            }
            
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