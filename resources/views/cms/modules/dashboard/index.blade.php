@extends('cms.layouts.master')

@section('title', 'Dashboard | Pet Grooming CMS')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Welcome to your pet grooming business overview</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                @can('appointment-create')
                <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-round me-2">
                    <i class="fas fa-plus me-2"></i>New Appointment
                </a>
                @endcan
                @can('add client')
                <a href="{{ route('clients.create') }}" class="btn btn-info btn-round">
                    <i class="fas fa-user-plus me-2"></i>Add Client
                </a>
                @endcan
            </div>
        </div>

        <!-- Statistics Cards -->
        @can('view dashboard stats')
        <div class="row mb-4">
            <div class="col-sm-6 col-md-3 mb-3">
                <div class="simple-widget">
                    <div class="simple-widget-icon"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="simple-widget-value">{{ number_format($stats['total_clients']) }}</div>
                        <div class="simple-widget-label">Total Clients</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-3">
                <div class="simple-widget">
                    <div class="simple-widget-icon"><i class="fas fa-calendar"></i></div>
                    <div>
                        <div class="simple-widget-value">{{ $appointmentStats['today'] }}</div>
                        <div class="simple-widget-label">Today's Appointments</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-3">
                <div class="simple-widget">
                    <div class="simple-widget-icon"><i class="fas fa-dollar-sign"></i></div>
                    <div>
                        <div class="simple-widget-value">${{ number_format($financialStats['this_month_earnings'], 2) }}</div>
                        <div class="simple-widget-label">This Month's Earnings</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 mb-3">
                <div class="simple-widget">
                    <div class="simple-widget-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="simple-widget-value">{{ $appointmentStats['scheduled'] }}</div>
                        <div class="simple-widget-label">Scheduled Appointments</div>
                    </div>
                </div>
            </div>
        </div>
        @endcan

        <!-- Main Content Row -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Today's Appointments -->
                @can('view dashboard appointments')
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title d-flex align-items-center">
                                <button class="btn btn-icon btn-link btn-sm me-2" id="prevDateBtn" data-date="{{ $selectedDate->copy()->subDay()->format('Y-m-d') }}">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span id="dateDisplay">{{ $selectedDate->format('l, M d, Y') }} Appointments</span>
                                <button class="btn btn-icon btn-link btn-sm ms-2" id="nextDateBtn" data-date="{{ $selectedDate->copy()->addDay()->format('Y-m-d') }}">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary ms-3" id="todayBtn" style="display: {{ $selectedDate->format('Y-m-d') !== Carbon\Carbon::today()->format('Y-m-d') ? 'inline-block' : 'none' }};">
                                    <i class="fas fa-calendar-day me-1"></i>Today
                                </button>
                            </div>
                            <div class="card-tools">
                                @can('appointment-access')
                                <a href="{{ route('appointments.index') }}" class="btn btn-label-info btn-round btn-sm">
                                    <span class="btn-label">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                    View All
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="appointmentsContainer">
                        @if($selectedDateAppointments->count() > 0)
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
                                        @foreach($selectedDateAppointments as $appointment)
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
                                                        @elseif($appointment->payment_status === 'refunded')
                                                            <span class="text-info">Refunded</span>
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
                                                        <button type="button" class="btn btn-outline-info btn-sm view-appointment-btn" data-appointment-id="{{ $appointment->id }}" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
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
                                <h5 class="text-muted">No appointments for {{ $selectedDate->format('l, M d, Y') }}</h5>
                                <p class="text-muted">All clear for this day!</p>
                                @can('appointment-create')
                                <a href="{{ route('appointments.create') }}" class="btn btn-primary">Schedule Appointment</a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
                @endcan

                <!-- Business Performance & Service Revenue Row -->
                <!-- <div class="row">
                    @can('view dashboard business metrics')
                    <div class="col-md-6 mb-4">
                        <div class="card card-round h-100">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title">Business Performance</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="text-center p-3 border rounded">
                                            <div class="h4 text-primary mb-1">${{ number_format($businessMetrics['avg_appointment_value'], 2) }}</div>
                                            <small class="text-muted">Avg. Appointment Value</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 border rounded">
                                            <div class="h4 text-success mb-1">{{ number_format($businessMetrics['avg_appointments_per_day'], 1) }}</div>
                                            <small class="text-muted">Avg. Appointments/Day</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 border rounded">
                                            <div class="h4 text-info mb-1">{{ $businessMetrics['client_retention_rate'] }}%</div>
                                            <small class="text-muted">Client Retention Rate</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 border rounded">
                                            <div class="h4 text-warning mb-1">
                                                @if($businessMetrics['peak_hours']->count() > 0)
                                                    {{ $businessMetrics['peak_hours']->first()['time'] }}
                                                @else
                                                    N/A
                                                @endif
                                            </div>
                                            <small class="text-muted">Peak Hour</small>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($businessMetrics['peak_hours']->count() > 0)
                                    <div class="mt-3">
                                        <h6 class="text-muted mb-2">Peak Hours (Last 3 Months)</h6>
                                        <div class="d-flex justify-content-between">
                                            @foreach($businessMetrics['peak_hours'] as $peak)
                                                <div class="text-center">
                                                    <div class="fw-bold">{{ $peak['time'] }}</div>
                                                    <small class="text-muted">{{ $peak['count'] }} appointments</small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endcan

                    @can('view dashboard service revenue')
                    <div class="col-md-6 mb-4">
                        <div class="card card-round h-100">
                            <div class="card-header">
                                <div class="card-head-row">
                                    <div class="card-title">Service Revenue (This Month)</div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($serviceRevenue->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Service</th>
                                                    <th class="text-end">Revenue</th>
                                                    <th class="text-end">Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($serviceRevenue as $service)
                                                    <tr>
                                                        <td>{{ $service->name }}</td>
                                                        <td class="text-end">
                                                            <span class="fw-bold text-success">${{ number_format($service->total_revenue, 2) }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="badge bg-primary">{{ $service->appointment_count }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <p class="text-muted">No revenue data available for this month</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endcan
                </div> -->

                <!-- Most Active Clients -->
                <!-- @can('view dashboard client activity')
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Most Active Clients (Last 30 Days)</div>
                            <div class="card-tools">
                                @can('view client')
                                <a href="{{ route('clients.index') }}" class="btn btn-label-info btn-round btn-sm">
                                    <span class="btn-label">
                                        <i class="fa fa-users"></i>
                                    </span>
                                    View All Clients
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentClientActivity->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Client</th>
                                            <th>Recent Appointments</th>
                                            <th>Total This Month</th>
                                            <th>Last Visit</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentClientActivity as $client)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <div class="avatar-title rounded-circle bg-primary">
                                                                {{ strtoupper(substr($client->first_name, 0, 1)) }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $client->first_name }} {{ $client->last_name }}</div>
                                                            <small class="text-muted">{{ $client->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $client->appointments_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">{{ $client->appointments_count }}</span>
                                                </td>
                                                <td>
                                                    @if($client->appointments->count() > 0)
                                                        <div class="fw-bold">{{ \Carbon\Carbon::parse($client->appointments->first()->appointment_date)->format('M d, Y') }}</div>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($client->appointments->first()->start_time)->format('h:i A') }}</small>
                                                    @else
                                                        <span class="text-muted">No recent visits</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        @can('view client')
                                                        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-outline-info btn-sm" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @endcan
                                                        @can('appointment-create')
                                                        <a href="{{ route('appointments.create', ['client_id' => $client->id]) }}" class="btn btn-outline-primary btn-sm" title="New Appointment">
                                                            <i class="fas fa-plus"></i>
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
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No recent client activity</h5>
                                <p class="text-muted">No clients have had appointments in the last 30 days.</p>
                                @can('view client')
                                <a href="{{ route('clients.index') }}" class="btn btn-primary">View All Clients</a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
                @endcan -->
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                @can('view dashboard financial summary')
                <!-- Financial Summary -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Financial Summary</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Earnings</span>
                                <span class="fw-bold">${{ number_format($financialStats['total_earnings'], 2) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>This Month</span>
                                <span class="fw-bold text-success">${{ number_format($financialStats['this_month_earnings'], 2) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Pending Payments</span>
                                <span class="fw-bold text-warning">${{ number_format($financialStats['pending_payments'], 2) }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Paid Amount</span>
                                <span class="fw-bold text-info">${{ number_format($financialStats['paid_amount'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                @can('view dashboard upcoming appointments')
                <!-- Upcoming Appointments -->
                <!-- <div class="card card-round mb-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Upcoming Appointments</div>
                            <div class="card-tools">
                                @can('appointment-access')
                                <a href="{{ route('appointments.index') }}" class="btn btn-icon btn-link btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentAppointments->count() > 0)
                            <div class="appointment-list">
                                @foreach($recentAppointments->take(4) as $appointment)
                                    @can('appointment-view')
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="text-decoration-none">
                                        <div class="appointment-item d-flex align-items-center mb-3 p-2 border rounded appointment-card">
                                    @else
                                    <div class="text-decoration-none">
                                        <div class="appointment-item d-flex align-items-center mb-3 p-2 border rounded appointment-card no-click">
                                    @endcan
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title rounded-circle bg-primary">
                                                    {{ strtoupper(substr($appointment->client->first_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold small text-dark">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</div>
                                                <div class="text-muted small">{{ $appointment->dog->name }}</div>
                                                <div class="text-muted small">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d') }}
                                                    <i class="fas fa-clock ms-2 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @php
                                                    $statusColors = [
                                                        'scheduled' => 'bg-primary',
                                                        'confirmed' => 'bg-info',
                                                        'in_progress' => 'bg-warning',
                                                        'completed' => 'bg-success',
                                                        'cancelled' => 'bg-danger'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $statusColors[$appointment->status] ?? 'bg-secondary' }} small">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @can('appointment-view')
                                    </a>
                                    @else
                                    </div>
                                    @endcan
                                @endforeach
                                @if($recentAppointments->count() > 4)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">+{{ $recentAppointments->count() - 4 }} more appointments</small>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                <p class="text-muted small mb-0">No upcoming appointments</p>
                            </div>
                        @endif
                    </div>
                </div> -->
                @endcan

                @can('view dashboard dog breeds')
                <!-- Dog Breeds -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Popular Dog Breeds</div>
                            <div class="card-tools">
                                @can('view dog')
                                <a href="{{ route('dogs.index') }}" class="btn btn-icon btn-link btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($dogBreeds->count() > 0)
                            <div class="breed-list">
                                @foreach($dogBreeds as $breed)
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                        <div>
                                            <div class="fw-bold">{{ $breed->breed ?: 'Unknown Breed' }}</div>
                                            <small class="text-muted">{{ $breed->count }} {{ Str::plural('dog', $breed->count) }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ $breed->count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($dogBreeds->count() == 8)
                                <div class="text-center mt-2">
                                    <small class="text-muted">Showing top 8 breeds</small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-dog fa-2x text-muted mb-2"></i>
                                <p class="text-muted small mb-0">No breed data available</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
    
    {{-- Offcanvas Sidebar for Appointment Details --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="appointmentSidebar" aria-labelledby="appointmentSidebarLabel" style="width: 420px;">
        <div id="appointmentSidebarContent">
            <!-- Content will be loaded here -->
            <div class="d-flex justify-content-center align-items-center h-100">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
<style>
.appointment-card {
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    background-color: #fff;
}

.appointment-card:hover {
    background-color: #f8f9fa;
    border-color: #007bff !important;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.15);
    transform: translateY(-1px);
}

.appointment-card:active {
    transform: translateY(0);
    box-shadow: 0 1px 4px rgba(0, 123, 255, 0.2);
}

/* Style for non-clickable appointment cards */
.appointment-card.no-click {
    cursor: default;
}

.appointment-card.no-click:hover {
    background-color: #fff;
    border-color: #dee2e6 !important;
    box-shadow: none;
    transform: none;
}
</style>
<script>
$(document).ready(function() {
    // Force load today's appointments on page load
    const today = new Date().toISOString().split('T')[0];
    console.log('Page loaded, forcing today\'s date:', today);
    
    // Initialize navigation event listeners
    initializeNavigation();
    
    // Load today's appointments if not already loaded
    if (currentDate !== today) {
        console.log('Current date is not today, loading today\'s appointments');
        navigateDate(today);
    }
    
    // Auto-refresh appointments every 30 seconds
    setInterval(function() {
        if (currentDate) {
            refreshAppointments(currentDate);
        }
    }, 30000);
    
    // Handle view appointment button clicks
    $(document).on('click', '.view-appointment-btn', function() {
        var appointmentId = $(this).data('appointment-id');
        var sidebar = new bootstrap.Offcanvas(document.getElementById('appointmentSidebar'));
        var content = document.getElementById('appointmentSidebarContent');
        
        // Show loading state
        content.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        
        // Show sidebar
        sidebar.show();
        
        // Load appointment details
        fetch('/appointments/' + appointmentId + '/sidebar')
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML = '<div class="alert alert-danger m-3">Failed to load appointment details.</div>';
            });
    });
});

// Current selected date
let currentDate = '{{ $selectedDate->format('Y-m-d') }}';

// Function to initialize navigation event listeners
function initializeNavigation() {
    // Bind event listeners to navigation buttons
    bindNavigationEvents();
}

// Function to bind navigation events
function bindNavigationEvents() {
    // Previous date button
    $(document).on('click', '#prevDateBtn', function(e) {
        e.preventDefault();
        const date = $(this).data('date');
        console.log('Prev button clicked, date:', date);
        if (date) {
            navigateDate(date);
        }
    });
    
    // Next date button
    $(document).on('click', '#nextDateBtn', function(e) {
        e.preventDefault();
        const date = $(this).data('date');
        console.log('Next button clicked, date:', date);
        if (date) {
            navigateDate(date);
        }
    });
    
    // Today button
    $(document).on('click', '#todayBtn', function(e) {
        e.preventDefault();
        console.log('Today button clicked');
        navigateToToday();
    });
    

}

// Function to navigate between dates using AJAX
function navigateDate(date) {
    console.log('navigateDate called with date:', date);
    
    // Show loading state
    showLoadingState();
    
    // Disable navigation buttons during loading
    $('#prevDateBtn, #nextDateBtn, #todayBtn').prop('disabled', true);
    
    $.ajax({
        url: '{{ route('dashboard.appointments') }}',
        method: 'POST',
        data: {
            date: date,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('AJAX success, response:', response);
            
            // Update the appointments container
            $('#appointmentsContainer').html(response.html);
            
            // Update the date display
            $('#dateDisplay').text(response.formattedDate + ' Appointments');
            
            // Update current date
            currentDate = response.date;
            
            // Update navigation buttons
            updateNavigationButtons(response.date);
            
            // Update Today button visibility
            if (response.isToday) {
                $('#todayBtn').hide();
            } else {
                $('#todayBtn').show();
            }
            
            // Update URL without page reload
            const url = new URL(window.location);
            url.searchParams.set('date', response.date);
            window.history.pushState({}, '', url);
            
            // Show success notification
            // showNotification('Appointments updated successfully', 'success');
        },
        error: function(xhr, status, error) {
            console.error('Error loading appointments:', error);
            console.error('Response:', xhr.responseText);
            showErrorState();
        },
        complete: function() {
            // Re-enable navigation buttons
            $('#prevDateBtn, #nextDateBtn, #todayBtn').prop('disabled', false);
        }
    });
}

// Function to refresh appointments without showing loading state
function refreshAppointments(date) {
    console.log('Refreshing appointments for date:', date);
    
    $.ajax({
        url: '{{ route('dashboard.appointments') }}',
        method: 'POST',
        data: {
            date: date,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            // Only update if the content has changed
            const currentContent = $('#appointmentsContainer').html();
            if (currentContent !== response.html) {
                $('#appointmentsContainer').html(response.html);
                console.log('Appointments refreshed automatically');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error refreshing appointments:', error);
        }
    });
}

// Function to show loading state
function showLoadingState() {
    $('#appointmentsContainer').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading appointments...</p>
        </div>
    `);
}

// Function to show error state
function showErrorState() {
    $('#appointmentsContainer').html(`
        <div class="text-center py-4">
            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
            <h5 class="text-danger">Error loading appointments</h5>
            <p class="text-muted">Please try again.</p>
            <button class="btn btn-primary" onclick="navigateDate(currentDate)">
                <i class="fas fa-redo me-2"></i>Retry
            </button>
        </div>
    `);
}

// Function to show notification
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = $(`
        <div class="alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);
    
    // Add to body
    $('body').append(notification);
    
    // Auto-remove after 3 seconds
    setTimeout(function() {
        notification.fadeOut(function() {
            $(this).remove();
        });
    }, 3000);
}

// Function to navigate to today
function navigateToToday() {
    const today = new Date().toISOString().split('T')[0];
    navigateDate(today);
}

// Function to update navigation buttons with new dates
function updateNavigationButtons(date) {
    console.log('updateNavigationButtons called with date:', date);
    
    const currentDate = new Date(date);
    const prevDate = new Date(currentDate);
    prevDate.setDate(currentDate.getDate() - 1);
    const nextDate = new Date(currentDate);
    nextDate.setDate(currentDate.getDate() + 1);
    
    const prevDateStr = prevDate.toISOString().split('T')[0];
    const nextDateStr = nextDate.toISOString().split('T')[0];
    
    console.log('Setting prev button date to:', prevDateStr);
    console.log('Setting next button date to:', nextDateStr);
    
    // Update data attributes instead of onclick
    $('#prevDateBtn').data('date', prevDateStr);
    $('#nextDateBtn').data('date', nextDateStr);
}
</script>
@endpush
