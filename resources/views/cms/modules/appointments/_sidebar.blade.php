<style>
    #appointmentSidebar {
        width: 540px !important;
        max-width: 100vw;
    }
    .sidebar-statusbar {
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        padding: 1.2rem 1.7rem 1.2rem 1.7rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        color: #3a3a3a;
        font-weight: 600;
        font-size: 1.15rem;
        background: #fff;
        border-bottom: 1.5px solid #e0e4ea;
    }
    .sidebar-statusbar.bg-primary, .sidebar-statusbar.bg-info, .sidebar-statusbar.bg-warning, .sidebar-statusbar.bg-success, .sidebar-statusbar.bg-danger {
        color: #fff;
    }
    .sidebar-booking-id {
        font-size: 1rem;
        font-weight: 400;
        opacity: 0.7;
        margin-left: auto;
    }
    .sidebar-content {
        padding: 0 1.5rem 1.5rem 1.5rem;
    }
    @media (max-width: 600px) {
        .sidebar-content {
            padding: 0 0.5rem 1rem 0.5rem;
        }
    }
    .sidebar-card {
        padding: 0;
        margin: 0;
    }
    .sidebar-section-label {
        font-size: 0.97rem;
        font-weight: 700;
        color: #6c7a89;
        letter-spacing: 0.02em;
        margin-bottom: 0.6rem;
        margin-top: 1.2rem;
        text-transform: uppercase;
    }
    .client-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e0e4ea;
        box-shadow: 0 1px 4px rgba(60,72,88,0.04);
        padding: 1.1rem 1.1rem 1.1rem 1.1rem;
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.3rem;
        gap: 0.5rem;
    }
    .client-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #e6eaf2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        font-weight: 700;
        color: #4a5a6a;
        box-shadow: 0 1px 4px rgba(60,72,88,0.06);
        margin-right: 0.6rem;
    }
    .client-main {
        flex: 1;
    }
    .client-main .client-name {
        font-size: 1.09rem;
        font-weight: 700;
        color: #3a3a3a;
        margin-bottom: 0.1rem;
    }
    .client-frequency {
        background: #f3f6fa;
        color: #4a5a6a;
        border-radius: 8px;
        font-size: 0.82rem;
        padding: 0.18em 0.6em;
        margin-left: 0.5em;
        font-weight: 500;
    }
    .client-address {
        color: #7b8a99;
        font-size: 0.95rem;
        margin-top: 0.3rem;
        display: flex;
        align-items: center;
        gap: 0.4em;
    }
    .appointment-details-section {
        font-size: 1.08rem;
        background: none;
        border-radius: 9px;
        padding: 0 0 0 0.1rem;
        margin-bottom: 1.2rem;
        color: #3a3a3a;
        box-shadow: none;
        display: flex;
        flex-direction: column;
        gap: 0.1em;
    }
    .appointment-details-section .icon {
        color: #7b8a99;
        font-size: 1.05em;
        margin-right: 0.5em;
    }
    .sidebar-notes {
        background: #fffef6;
        border-radius: 9px;
        padding: 1rem 1.1rem 1rem 1.1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #f7e9b7;
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        box-shadow: 0 1px 4px rgba(255, 193, 7, 0.04);
    }
    .sidebar-notes-icon {
        color: #bfa100;
        font-size: 1.15rem;
        margin-top: 0.1rem;
    }
    .services-list {
        margin-bottom: 1.2rem;
    }
    .service-list-item {
        background: #fff;
        border-radius: 7px;
        border: 1px solid #e0e4ea;
        box-shadow: 0 1px 2px rgba(60,72,88,0.03);
        padding: 0.7rem 1.1rem;
        margin-bottom: 0.7rem;
        font-size: 1.04rem;
        color: #3a3a3a;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.7em;
    }
    .service-list-item i {
        color: #7b8a99;
        font-size: 1.1em;
    }
    .sidebar-total-row {
        border-top: 1px solid #e0e4ea;
        margin-top: 1.2rem;
        padding-top: 0.8rem;
        font-size: 1.13rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #3a3a3a;
    }
    .sidebar-actions {
        display: flex;
        gap: 1.1rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }
    .sidebar-actions .btn {
        font-size: 1.04rem;
        padding: 0.6em 1.7em;
        border-radius: 2em;
        font-weight: 600;
        box-shadow: 0 1px 4px rgba(60,72,88,0.04);
    }
    @media (max-width: 600px) {
        #appointmentSidebar {
            width: 100vw !important;
            padding: 0;
        }
        .sidebar-card {
            padding: 0;
            border-radius: 0;
        }
        .sidebar-statusbar {
            border-radius: 0;
            padding: 1rem 1rem 1rem 1rem;
            font-size: 1.01rem;
        }
        .sidebar-actions {
            flex-direction: column;
            gap: 0.7rem;
        }
        .sidebar-actions .btn {
            width: 100%;
        }
    }
    .appointment-details-row {
        display: flex;
        align-items: center;
        gap: 0.5em;
        font-size: 1.08rem;
        margin-bottom: 0.5em;
        color: #3a3a3a;
    }
    .appointment-details-row .icon {
        color: #7b8a99;
        font-size: 1.08em;
        margin-right: 0.4em;
    }
    .pet-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e0e4ea;
        box-shadow: 0 1px 4px rgba(60,72,88,0.04);
        padding: 1.1rem 1.1rem 1.1rem 1.1rem;
        display: flex;
        align-items: center;
        margin-bottom: 1.3rem;
        gap: 0.5rem;
    }
    .pet-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #4a5a6a;
        box-shadow: 0 1px 4px rgba(60,72,88,0.06);
        margin-right: 0.6rem;
    }
    .pet-main {
        flex: 1;
    }
    .pet-name {
        font-size: 1.09rem;
        font-weight: 700;
        color: #3a3a3a;
        margin-bottom: 0.1rem;
    }
    .pet-meta {
        color: #7b8a99;
        font-size: 0.95rem;
    }
