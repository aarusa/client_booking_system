@extends('cms.layouts.master')

@section('title', 'Appointment Details')

@section('content')
    
    <div class="page-inner">
        <!-- Header Section -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Appointment #{{ $appointment->id }}</h3>
                <h6 class="op-7 mb-2">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }} - {{ $appointment->dog->name }}</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                @can('appointment-edit')
                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-round me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @endcan
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-round">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Main Info -->
            <div class="col-lg-8">
                <!-- Appointment Details -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Appointment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Date</label>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Time</label>
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <div>
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'warning',
                                            'confirmed' => 'info',
                                            'in_progress' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Total Price</label>
                                <div class="fw-bold h5 text-success">${{ number_format($appointment->total_price, 2) }}</div>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="mt-4">
                            <label class="text-muted small">Services</label>
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
                            
                            <div class="mt-2">
                                @foreach($selectedServices as $serviceId)
                                    @if(isset($services[$serviceId]))
                                        <span class="badge bg-primary me-2 mb-2">{{ $services[$serviceId] }}</span>
                                    @endif
                                @endforeach
                                @if(empty($selectedServices))
                                    <span class="text-muted">No services selected</span>
                                @endif
                            </div>
                        </div>

                        @if($appointment->notes)
                        <div class="mt-4">
                            <label class="text-muted small">Notes</label>
                            <div class="bg-light p-3 rounded mt-2">
                                {{ $appointment->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Client & Dog Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Client Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small">Name</label>
                                    <div class="fw-bold">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Email</label>
                                    <div>
                                        <a href="mailto:{{ $appointment->client->email }}" class="text-decoration-none">{{ $appointment->client->email }}</a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Phone</label>
                                    <div>
                                        @if($appointment->client->phone)
                                            <a href="tel:{{ $appointment->client->phone }}" class="text-decoration-none">{{ $appointment->client->phone }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </div>
                                </div>
                                @if($appointment->client->full_address)
                                <div>
                                    <label class="text-muted small">Address</label>
                                    <div class="fw-bold">{{ $appointment->client->full_address }}</div>
                                    <a href="https://maps.google.com/?q={{ urlencode($appointment->client->full_address) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Maps
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Dog Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small">Name</label>
                                    <div class="fw-bold">{{ $appointment->dog->name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Breed</label>
                                    <div class="fw-bold">{{ $appointment->dog->breed ?? 'Unknown' }}</div>
                                </div>
                                @if($appointment->dog->age)
                                <div class="mb-3">
                                    <label class="text-muted small">Age</label>
                                    <div class="fw-bold">{{ $appointment->dog->age }} years</div>
                                </div>
                                @endif
                                @if($appointment->dog->weight)
                                <div class="mb-3">
                                    <label class="text-muted small">Weight</label>
                                    <div class="fw-bold">{{ $appointment->dog->weight }} lbs</div>
                                </div>
                                @endif
                                @if($appointment->dog->coat_type)
                                <div class="mb-3">
                                    <label class="text-muted small">Coat Type</label>
                                    <div class="fw-bold">{{ $appointment->dog->coat_type }}</div>
                                </div>
                                @endif
                                @if($appointment->dog->gender)
                                <div>
                                    <label class="text-muted small">Gender</label>
                                    <div class="fw-bold">{{ $appointment->dog->gender }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Additional Info -->
            <div class="col-lg-4">
                <!-- Payment Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Payment Status</label>
                            <div>
                                @php
                                    $paymentColors = [
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'partial' => 'info',
                                        'refunded' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $paymentColors[$appointment->payment_status] ?? 'secondary' }}">
                                    {{ ucfirst($appointment->payment_status) }}
                                </span>
                            </div>
                        </div>
                        @if($appointment->payment_mode)
                        <div class="mb-3">
                            <label class="text-muted small">Payment Method</label>
                            <div class="fw-bold">{{ ucfirst($appointment->payment_mode) }}</div>
                        </div>
                        @endif
                        @if($appointment->amount_paid > 0)
                        <div class="mb-3">
                            <label class="text-muted small">Amount Paid</label>
                            <div class="fw-bold text-success">${{ number_format($appointment->amount_paid, 2) }}</div>
                        </div>
                        @endif
                        @if($appointment->paid_at)
                        <div>
                            <label class="text-muted small">Paid On</label>
                            <div class="fw-bold">{{ $appointment->paid_at->format('M d, Y g:i A') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Duration</label>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->end_time)) }} minutes</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted small">Created</label>
                            <div class="fw-bold">{{ $appointment->created_at->format('M d, Y g:i A') }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted small">Last Updated</label>
                            <div class="fw-bold">{{ $appointment->updated_at->format('M d, Y g:i A') }}</div>
                        </div>

                        @if($appointment->subscription)
                        <div>
                            <label class="text-muted small">Subscription</label>
                            <div class="fw-bold">{{ $appointment->subscription->subscription_name ?? 'N/A' }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection 