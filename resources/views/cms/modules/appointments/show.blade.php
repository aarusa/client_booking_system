@extends('cms.layouts.master')

@section('title', 'Appointment Details')

@section('content')
@php
    $statusMap = [
        'scheduled' => ['Scheduled', 'fa-calendar', 'bg-primary'],
        'confirmed' => ['Confirmed', 'fa-check-circle', 'bg-info'],
        'in_progress' => ['In Progress', 'fa-spinner', 'bg-warning'],
        'completed' => ['Completed', 'fa-check-double', 'bg-success'],
        'cancelled' => ['Cancelled', 'fa-times-circle', 'bg-danger'],
    ];
    $status = $statusMap[$appointment->status] ?? ['Unknown', 'fa-question-circle', 'bg-secondary'];
    $services = json_decode($appointment->services_data ?? '[]', true) ?: [];
@endphp
<div class="container-fluid">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-8 col-12 d-flex align-items-center gap-3">
            <span class="badge {{ $status[2] }} px-3 py-2 fs-6"><i class="fas {{ $status[1] }} me-1"></i>{{ $status[0] }}</span>
            <div class="fw-bold fs-4">Appointment Details</div>
            <div class="text-muted ms-3">
                <i class="fas fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('D, M d, Y') }}
                <span class="mx-2">|</span>
                <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i a') }}
                <span class="mx-2">|</span>
                <span class="badge bg-light text-dark border">One-off</span>
            </div>
        </div>
        <div class="col-md-4 col-12 text-md-end mt-3 mt-md-0">
            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning me-2"><i class="fas fa-edit me-1"></i>Edit</a>
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
        </div>
    </div>
    <div class="row g-4">
        <!-- Client & Pet Info -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Avatar" style="width: 90px; height: 90px; border-radius: 50%; background: #ffe6b3; object-fit: cover; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <div class="ms-3">
                            <div class="fw-bold fs-5 mb-1">{{ $appointment->dog->name }}</div>
                            <div class="text-muted small mb-1">
                                {{ $appointment->dog->breed ?? 'Unknown breed' }}
                                &bull; {{ $appointment->dog->weight ?? '0' }}kg
                                &bull; {{ $appointment->dog->coat_type ? ucfirst($appointment->dog->coat_type) : 'Unknown coat' }}
                                &bull; {{ $appointment->dog->age ? $appointment->dog->age . ' yrs' : 'N/A' }}
                            </div>
                            <div class="text-muted small">Size: {{ ucfirst($appointment->dog->size) }}</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-purple" style="background: #7c3aed; color: #fff; font-size: 0.95rem;"><i class="fas fa-crown me-1"></i> Tin</span>
                    </div>
                    <div class="fw-bold mt-3">Client</div>
                    <div class="mb-1">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</div>
                    <div class="mb-1"><a href="tel:{{ $appointment->client->phone }}" class="text-decoration-none"><i class="fas fa-phone me-1"></i>{{ $appointment->client->phone }}</a></div>
                    <div class="mb-1"><a href="https://maps.google.com/?q={{ urlencode($appointment->client->full_address) }}" target="_blank" class="text-decoration-none"><i class="fas fa-map-marker-alt me-1"></i>{{ $appointment->client->full_address ?? 'No address' }}</a></div>
                    <div class="mb-2">
                        <button class="btn btn-light btn-sm mt-2" title="Message" disabled><i class="far fa-comment-dots"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services Section -->
        <div class="col-lg-5 col-md-6">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <div class="fw-bold fs-5"><i class="fas fa-list-alt me-2"></i>Services</div>
                </div>
                <div class="card-body pt-2">
                    @if(count($services))
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($services as $service)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <div>
                                        <div class="fw-semibold">{{ $service['name'] ?? 'Unknown Service' }}</div>
                                        <div class="text-muted small">{{ ucfirst($appointment->dog->size) }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">${{ number_format($service['price'] ?? 0, 2) }}</div>
                                        <div class="text-muted small">{{ $appointment->start_time && $appointment->end_time ? \Carbon\Carbon::parse($appointment->start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->end_time)) . ' mins' : '' }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div class="fw-bold">Total</div>
                            <div class="fw-bold fs-5">${{ number_format($appointment->total_price, 2) }}</div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No services selected for this appointment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Behaviour Note -->
        <div class="col-lg-3 col-md-12">
            <div class="card h-100">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <div class="fw-bold fs-5"><i class="fas fa-paw me-2"></i>Pet Behaviour</div>
                </div>
                <div class="card-body pt-2">
                    @if($appointment->notes)
                        <div class="bg-light rounded p-3">{{ $appointment->notes }}</div>
                    @else
                        <div class="text-muted">No behaviour note recorded.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Groomer Name Footer -->
    <div class="row mt-4">
        <div class="col-12 text-end">
            <span class="text-muted">Groomer: <span class="fw-bold">Tin</span></span>
        </div>
    </div>
</div>
@endsection 