</style>
@php
    $statusMap = [
        'scheduled' => ['Scheduled', 'fa-calendar', 'bg-primary'],
        'confirmed' => ['Confirmed', 'fa-check-circle', 'bg-info'],
        'in_progress' => ['In Progress', 'fa-spinner', 'bg-warning'],
        'completed' => ['Completed', 'fa-check-double', 'bg-success'],
        'cancelled' => ['Cancelled', 'fa-times-circle', 'bg-danger'],
    ];
    $status = $statusMap[$appointment->status] ?? ['Unknown', 'fa-question-circle', 'bg-primary'];
    $services = collect(json_decode($appointment->services_data ?? '[]', true) ?: []);
    $uniqueServices = $services->unique(function($item) {
        return $item['name'];
    });
    $client = $appointment->client;
    $dog = $appointment->dog;
    $frequency = $client->visit_frequency ?? '2 Weeks';
@endphp
<div class="sidebar-statusbar">
    <i class="fas {{ $status[1] }} me-2"></i>{{ $status[0] }}
    <span class="sidebar-booking-id">Booking #{{ $appointment->id }}</span>
</div>
<div class="offcanvas-body d-flex justify-content-center align-items-start" style="background: #f7f9fb; min-height: 100vh; overflow-y: auto;">
    <div class="sidebar-content w-100">
        <div class="sidebar-section-label">Client</div>
        <div class="client-card mb-3">
            <div class="client-avatar">
                {{ strtoupper(substr($client->first_name,0,1)) }}
            </div>
            <div class="client-main">
                <div class="client-name">{{ $client->first_name }} {{ $client->last_name }}</div>
                <div class="text-muted small mb-1"><i class="fas fa-phone me-1"></i>{{ $client->phone }}</div>
            </div>
        </div>
        <div class="sidebar-section-label">Appointment</div>
        <div class="appointment-details-section mb-3">
            <div class="appointment-details-row">
                <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d/m/Y') }}</span>
                <span class="mx-2">|</span>
                <span>{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i a') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i a') }}</span>
            </div>
            <div class="appointment-details-row">
                <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                <span>{{ $client->full_address ?? 'No address' }}</span>
            </div>
            <div class="appointment-details-row">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span>Groomer: <span class="fw-bold">Ashish</span></span>
            </div>
        </div>
        <!-- Pet Card -->
        <div class="pet-card mb-3">
            <div class="pet-avatar">
                <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="Pet Avatar" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; background: #fff;" onerror="this.style.display='none'; this.parentNode.innerHTML='<i class=\'fas fa-dog\'></i>';" />
            </div>
            <div class="pet-main">
                <div class="pet-name">{{ $dog->name }}</div>
                <div class="pet-meta text-muted small">{{ $dog->breed ?? 'Unknown breed' }} &bull; {{ ucfirst($dog->size) }}</div>
            </div>
        </div>
        <div class="sidebar-section-label">Notes</div>
        <div class="sidebar-notes mb-3">
            <span class="sidebar-notes-icon"><i class="fas fa-exclamation-circle"></i></span>
            <div>
                @if($appointment->notes)
                    <span class="text-dark">{{ $appointment->notes }}</span>
                @else
                    <span class="text-muted">No special instructions for this visit.</span>
                @endif
            </div>
        </div>
        <div class="sidebar-section-label">Services</div>
        <div class="services-list">
        @if($uniqueServices->count())
            @foreach($uniqueServices as $service)
                <div class="service-list-item">
                    <i class="fas fa-scissors"></i> {{ $service['name'] ?? 'Service' }}
                </div>
            @endforeach
        @else
            <div class="text-muted mb-3">No services selected for this appointment.</div>
        @endif
        </div>
        <div class="sidebar-total-row">
            <span>Estimated total</span>
            <span class="text-success fs-4">${{ number_format($appointment->total_price, 2) }}</span>
        </div>
    </div>
</div>