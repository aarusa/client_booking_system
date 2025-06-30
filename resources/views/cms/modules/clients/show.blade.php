{{-- File: resources/views/cms/modules/clients/show.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Client Details')

@section('content')
    
    <div class="page-inner">
        <!-- Header Section -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">{{ $client->first_name }} {{ $client->last_name }}</h3>
                <h6 class="op-7 mb-2">Client Profile</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                @can('create appointment')
                <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="btn btn-primary btn-round me-2">
                    <i class="fas fa-plus me-2"></i>New Appointment
                </a>
                @endcan
                @can('edit client')
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-round me-2">
                    <i class="fas fa-edit me-2"></i>Edit
                </a>
                @endcan
                <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-round">
                    <i class="fas fa-arrow-left me-2"></i>Back
                </a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Client Info & Stats -->
            <div class="col-lg-4">
                <!-- Client Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Email</label>
                            <div class="fw-bold">
                                <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Phone</label>
                            <div class="fw-bold">
                                @if($client->phone)
                                    <a href="tel:{{ $client->phone }}" class="text-decoration-none">{{ $client->phone }}</a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Address</label>
                            <div class="fw-bold">
                                @if($client->full_address)
                                    {{ $client->full_address }}
                                    <br>
                                    <a href="https://maps.google.com/?q={{ urlencode($client->full_address) }}" 
                                       target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>View on Maps
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Member Since</label>
                            <div class="fw-bold">{{ $client->created_at->format('M d, Y') }}</div>
                        </div>
                        @if($client->reminder)
                        <div class="mb-3">
                            <label class="text-muted small">Reminder Preference</label>
                            <div class="fw-bold">{{ $client->reminder }}</div>
                        </div>
                        @endif
                        @if($client->notes)
                        <div>
                            <label class="text-muted small">Notes</label>
                            <div class="fw-bold">{{ $client->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="h3 text-primary">{{ $client->dogs->count() }}</div>
                                <div class="text-muted small">Dogs</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h3 text-info">{{ $client->appointments->count() }}</div>
                                <div class="text-muted small">Appointments</div>
                            </div>
                            <div class="col-6">
                                <div class="h3 text-success">${{ number_format($client->total_earned, 2) }}</div>
                                <div class="text-muted small">Total Earned</div>
                            </div>
                            <div class="col-6">
                                <div class="h3 text-warning">{{ $client->appointments->where('status', 'scheduled')->count() }}</div>
                                <div class="text-muted small">Scheduled</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Dogs & Appointments -->
            <div class="col-lg-8">
                <!-- Dogs Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Dogs ({{ $client->dogs->count() }})</h5>
                        @can('edit client')
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Add Dog
                        </a>
                        @endcan
                    </div>
                    <div class="card-body">
                        @if($client->dogs->count() > 0)
                            <div class="row g-3">
                                @foreach($client->dogs as $dog)
                                    <div class="col-md-6">
                                        <div class="border rounded p-3 h-100">
                                            <!-- Dog Header -->
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div>
                                                    <h6 class="mb-1">{{ $dog->name }}</h6>
                                                    <span class="badge bg-secondary">{{ $dog->gender ?? 'Unknown' }}</span>
                                                </div>
                                                @can('create appointment')
                                                <a href="{{ route('appointments.create', ['client_id' => $client->id, 'dog_id' => $dog->id]) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i>Appointment
                                                </a>
                                                @endcan
                                            </div>
                                            
                                            <!-- Dog Details -->
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-2">Details</h6>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="text-muted small">Breed</div>
                                                        <div class="fw-bold">{{ $dog->breed ?? 'Unknown' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-muted small">Age</div>
                                                        <div class="fw-bold">{{ $dog->age ? $dog->age . ' years' : 'Unknown' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-muted small">Weight</div>
                                                        <div class="fw-bold">{{ $dog->weight ? $dog->weight . ' lbs' : 'Unknown' }}</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-muted small">Coat Type</div>
                                                        <div class="fw-bold">{{ $dog->coat_type ?? 'Unknown' }}</div>
                                                    </div>
                                                    @if($dog->spayed_neutered)
                                                    <div class="col-12">
                                                        <div class="text-muted small">Spayed/Neutered</div>
                                                        <div class="fw-bold">{{ $dog->spayed_neutered }}</div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Notes Section -->
                                            @if($dog->behavior || $dog->notes)
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-2">Notes</h6>
                                                @if($dog->behavior)
                                                <div class="mb-2">
                                                    <div class="text-muted small">Behavior</div>
                                                    <div class="small">{{ $dog->behavior }}</div>
                                                </div>
                                                @endif
                                                @if($dog->notes)
                                                <div>
                                                    <div class="text-muted small">Additional Notes</div>
                                                    <div class="small">{{ $dog->notes }}</div>
                                                </div>
                                                @endif
                                            </div>
                                            @endif

                                            <!-- Create Appointment Button -->
                                            @can('create appointment')
                                            <div class="border-top pt-3 mt-3">
                                                <a href="{{ route('appointments.create', ['client_id' => $client->id, 'dog_id' => $dog->id]) }}" 
                                                   class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-calendar-plus me-2"></i>Create Appointment for {{ $dog->name }}
                                                </a>
                                            </div>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-dog fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">No Dogs Registered</h6>
                                <p class="text-muted">This client hasn't registered any dogs yet.</p>
                                @can('edit client')
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add First Dog
                                </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Appointments</h5>
                    </div>
                    <div class="card-body">
                        @if($client->appointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Dog</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->appointments->take(5) as $appointment)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                                    <small class="text-muted">{{ $appointment->start_time->format('g:i A') }}</small>
                                                </td>
                                                <td>{{ $appointment->dog->name ?? 'Unknown' }}</td>
                                                <td>
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
                                                </td>
                                                <td class="fw-bold">${{ number_format($appointment->total_price, 2) }}</td>
                                                <td>
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($client->appointments->count() > 5)
                                <div class="text-center mt-3">
                                    <small class="text-muted">Showing 5 of {{ $client->appointments->count() }} appointments</small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">No Appointments</h6>
                                <p class="text-muted">This client hasn't scheduled any appointments yet.</p>
                                @can('create appointment')
                                <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Schedule First Appointment
                                </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection 