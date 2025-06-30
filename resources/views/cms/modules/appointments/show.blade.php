@extends('cms.layouts.master')

@section('title', 'Appointment Details')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Appointment Details</h3>
                <h6 class="op-7 mb-2">View appointment information and details.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0 d-flex gap-2 flex-wrap">
                @can('appointment-edit')
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm d-flex align-items-center">
                    <i class="fas fa-edit me-2"></i> Edit
                </a>
                @endcan
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-sm d-flex align-items-center">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Appointment Information</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="appointment_date">Appointment Date</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="time">Time</label>
                                    <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($appointment->status) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="total_price">Total Price</label>
                                    <input type="text" class="form-control" value="${{ number_format($appointment->total_price, 2) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="services">Services</label>
                            @php
                                $services = [
                                    1 => 'Basic Grooming',
                                    2 => 'Full Grooming',
                                    3 => 'Nail Trim',
                                    4 => 'Ear Cleaning',
                                    5 => 'De-shedding Treatment',
                                    6 => 'Puppy Grooming',
                                ];
                                
                                $selectedServices = json_decode($appointment->services_data ?? '[]', true) ?: [];
                            @endphp
                            
                            <div class="form-control" style="min-height: 50px; padding: 10px;">
                                @foreach($selectedServices as $serviceId)
                                    @if(isset($services[$serviceId]))
                                        <span class="badge bg-primary me-1">{{ $services[$serviceId] }}</span>
                                    @endif
                                @endforeach
                                @if(empty($selectedServices))
                                    <span class="text-muted">No services selected</span>
                                @endif
                            </div>
                        </div>

                        @if($appointment->notes)
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" rows="3" readonly>{{ $appointment->notes }}</textarea>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Client Information</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="client_name">Name</label>
                                    <input type="text" class="form-control" value="{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="client_email">Email</label>
                                    <input type="email" class="form-control" value="{{ $appointment->client->email }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="client_phone">Phone</label>
                                    <input type="text" class="form-control" value="{{ $appointment->client->phone ?: 'Not provided' }}" readonly>
                                </div>
                                @if($appointment->client->address)
                                <div class="form-group">
                                    <label for="client_address">Address</label>
                                    <textarea class="form-control" rows="2" readonly>{{ $appointment->client->address }}, {{ $appointment->client->city }}, {{ $appointment->client->state }} {{ $appointment->client->zipcode }}</textarea>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Dog Information</div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="dog_name">Name</label>
                                    <input type="text" class="form-control" value="{{ $appointment->dog->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="dog_breed">Breed</label>
                                    <input type="text" class="form-control" value="{{ $appointment->dog->breed ?? 'Unknown breed' }}" readonly>
                                </div>
                                @if($appointment->dog->age)
                                <div class="form-group">
                                    <label for="dog_age">Age</label>
                                    <input type="text" class="form-control" value="{{ $appointment->dog->age }} years" readonly>
                                </div>
                                @endif
                                @if($appointment->dog->weight)
                                <div class="form-group">
                                    <label for="dog_weight">Weight</label>
                                    <input type="text" class="form-control" value="{{ $appointment->dog->weight }} lbs" readonly>
                                </div>
                                @endif
                                @if($appointment->dog->coat_type)
                                <div class="form-group">
                                    <label for="dog_coat_type">Coat Type</label>
                                    <input type="text" class="form-control" value="{{ ucfirst($appointment->dog->coat_type) }}" readonly>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-info-circle me-2"></i>Additional Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Created</label>
                            <input type="text" class="form-control" value="{{ $appointment->created_at->format('M d, Y g:i A') }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Last Updated</label>
                            <input type="text" class="form-control" value="{{ $appointment->updated_at->format('M d, Y g:i A') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Duration</label>
                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($appointment->start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->end_time)) }} minutes" readonly>
                        </div>

                        @if($appointment->subscription)
                        <div class="form-group">
                            <label>Subscription</label>
                            <input type="text" class="form-control" value="{{ $appointment->subscription->name ?? 'N/A' }}" readonly>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection 