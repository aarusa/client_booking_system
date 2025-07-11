@if($appointments->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Client & Pet</th>
                    <th>Time</th>
                    <th>Services</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</div>
                                <small class="text-muted">{{ $appointment->dog->name }} ({{ $appointment->dog->breed ?? 'Unknown' }})</small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}</small>
                        </td>
                        <td>
                            @php
                                $services = json_decode($appointment->services_data ?? '[]', true);
                            @endphp
                            @foreach(array_slice($services, 0, 2) as $service)
                                <span class="badge bg-light text-dark me-1">{{ $service['name'] }}</span>
                            @endforeach
                            @if(count($services) > 2)
                                <span class="badge bg-secondary">+{{ count($services) - 2 }} more</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-primary',
                                    'confirmed' => 'bg-info',
                                    'in_progress' => 'bg-warning',
                                    'completed' => 'bg-success',
                                    'cancelled' => 'bg-danger'
                                ];
                                $statusLabels = [
                                    'scheduled' => 'Scheduled',
                                    'confirmed' => 'Confirmed',
                                    'in_progress' => 'In Progress',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$appointment->status] ?? 'bg-secondary' }}">
                                {{ $statusLabels[$appointment->status] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold">${{ number_format($appointment->total_price, 2) }}</div>
                            <small class="text-muted">
                                @if($appointment->payment_status === 'paid')
                                    <span class="text-success">Paid</span>
                                @elseif($appointment->payment_status === 'partial')
                                    <span class="text-warning">Partial</span>
                                @else
                                    <span class="text-danger">Pending</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                @can('appointment-edit')
                                <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('appointment-view')
                                <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-outline-info btn-sm" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-4">
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No appointments for {{ $formattedDate }}</h5>
        <p class="text-muted">All clear for this day!</p>
        @can('appointment-create')
        <a href="{{ route('appointments.create') }}" class="btn btn-primary">Schedule Appointment</a>
        @endcan
    </div>
@endif 