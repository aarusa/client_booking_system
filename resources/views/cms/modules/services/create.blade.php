{{-- File: resources/views/cms/modules/services/create.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Create Service')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Add New Service</h3>
                <h6 class="op-7 mb-2">Fill out the form to create a new service.</h6>
            </div>
        </div>
        
        <form action="{{ route('services.store') }}" method="POST">
        @csrf
        
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label for="name">Service Name</label>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="name"
                                            name="name"
                                            value="{{ old('name') }}"
                                            placeholder="Enter Service Name"
                                        />
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea
                                            class="form-control @error('description') is-invalid @enderror"
                                            id="description"
                                            name="description"
                                            rows="4"
                                            placeholder="Enter service description"
                                        >{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="duration">Duration (minutes)</label>
                                        <input
                                            type="number"
                                            class="form-control @error('duration') is-invalid @enderror"
                                            id="duration"
                                            name="duration"
                                            value="{{ old('duration') }}"
                                            placeholder="Enter duration in minutes"
                                            min="1"
                                        />
                                        @error('duration')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="is_active"
                                                name="is_active"
                                                value="1"
                                                {{ old('is_active') ? 'checked' : '' }}
                                            />
                                            <label class="form-check-label" for="is_active">
                                                Active Service
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <i class="fas fa-dollar-sign me-2"></i>Pricing
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="price_small">Small Dog Price ($)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control @error('prices.small') is-invalid @enderror"
                                    id="price_small"
                                    name="prices[small]"
                                    value="{{ old('prices.small') }}"
                                    placeholder="0.00"
                                    min="0"
                                />
                                @error('prices.small')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="price_medium">Medium Dog Price ($)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control @error('prices.medium') is-invalid @enderror"
                                    id="price_medium"
                                    name="prices[medium]"
                                    value="{{ old('prices.medium') }}"
                                    placeholder="0.00"
                                    min="0"
                                />
                                @error('prices.medium')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="price_large">Large Dog Price ($)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    class="form-control @error('prices.large') is-invalid @enderror"
                                    id="price_large"
                                    name="prices[large]"
                                    value="{{ old('prices.large') }}"
                                    placeholder="0.00"
                                    min="0"
                                />
                                @error('prices.large')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    <strong>Dog Size Guidelines:</strong><br>
                                    • Small: Up to 20 lbs<br>
                                    • Medium: 21-50 lbs<br>
                                    • Large: Over 50 lbs
                                </small>
                            </div>
                        </div>
                        <div class="card-action">
                            <button class="btn btn-info" type="submit">Save</button>
                            <a href="{{ route('services.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>

@endsection

@push('scripts')
<script>
    // Auto-format price inputs
    document.querySelectorAll('input[name^="prices"]').forEach(function(input) {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });
</script>
@endpush 