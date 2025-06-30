{{-- File: resources/views/cms/modules/clients/edit.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Edit Client')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Edit Client</h3>
                <h6 class="op-7 mb-2">Update client and dog information.</h6>
            </div>
        </div>
        
        <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                                            value="{{ old('first_name', $client->first_name) }}"
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
                                            value="{{ old('last_name', $client->last_name) }}"
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
                                            value="{{ old('email', $client->email) }}"
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
                                            value="{{ old('phone', $client->phone) }}"
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
                                            value="{{ old('address', $client->address) }}"
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
                                            value="{{ old('city', $client->city) }}"
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
                                            value="{{ old('state', $client->state) }}"
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
                                            value="{{ old('zipcode', $client->zipcode) }}"
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
                                            value="{{ old('reminder', $client->reminder) }}"
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
                                        >{{ old('notes', $client->notes) }}</textarea>
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
                                @foreach($client->dogs as $index => $dog)
                                <div class="dog-entry" data-dog-index="{{ $index }}" data-dog-id="{{ $dog->id }}">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6>Dog #{{ $index + 1 }} - {{ $dog->name }}</h6>
                        @if($index > 0)
                        <button type="button" class="btn btn-sm btn-danger remove-dog-btn">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                        @endif
                                    </div>
                                    <input type="hidden" name="dogs[{{ $index }}][id]" value="{{ $dog->id }}">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][name]">Dog Name <span class="text-danger">*</span></label>
                                                <input
                                                    type="text"
                                                    class="form-control @error('dogs.'.$index.'.name') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][name]"
                                                    name="dogs[{{ $index }}][name]"
                                                    value="{{ old('dogs.'.$index.'.name', $dog->name) }}"
                                                    placeholder="Enter Dog Name"
                                                    required
                                                />
                                                @error('dogs.'.$index.'.name')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][breed]">Breed</label>
                                                <input
                                                    type="text"
                                                    class="form-control @error('dogs.'.$index.'.breed') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][breed]"
                                                    name="dogs[{{ $index }}][breed]"
                                                    value="{{ old('dogs.'.$index.'.breed', $dog->breed) }}"
                                                    placeholder="Enter Breed"
                                                />
                                                @error('dogs.'.$index.'.breed')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][age]">Age (years)</label>
                                                <input
                                                    type="number"
                                                    class="form-control @error('dogs.'.$index.'.age') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][age]"
                                                    name="dogs[{{ $index }}][age]"
                                                    value="{{ old('dogs.'.$index.'.age', $dog->age) }}"
                                                    placeholder="Age"
                                                    min="0"
                                                    step="0.1"
                                                />
                                                @error('dogs.'.$index.'.age')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][gender]">Gender</label>
                                                <select
                                                    class="form-control @error('dogs.'.$index.'.gender') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][gender]"
                                                    name="dogs[{{ $index }}][gender]"
                                                >
                                                    <option value="">Select Gender</option>
                                                    <option value="Male" {{ old('dogs.'.$index.'.gender', $dog->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ old('dogs.'.$index.'.gender', $dog->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                                </select>
                                                @error('dogs.'.$index.'.gender')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][weight]">Weight (lbs)</label>
                                                <input
                                                    type="number"
                                                    class="form-control @error('dogs.'.$index.'.weight') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][weight]"
                                                    name="dogs[{{ $index }}][weight]"
                                                    value="{{ old('dogs.'.$index.'.weight', $dog->weight) }}"
                                                    placeholder="Weight"
                                                    min="0"
                                                    step="0.1"
                                                />
                                                @error('dogs.'.$index.'.weight')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][coat_type]">Coat Type</label>
                                                <select
                                                    class="form-control @error('dogs.'.$index.'.coat_type') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][coat_type]"
                                                    name="dogs[{{ $index }}][coat_type]"
                                                >
                                                    <option value="">Select Coat Type</option>
                                                    <option value="Short" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Short' ? 'selected' : '' }}>Short</option>
                                                    <option value="Medium" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Medium' ? 'selected' : '' }}>Medium</option>
                                                    <option value="Long" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Long' ? 'selected' : '' }}>Long</option>
                                                    <option value="Curly" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Curly' ? 'selected' : '' }}>Curly</option>
                                                    <option value="Wiry" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Wiry' ? 'selected' : '' }}>Wiry</option>
                                                    <option value="Smooth" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Smooth' ? 'selected' : '' }}>Smooth</option>
                                                    <option value="Double" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Double' ? 'selected' : '' }}>Double</option>
                                                    <option value="Silky" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Silky' ? 'selected' : '' }}>Silky</option>
                                                    <option value="Corded" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Corded' ? 'selected' : '' }}>Corded</option>
                                                    <option value="Hairless" {{ old('dogs.'.$index.'.coat_type', $dog->coat_type) == 'Hairless' ? 'selected' : '' }}>Hairless</option>
                                                </select>
                                                @error('dogs.'.$index.'.coat_type')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][spayed_neutered]">Spayed/Neutered</label>
                                                <select
                                                    class="form-control @error('dogs.'.$index.'.spayed_neutered') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][spayed_neutered]"
                                                    name="dogs[{{ $index }}][spayed_neutered]"
                                                >
                                                    <option value="">Select Status</option>
                                                    <option value="Yes" {{ old('dogs.'.$index.'.spayed_neutered', $dog->spayed_neutered) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No" {{ old('dogs.'.$index.'.spayed_neutered', $dog->spayed_neutered) == 'No' ? 'selected' : '' }}>No</option>
                                                    <option value="Unknown" {{ old('dogs.'.$index.'.spayed_neutered', $dog->spayed_neutered) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                                </select>
                                                @error('dogs.'.$index.'.spayed_neutered')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][behavior]">Behavior Notes</label>
                                                <textarea
                                                    class="form-control @error('dogs.'.$index.'.behavior') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][behavior]"
                                                    name="dogs[{{ $index }}][behavior]"
                                                    rows="3"
                                                    placeholder="Any behavioral notes or special instructions"
                                                >{{ old('dogs.'.$index.'.behavior', $dog->behavior) }}</textarea>
                                                @error('dogs.'.$index.'.behavior')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label for="dogs[{{ $index }}][notes]">Additional Notes</label>
                                                <textarea
                                                    class="form-control @error('dogs.'.$index.'.notes') is-invalid @enderror"
                                                    id="dogs[{{ $index }}][notes]"
                                                    name="dogs[{{ $index }}][notes]"
                                                    rows="3"
                                                    placeholder="Any additional notes about this dog"
                                                >{{ old('dogs.'.$index.'.notes', $dog->notes) }}</textarea>
                                                @error('dogs.'.$index.'.notes')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
                        <button class="btn btn-info" type="submit">Update Client & Dogs</button>
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
                                <h6 class="alert-heading">Client Details:</h6>
                                <ul class="mb-0">
                                    <li><strong>Created:</strong> {{ $client->created_at->format('M d, Y') }}</li>
                                    <li><strong>Last Updated:</strong> {{ $client->updated_at->format('M d, Y') }}</li>
                                    <li><strong>Dogs:</strong> {{ $client->dogs->count() }} registered</li>
                                    <li><strong>Appointments:</strong> {{ $client->appointments->count() }} total</li>
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
        let dogIndex = {{ $client->dogs->count() }};
        
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
                                <label for="dogs[${dogIndex}][coat_type]">Coat Type</label>
                                <select
                                    class="form-control"
                                    id="dogs[${dogIndex}][coat_type]"
                                    name="dogs[${dogIndex}][coat_type]"
                                >
                                    <option value="">Select Coat Type</option>
                                    <option value="Short">Short</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Long">Long</option>
                                    <option value="Curly">Curly</option>
                                    <option value="Wiry">Wiry</option>
                                    <option value="Smooth">Smooth</option>
                                    <option value="Double">Double</option>
                                    <option value="Silky">Silky</option>
                                    <option value="Corded">Corded</option>
                                    <option value="Hairless">Hairless</option>
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