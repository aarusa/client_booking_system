@extends('cms.layouts.master')

@section('title', 'Client Details')

@section('content')
<div class="page-inner">
    <!-- Header Section -->
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-2">{{ $client->first_name }} {{ $client->last_name }}</h3>
            <p class="text-muted mb-0">Client since {{ $client->created_at->format('M Y') }}</p>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <div class="btn-group" role="group">
                @can('edit client')
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                @endcan
                @can('delete client')
                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block" class="delete-client-form ms-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm delete-client-btn">
                        <i class="fas fa-trash-alt me-1"></i>Delete
                    </button>
                </form>
                @endcan
                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm ms-1">
                    <i class="fas fa-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Left Column: Client Info & Stats -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted">Email:</span> <span class="fw-bold">{{ $client->email }}</span></div>
                    <div class="mb-2"><span class="text-muted">Phone:</span> <span class="fw-bold">{{ $client->phone ?? 'Not provided' }}</span></div>
                    @if($client->full_address)
                    <div class="mb-2"><span class="text-muted">Address:</span> <span class="fw-bold">{{ $client->full_address }}</span></div>
                    @endif
                    @if($client->reminder)
                    <div class="mb-2"><span class="text-muted">Reminder:</span> <span class="fw-bold">{{ $client->reminder }}</span></div>
                    @endif
                    @if($client->notes)
                    <div class="mb-2"><span class="text-muted">Notes:</span> <span class="fw-bold">{{ $client->notes }}</span></div>
                    @endif
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Stats</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2"><span class="text-muted">Total Paid:</span> <span class="fw-bold">${{ number_format($client->appointments->where('payment_status', 'paid')->sum('amount_paid'), 2) }}</span></div>
                    <div class="mb-2"><span class="text-muted">Appointments:</span> <span class="fw-bold">{{ $client->appointments->count() }}</span></div>
                    <div class="mb-2"><span class="text-muted">Scheduled:</span> <span class="fw-bold">{{ $client->appointments->where('status', 'scheduled')->count() }}</span></div>
                    <div class="mb-2"><span class="text-muted">Dogs:</span> <span class="fw-bold">{{ $client->dogs->count() }}</span></div>
                </div>
            </div>
        </div>
        <!-- Right Column: Dogs & Appointment History -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dogs</h5>
                </div>
                <div class="card-body">
                    @if($client->dogs->count() > 0)
                        <div class="row g-3">
                            @foreach($client->dogs as $dog)
                            <div class="col-md-12">
                                <div class="dog-simple-card p-3 h-100 mb-2">
                                    <div class="fw-bold mb-1">{{ $dog->name }}</div>
                                    <div class="small text-muted mb-1">{{ $dog->breed ?? 'Unknown' }}</div>
                                    <div class="small">Age: <span class="fw-bold text-dark">{{ $dog->age ? $dog->age . ' years' : 'Unknown' }}</span></div>
                                    <div class="small">Weight: <span class="fw-bold text-dark">{{ $dog->weight ? $dog->weight . ' lbs' : 'Unknown' }}</span></div>
                                    <div class="small">Coat: <span class="fw-bold text-dark">{{ $dog->coat_type ?? 'Unknown' }}</span></div>
                                    @if($dog->spayed_neutered)
                                    <div class="small">Spayed/Neutered: <span class="fw-bold text-dark">{{ $dog->spayed_neutered }}</span></div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">No dogs registered for this client.</div>
                    @endif
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Appointment History</h5>
                </div>
                <div class="card-body">
                    @if($client->appointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Dog</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->appointments->sortByDesc('appointment_date') as $appointment)
                                <tr>
                                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                    <td>{{ $appointment->dog->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $appointment->status === 'scheduled' ? 'warning' :
                                            ($appointment->status === 'confirmed' ? 'info' :
                                            ($appointment->status === 'in_progress' ? 'primary' :
                                            ($appointment->status === 'completed' ? 'success' :
                                            ($appointment->status === 'cancelled' ? 'danger' : 'secondary'))))
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($appointment->total_price, 2) }}</td>
                                    <td>
                                        @if($appointment->payment_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($appointment->payment_status === 'partial')
                                            <span class="badge bg-info">Partial</span>
                                        @elseif($appointment->payment_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($appointment->payment_status === 'refunded')
                                            <span class="badge bg-danger">Refunded</span>
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-muted">No appointments found for this client.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // SweetAlert confirmation for delete client
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-client-form').forEach(function(form) {
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

<style>
.dog-simple-card {
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
.card {
    border-radius: 10px;
}
.table th, .table td {
    vertical-align: middle;
}
</style>
@endsection 