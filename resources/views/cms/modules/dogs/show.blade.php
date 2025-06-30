{{-- File: resources/views/cms/modules/dogs/show.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Dog Details')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dog Details</h3>
                <h6 class="op-7 mb-2">View detailed information about the dog.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('edit dog')
            <a href="{{ route('dogs.edit', $dog->id) }}" class="btn btn-warning btn-round me-2">Edit Dog</a>
            @endcan
            <a href="{{ route('dogs.index') }}" class="btn btn-secondary btn-round">Back to List</a>
            </div>
        </div>
        
        <div class="row">
            <!-- Dog Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-dog me-2"></i>Dog Information
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $dog->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Owner:</strong></td>
                                <td>{{ $dog->client->full_name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Breed:</strong></td>
                                <td>{{ $dog->breed ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Age:</strong></td>
                                <td>{{ $dog->age ? $dog->age . ' years' : 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Gender:</strong></td>
                                <td>
                                    @if($dog->gender)
                                        <span class="badge bg-{{ $dog->gender == 'male' ? 'primary' : 'pink' }}">
                                            {{ ucfirst($dog->gender) }}
                                        </span>
                                    @else
                                        Unknown
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Weight:</strong></td>
                                <td>{{ $dog->weight ? $dog->weight . ' lbs' : 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Coat Type:</strong></td>
                                <td>{{ $dog->coat_type ? ucfirst($dog->coat_type) : 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Spayed/Neutered:</strong></td>
                                <td>{{ $dog->spayed_neutered ? ucfirst($dog->spayed_neutered) : 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tags:</strong></td>
                                <td>{{ $dog->tags ?? 'None' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Created:</strong></td>
                                <td>{{ $dog->created_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>{{ $dog->updated_at->format('M d, Y \a\t g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-user me-2"></i>Owner Information
                        </div>
                    </div>
                    <div class="card-body">
                        @if($dog->client)
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $dog->client->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $dog->client->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $dog->client->phone ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td>{{ $dog->client->full_address ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Dogs:</strong></td>
                                    <td>{{ $dog->client->dogs->count() }}</td>
                                </tr>
                            </table>
                            <div class="mt-3">
                                <a href="{{ route('clients.show', $dog->client->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Owner Details
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Owner information not available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Behavior & Notes -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-clipboard me-2"></i>Behavior Notes
                        </div>
                    </div>
                    <div class="card-body">
                        @if($dog->behavior)
                            <p>{{ $dog->behavior }}</p>
                        @else
                            <p class="text-muted">No behavior notes recorded.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-sticky-note me-2"></i>Additional Notes
                        </div>
                    </div>
                    <div class="card-body">
                        @if($dog->notes)
                            <p>{{ $dog->notes }}</p>
                        @else
                            <p class="text-muted">No additional notes recorded.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments History -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-calendar me-2"></i>Appointment History ({{ $dog->appointments->count() }})
                        </div>
                    </div>
                    <div class="card-body">
                        @if($dog->appointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Total Price</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dog->appointments->take(10) as $appointment)
                                            <tr>
                                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                                <td>{{ $appointment->start_time->format('g:i A') }}</td>
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
                                                <td>{{ Str::limit($appointment->notes, 30) ?? 'No notes' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($dog->appointments->count() > 10)
                                <div class="text-center mt-3">
                                    <p class="text-muted">Showing 10 of {{ $dog->appointments->count() }} appointments</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No appointments found for this dog.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection 