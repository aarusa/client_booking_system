{{-- File: resources/views/cms/modules/services/show.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Service Details')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Service Details</h3>
                <h6 class="op-7 mb-2">View service information and pricing.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('edit service')
            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-round me-2">Edit Service</a>
            @endcan
            <a href="{{ route('services.index') }}" class="btn btn-secondary btn-round">Back to Services</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Service Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Service Name</label>
                                    <p class="form-control-static">{{ $service->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <p class="form-control-static">
                                        @if($service->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <p class="form-control-static">
                                {{ $service->description ?: 'No description provided' }}
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Duration</label>
                            <p class="form-control-static">{{ $service->duration }} minutes</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Created</label>
                            <p class="form-control-static">{{ $service->created_at->format('F j, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Last Updated</label>
                            <p class="form-control-static">{{ $service->updated_at->format('F j, Y \a\t g:i A') }}</p>
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
                        @if($service->prices->count() > 0)
                            @foreach($service->prices as $price)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong>{{ ucfirst($price->dog_size) }} Dog</strong>
                                        <br>
                                        <small class="text-muted">
                                            @if($price->dog_size === 'small')
                                                Up to 20 lbs
                                            @elseif($price->dog_size === 'medium')
                                                21-50 lbs
                                            @else
                                                Over 50 lbs
                                            @endif
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="h5 mb-0">${{ number_format($price->price, 2) }}</span>
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <hr>
                                @endif
                            @endforeach
                        @else
                            <p class="text-muted">No pricing information available.</p>
                        @endif
                    </div>
                </div>
                
                @if($service->appointments->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-calendar me-2"></i>Appointments
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            This service is used in {{ $service->appointments->count() }} appointment(s).
                        </p>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Cannot be deleted while in use.
                        </small>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

@endsection 