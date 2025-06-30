@extends('cms.layouts.master')

@section('title', 'Appointment Details')

@section('content')
    
    <div class="page-inner">
        <!-- Header Section -->
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-2">Appointment #{{ $appointment->id }}</h3>
                <p class="text-muted mb-0">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }} • {{ $appointment->dog->name }}</p>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <div class="btn-group" role="group">
                    @can('appointment-edit')
                    <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    @endcan
                    @can('appointment-delete')
                    <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline-block" class="delete-appointment-form ms-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm delete-appointment-btn">
                            <i class="fas fa-trash-alt me-1"></i>Delete
                        </button>
                    </form>
                    @endcan
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row">
            <!-- Left Column - Main Info -->
            <div class="col-lg-8">
                <!-- Appointment Overview -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Appointment Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="text-center me-3" style="width: 30px;">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Date & Time</small>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}</div>
                                        <div class="text-muted">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="text-center me-3" style="width: 30px;">
                                        <i class="fas fa-clock text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Duration</small>
                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->start_time)->diffInMinutes(\Carbon\Carbon::parse($appointment->end_time)) }} minutes</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="text-center me-3" style="width: 30px;">
                                        <i class="fas fa-tag text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Status</small>
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
                            </div>
                            
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <div class="text-center me-3" style="width: 30px;">
                                        <i class="fas fa-dollar-sign text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted d-block mb-1">Total Price</small>
                                        <div class="fw-bold h5 mb-0">${{ number_format($appointment->total_price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Services</h5>
                    </div>
                    <div class="card-body">
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
                        
                        @if(!empty($selectedServices))
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Service</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedServices as $service)
                                            @if(isset($services[$service['id']]))
                                                <tr>
                                                    <td>{{ $services[$service['id']] }}</td>
                                                    <td class="text-end">${{ number_format($service['price'] ?? 0, 2) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr class="table-active">
                                            <td><strong>Total</strong></td>
                                            <td class="text-end"><strong>${{ number_format($appointment->total_price, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">No services selected</p>
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
                                    <small class="text-muted d-block">Name</small>
                                    <strong>{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</strong>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block">Email</small>
                                    <a href="mailto:{{ $appointment->client->email }}" class="text-decoration-none">{{ $appointment->client->email }}</a>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block">Phone</small>
                                    @if($appointment->client->phone)
                                        <a href="tel:{{ $appointment->client->phone }}" class="text-decoration-none">{{ $appointment->client->phone }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                                
                                @if($appointment->client->full_address)
                                <div>
                                    <small class="text-muted d-block">Address</small>
                                    <strong>{{ $appointment->client->full_address }}</strong>
                                    <div class="mt-2">
                                        <a href="https://maps.google.com/?q={{ urlencode($appointment->client->full_address) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-map-marker-alt me-1"></i>View on Maps
                                        </a>
                                    </div>
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
                                    <small class="text-muted d-block">Name</small>
                                    <strong>{{ $appointment->dog->name }}</strong>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block">Breed</small>
                                    <strong>{{ $appointment->dog->breed ?? 'Unknown' }}</strong>
                                </div>
                                
                                @if($appointment->dog->age)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Age</small>
                                    <strong>{{ $appointment->dog->age }} years</strong>
                                </div>
                                @endif
                                
                                @if($appointment->dog->weight)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Weight</small>
                                    <strong>{{ $appointment->dog->weight }} lbs</strong>
                                </div>
                                @endif
                                
                                @if($appointment->dog->size)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Size</small>
                                    <strong>{{ ucfirst(str_replace('_', ' ', $appointment->dog->size)) }}</strong>
                                </div>
                                @endif
                                
                                @if($appointment->dog->coat_type)
                                <div class="mb-3">
                                    <small class="text-muted d-block">Coat Type</small>
                                    <strong>{{ $appointment->dog->coat_type }}</strong>
                                </div>
                                @endif
                                
                                @if($appointment->dog->gender)
                                <div>
                                    <small class="text-muted d-block">Gender</small>
                                    <strong>{{ $appointment->dog->gender }}</strong>
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
                            <small class="text-muted d-block">Payment Status</small>
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
                        
                        @if($appointment->payment_mode)
                        <div class="mb-3">
                            <small class="text-muted d-block">Payment Method</small>
                            <strong>{{ ucfirst($appointment->payment_mode) }}</strong>
                        </div>
                        @endif
                        
                        @if($appointment->amount_paid > 0)
                        <div class="mb-3">
                            <small class="text-muted d-block">Amount Paid</small>
                            <strong>${{ number_format($appointment->amount_paid, 2) }}</strong>
                        </div>
                        @endif
                        
                        @if($appointment->paid_at)
                        <div class="mb-3">
                            <small class="text-muted d-block">Paid On</small>
                            <strong>{{ $appointment->paid_at->format('M d, Y g:i A') }}</strong>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                @if($appointment->notes)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $appointment->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Additional Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Additional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Created</small>
                            <strong>{{ $appointment->created_at->format('M d, Y g:i A') }}</strong>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $appointment->updated_at->format('M d, Y g:i A') }}</strong>
                        </div>

                        @if($appointment->subscription)
                        <div>
                            <small class="text-muted d-block">Subscription</small>
                            <strong>{{ $appointment->subscription->subscription_name ?? 'N/A' }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
<script>
    // SweetAlert confirmation for delete appointment
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-appointment-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection 