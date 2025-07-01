@extends('cms.layouts.master')

@section('title', 'Appointments')

@section('content')
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Appointments</h3>
                <h6 class="op-7 mb-2">Manage all scheduled appointments and bookings.</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                @can('appointment-create')
                <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-round">
                    <i class="fas fa-plus me-2"></i>New Appointment
                </a>
                @endcan
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">

                <!-- Filters and Sort Section -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Search & Filter</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="openFilterSidebar">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
                <!-- Filter Sidebar -->
                <div id="filterSidebar" class="filter-sidebar">
                    <div class="filter-sidebar-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Filters</h5>
                        <button type="button" class="btn-close" aria-label="Close" id="closeFilterSidebar"></button>
                    </div>
                    <form method="GET" action="{{ route('appointments.index') }}" id="filterForm" class="p-3">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        <div class="mb-3">
                            <label for="client_search" class="form-label">Search Client</label>
                            <input type="text" class="form-control" id="client_search" name="client_search" placeholder="Name or email..." value="{{ request('client_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="location_search" class="form-label">Search Location</label>
                            <input type="text" class="form-control" id="location_search" name="location_search" placeholder="City, state, or address..." value="{{ request('location_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date</label>
                            <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="{{ request('appointment_date') }}">
                        </div>
                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date (Newest First)</option>
                                <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date (Oldest First)</option>
                                <option value="client_asc" {{ request('sort') == 'client_asc' ? 'selected' : '' }}>Client Name (A-Z)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2 align-items-center mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                <div id="filterSidebarOverlay" class="filter-sidebar-overlay"></div>

                <!-- Status Tabs -->
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs nav-tabs-primary" id="appointmentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}" 
                                   href="{{ route('appointments.index', array_merge(request()->query(), ['tab' => 'all'])) }}" 
                                   role="tab">
                                    <i class="fas fa-list me-2"></i>All
                                    <span class="badge bg-secondary ms-2">{{ $statusCounts['all'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $activeTab === 'scheduled' ? 'active' : '' }}" 
                                   href="{{ route('appointments.index', array_merge(request()->query(), ['tab' => 'scheduled'])) }}" 
                                   role="tab">
                                    <i class="fas fa-calendar me-2"></i>Scheduled
                                    <span class="badge bg-warning ms-2">{{ $statusCounts['scheduled'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $activeTab === 'confirmed' ? 'active' : '' }}" 
                                   href="{{ route('appointments.index', array_merge(request()->query(), ['tab' => 'confirmed'])) }}" 
                                   role="tab">
                                    <i class="fas fa-check-circle me-2"></i>Confirmed
                                    <span class="badge bg-info ms-2">{{ $statusCounts['confirmed'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $activeTab === 'in_progress' ? 'active' : '' }}" 
                                   href="{{ route('appointments.index', array_merge(request()->query(), ['tab' => 'in_progress'])) }}" 
                                   role="tab">
                                    <i class="fas fa-spinner me-2"></i>In Progress
                                    <span class="badge bg-primary ms-2">{{ $statusCounts['in_progress'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $activeTab === 'completed' ? 'active' : '' }}" 
                                   href="{{ route('appointments.index', array_merge(request()->query(), ['tab' => 'completed'])) }}" 
                                   role="tab">
                                    <i class="fas fa-check-double me-2"></i>Completed
                                    <span class="badge bg-success ms-2">{{ $statusCounts['completed'] }}</span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $activeTab === 'cancelled' ? 'active' : '' }}" 
                                   href="{{ route('appointments.index', array_merge(request()->query(), ['tab' => 'cancelled'])) }}" 
                                   role="tab">
                                    <i class="fas fa-times-circle me-2"></i>Cancelled
                                    <span class="badge bg-danger ms-2">{{ $statusCounts['cancelled'] }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            @switch($activeTab)
                                @case('scheduled')
                                    Scheduled Appointments
                                    @break
                                @case('confirmed')
                                    Confirmed Appointments
                                    @break
                                @case('in_progress')
                                    In Progress Appointments
                                    @break
                                @case('completed')
                                    Completed Appointments
                                    @break
                                @case('cancelled')
                                    Cancelled Appointments
                                    @break
                                @default
                                    All Appointments
                            @endswitch
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Dog</th>
                                    <th scope="col">Date & Time</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Services</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($appointments->count() > 0)
                                    @foreach($appointments as $index => $appointment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ route('clients.show', $appointment->client->id) }}" class="text-decoration-none">
                                                    <strong class="text-primary">{{ $appointment->client->first_name }} {{ $appointment->client->last_name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $appointment->client->email }}</small>
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $appointment->dog->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $appointment->dog->breed ?? 'Unknown breed' }}</small>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - 
                                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $client = $appointment->client;
                                                    $location = [];
                                                    if (!empty($client->address)) $location[] = $client->address;
                                                    if (!empty($client->city)) $location[] = $client->city;
                                                    if (!empty($client->state)) $location[] = $client->state;
                                                    if (!empty($client->zipcode)) $location[] = $client->zipcode;
                                                @endphp
                                                <span class="text-muted small">{{ implode(', ', $location) ?: 'N/A' }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $selectedServices = json_decode($appointment->services_data ?? '[]', true) ?: [];
                                                @endphp
                                                
                                                @foreach($selectedServices as $service)
                                                    @if(isset($service['name']))
                                                        <span class="badge bg-primary me-1">{{ $service['name'] }}</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <strong class="text-dark">${{ number_format($appointment->total_price, 2) }}</strong>
                                            </td>
                                            <td>
                                                @can('appointment-edit')
                                                    <form action="{{ route('appointments.update-status', $appointment->id) }}" method="POST" class="status-update-form" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" class="form-select form-select-sm status-select" 
                                                                data-appointment-id="{{ $appointment->id }}"
                                                                style="min-width: 120px;">
                                                            <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                            <option value="confirmed" {{ $appointment->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                            <option value="in_progress" {{ $appointment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                            <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                            <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        </select>
                                                    </form>
                                                @else
                                                    @switch($appointment->status)
                                                        @case('scheduled')
                                                            <span class="badge bg-warning">Scheduled</span>
                                                            @break
                                                        @case('confirmed')
                                                            <span class="badge bg-info">Confirmed</span>
                                                            @break
                                                        @case('in_progress')
                                                            <span class="badge bg-primary">In Progress</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="badge bg-success">Completed</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-danger">Cancelled</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                                    @endswitch
                                                @endcan
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    @can('appointment-view')
                                                        <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-info btn-sm">
                                                            View
                                                        </a>
                                                    @endcan
                                                    @can('appointment-edit')
                                                        <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    @endcan
                                                    
                                                    @can('appointment-delete')
                                                        @if($appointment->status !== 'completed')
                                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" class="delete-appointment-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm delete-appointment-btn w-100">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                                <p>No appointments found.</p>
                                                @can('appointment-create')
                                                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Create First Appointment
                                                </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        <!-- Pagination and Results Summary -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <!-- Results Summary -->
                            <div class="text-muted">
                                @if($appointments->count() > 0)
                                    Showing {{ $appointments->firstItem() }} to {{ $appointments->lastItem() }} 
                                    of {{ $appointments->total() }} appointment(s)
                                @else
                                    No appointments found
                                @endif
                            </div>
                            
                            <!-- Pagination -->
                            @if($appointments->hasPages())
                                <nav aria-label="Appointments pagination">
                                    {{ $appointments->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@push('scripts')
    <style>
        /* Custom pagination styling */
        .pagination {
            margin-bottom: 0;
        }
        
        .page-link {
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            transition: all 0.15s ease-in-out;
        }
        
        .page-link:hover {
            color: #0056b3;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        .page-item:first-child .page-link {
            margin-left: 0;
            border-top-left-radius: 0.25rem;
            border-bottom-left-radius: 0.25rem;
        }
        
        .page-item:last-child .page-link {
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }
        
        /* Results summary styling */
        .results-summary {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        /* Responsive pagination */
        @media (max-width: 768px) {
            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                gap: 1rem;
            }
            
            .pagination {
                justify-content: center;
            }
        }

        .filter-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 350px;
            height: 100vh;
            background: #fff;
            box-shadow: -2px 0 16px rgba(0,0,0,0.08);
            z-index: 1050;
            transition: right 0.3s cubic-bezier(.4,0,.2,1);
            overflow-y: auto;
            border-left: 1px solid #e9ecef;
        }
        .filter-sidebar.active {
            right: 0;
        }
        .filter-sidebar-header {
            padding: 1rem 1.5rem 0.5rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
        }
        .filter-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.15);
            z-index: 1049;
            display: none;
        }
        .filter-sidebar-overlay.active {
            display: block;
        }
        @media (max-width: 600px) {
            .filter-sidebar {
                width: 100vw;
                right: -100vw;
            }
            .filter-sidebar.active {
                right: 0;
            }
        }

        /* Tab Styling */
        .nav-tabs-primary {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs-primary .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
            font-weight: 500;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-tabs-primary .nav-link:hover {
            border-color: transparent;
            color: #007bff;
            background-color: #f8f9fa;
        }

        .nav-tabs-primary .nav-link.active {
            color: #007bff;
            background-color: #fff;
            border-bottom-color: #007bff;
            font-weight: 600;
        }

        .nav-tabs-primary .nav-link .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Responsive tabs */
        @media (max-width: 768px) {
            .nav-tabs-primary {
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: hidden;
                -webkit-overflow-scrolling: touch;
            }

            .nav-tabs-primary .nav-item {
                flex: 0 0 auto;
                min-width: 120px;
            }

            .nav-tabs-primary .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                white-space: nowrap;
            }

            .nav-tabs-primary .nav-link i {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .nav-tabs-primary .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            .nav-tabs-primary .nav-link .badge {
                font-size: 0.7rem;
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
    
    <script>
        // Session messages are now handled centrally in master layout

        // SweetAlert confirmation for delete appointment
        $(document).on('click', '.delete-appointment-btn', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                buttons: {
                    cancel: {
                        text: "Cancel",
                        value: null,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Yes, delete it!",
                        value: true,
                        visible: true,
                        className: "btn btn-primary",
                        closeModal: true
                    }
                }
            }).then((value) => {
                if (value) {
                    form.submit();
                }
            });
        });

        // Auto-submit status update form when dropdown changes
        $(document).on('change', '.status-select', function() {
            const form = $(this).closest('form');
            const newStatus = $(this).val();
            const appointmentId = $(this).data('appointment-id');
            const originalValue = $(this).find('option[selected]').val() || $(this).val();
            
            // Show loading state
            $(this).prop('disabled', true);
            
            // Create form data manually to ensure status is included
            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('_method', 'PATCH');
            formData.append('status', newStatus);
            
            // Submit the form
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Show success message
                    swal({
                        title: "Status Updated!",
                        text: "Appointment status has been updated successfully.",
                        icon: "success",
                        button: "OK",
                    });
                    
                    // Re-enable the dropdown
                    $('.status-select[data-appointment-id="' + appointmentId + '"]').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', xhr.responseText);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Form Data:', formData);
                    
                    // Revert to original value
                    $('.status-select[data-appointment-id="' + appointmentId + '"]').val(originalValue);
                    
                    // Show error message with more details
                    let errorMessage = "Failed to update appointment status. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMessage = "You don't have permission to update appointment status.";
                    } else if (xhr.status === 404) {
                        errorMessage = "Appointment not found.";
                    } else if (xhr.status === 422) {
                        errorMessage = "Invalid status value.";
                    }
                    
                    swal({
                        title: "Error!",
                        text: errorMessage,
                        icon: "error",
                        button: "OK",
                    });
                    
                    // Re-enable the dropdown
                    $('.status-select[data-appointment-id="' + appointmentId + '"]').prop('disabled', false);
                }
            });
        });

        // Sidebar filter logic
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('filterSidebar');
            var overlay = document.getElementById('filterSidebarOverlay');
            var openBtn = document.getElementById('openFilterSidebar');
            var closeBtn = document.getElementById('closeFilterSidebar');
            if (openBtn && sidebar && overlay && closeBtn) {
                openBtn.addEventListener('click', function() {
                    sidebar.classList.add('active');
                    overlay.classList.add('active');
                });
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>
@endpush 