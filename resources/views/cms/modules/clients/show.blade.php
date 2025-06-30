{{-- File: resources/views/cms/modules/clients/show.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Client Details')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Client Details</h3>
                <h6 class="op-7 mb-2">View detailed information about the client.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('edit client')
            <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-round me-2">Edit Client</a>
            @endcan
            <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-round">Back to List</a>
            </div>
        </div>
        
        <div class="row">
            <!-- Client Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-user me-2"></i>Client Information
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $client->full_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $client->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td>{{ $client->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $client->full_address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Reminder:</strong></td>
                                <td>{{ $client->reminder ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Notes:</strong></td>
                                <td>{{ $client->notes ?? 'No notes' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $client->created_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $client->updated_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>Statistics
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-center">
                                    <h4 class="text-primary">{{ $client->dogs->count() }}</h4>
                                    <p class="text-muted">Dogs</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <h4 class="text-success">{{ $client->appointments->count() }}</h4>
                                    <p class="text-muted">Appointments</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="text-center">
                                    <h4 class="text-info">{{ $client->subscriptions->where('is_active', true)->count() }}</h4>
                                    <p class="text-muted">Active Subscriptions</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <h4 class="text-warning">{{ $client->appointments->where('status', 'completed')->count() }}</h4>
                                    <p class="text-muted">Completed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dogs Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-dog me-2"></i>Dogs ({{ $client->dogs->count() }})
                        </div>
                    </div>
                    <div class="card-body">
                        @if($client->dogs->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Breed</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Weight</th>
                                            <th>Coat Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->dogs as $dog)
                                            <tr>
                                                <td><strong>{{ $dog->name }}</strong></td>
                                                <td>{{ $dog->breed ?? 'Unknown' }}</td>
                                                <td>{{ $dog->age ?? 'Unknown' }}</td>
                                                <td>
                                                    @if($dog->gender)
                                                        <span class="badge bg-{{ $dog->gender == 'male' ? 'primary' : 'pink' }}">
                                                            {{ ucfirst($dog->gender) }}
                                                        </span>
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>
                                                <td>{{ $dog->weight ? $dog->weight . ' lbs' : 'Unknown' }}</td>
                                                <td>{{ $dog->coat_type ?? 'Unknown' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-dog fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No dogs registered for this client.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-calendar me-2"></i>Recent Appointments ({{ $client->appointments->count() }})
                        </div>
                    </div>
                    <div class="card-body">
                        @if($client->appointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Dog</th>
                                            <th>Status</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->appointments->take(5) as $appointment)
                                            <tr>
                                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                                <td>{{ $appointment->start_time->format('g:i A') }}</td>
                                                <td>{{ $appointment->dog->name ?? 'Unknown' }}</td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'scheduled' => 'secondary',
                                                            'confirmed' => 'info',
                                                            'in_progress' => 'warning',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColors[$appointment->status] ?? 'secondary' }}">
                                                        {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                                    </span>
                                                </td>
                                                <td>${{ number_format($appointment->total_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($client->appointments->count() > 5)
                                <div class="text-center mt-3">
                                    <p class="text-muted">Showing 5 of {{ $client->appointments->count() }} appointments</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No appointments found for this client.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subscriptions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-sync me-2"></i>Active Subscriptions ({{ $client->subscriptions->where('is_active', true)->count() }})
                        </div>
                    </div>
                    <div class="card-body">
                        @if($client->subscriptions->where('is_active', true)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Frequency</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Price/Session</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->subscriptions->where('is_active', true) as $subscription)
                                            <tr>
                                                <td><strong>{{ $subscription->subscription_name ?? 'Unnamed' }}</strong></td>
                                                <td>{{ ucfirst($subscription->frequency) }}</td>
                                                <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                                <td>{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'Ongoing' }}</td>
                                                <td>${{ number_format($subscription->price_per_session, 2) }}</td>
                                                <td>
                                                    <span class="badge bg-success">Active</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-sync fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No active subscriptions for this client.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection 