@extends('cms.layouts.master')

@section('title', 'Client Details - ' . $client->full_name)

@section('content')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Client Details</h3>
            <h6 class="op-7 mb-2">Complete overview of {{ $client->full_name }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            @can('edit client')
            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-round me-2">
                <i class="fas fa-edit"></i> Edit Client
            </a>
            @endcan
            <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-round">
                <i class="fas fa-arrow-left"></i> Back to Clients
            </a>
        </div>
    </div>

    <!-- Client Information Section -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user text-primary me-2"></i>
                        Client Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Full Name</label>
                                <p class="mb-0 fw-semibold">{{ $client->full_name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email Address</label>
                                <p class="mb-0">
                                    <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                        {{ $client->email }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Phone Number</label>
                                <p class="mb-0">
                                    @if($client->phone)
                                        <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                            {{ $client->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Address</label>
                                <p class="mb-0">
                                    @if($client->full_address)
                                        {{ $client->full_address }}
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small">Member Since</label>
                                <p class="mb-0">{{ $client->created_at->format('M d, Y') }}</p>
                            </div>
                            @if($client->reminder)
                            <div class="mb-3">
                                <label class="form-label text-muted small">Reminder</label>
                                <p class="mb-0">{{ $client->reminder }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($client->notes)
                    <div class="mt-3 pt-3 border-top">
                        <label class="form-label text-muted small">Notes</label>
                        <p class="mb-0">{{ $client->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Financial Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total Earned</span>
                            <span class="fw-bold text-success">${{ number_format($client->total_earned, 2) }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total Paid</span>
                            <span class="fw-bold text-primary">${{ number_format($client->total_paid, 2) }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Outstanding Balance</span>
                            <span class="fw-bold {{ $client->hasOutstandingPayments() ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($client->outstanding_balance, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Total Appointments</span>
                            <span class="fw-bold">{{ $client->appointments->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dogs Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-dog text-warning me-2"></i>
                        Dogs ({{ $client->dogs->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($client->dogs->count() > 0)
                        <div class="row">
                            @foreach($client->dogs as $dog)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card shadow-sm border-0 h-100 dog-card">
                                    <div class="card-header bg-info py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold text-white">
                                                <i class="fas fa-dog me-2"></i>{{ $dog->name }}
                                            </h6>
                                            <span class="badge bg-{{ $dog->gender === 'Male' ? 'light' : 'white' }} text-dark small">
                                                <i class="fas fa-{{ $dog->gender === 'Male' ? 'mars' : 'venus' }} me-1"></i>
                                                {{ $dog->gender ?? 'Unknown' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-paw text-warning me-2"></i>
                                                <span class="fw-semibold text-primary">{{ $dog->breed ?? 'Unknown breed' }}</span>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            @if($dog->age)
                                            <div class="col-4">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="text-muted small">Age</div>
                                                    <div class="fw-bold text-primary">{{ $dog->age }}y</div>
                                                </div>
                                            </div>
                                            @endif
                                            @if($dog->weight)
                                            <div class="col-4">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="text-muted small">Weight</div>
                                                    <div class="fw-bold text-success">{{ $dog->weight }}lbs</div>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="col-4">
                                                <div class="text-center p-2 bg-light rounded">
                                                    <div class="text-muted small">Size</div>
                                                    <div class="fw-bold text-info text-capitalize">{{ str_replace('_', ' ', $dog->size) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($dog->notes)
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-sticky-note text-muted me-2 mt-1"></i>
                                                <small class="text-muted">{{ Str::limit($dog->notes, 60) }}</small>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-dog text-muted fa-2x mb-3"></i>
                            <p class="text-muted mb-0">No dogs registered for this client</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt text-info me-2"></i>
                        Recent Appointments
                    </h5>
                    <a href="{{ route('appointments.create') }}?client_id={{ $client->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> New Appointment
                    </a>
                </div>
                <div class="card-body">
                    @if($client->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Dog</th>
                                        <th>Services</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->appointments->take(10)->sortByDesc('appointment_date') as $appointment)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $appointment->start_time->format('g:i A') }}</small>
                                        </td>
                                        <td>
                                            @if($appointment->dog)
                                                <span class="fw-semibold">{{ $appointment->dog->name }}</span>
                                            @else
                                                <span class="text-muted">No dog assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($appointment->services_data && is_array($appointment->services_data) && count($appointment->services_data) > 0)
                                                @foreach(array_slice($appointment->services_data, 0, 2) as $service)
                                                    @if(is_array($service) && isset($service['name']))
                                                        <span class="badge bg-light text-dark me-1">{{ $service['name'] }}</span>
                                                    @endif
                                                @endforeach
                                                @if(count($appointment->services_data) > 2)
                                                    <small class="text-muted">+{{ count($appointment->services_data) - 2 }} more</small>
                                                @endif
                                            @else
                                                <span class="text-muted">No services</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : ($appointment->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $appointment->payment_status_badge_class }}">
                                                {{ ucfirst($appointment->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="fw-semibold">${{ number_format($appointment->total_price, 2) }}</td>
                                        <td>
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($client->appointments->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('appointments.index') }}?client_id={{ $client->id }}" class="btn btn-outline-primary btn-sm">
                                    View All Appointments
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-muted fa-2x mb-3"></i>
                            <p class="text-muted mb-0">No appointments found for this client</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Session messages are now handled centrally in master layout --}} 