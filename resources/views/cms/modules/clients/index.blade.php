{{-- File: resources/views/cms/modules/clients/index.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Client Management')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Client Management</h3>
                <h6 class="op-7 mb-2">Overview of all registered clients in the system</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('add client')
            <a href="{{ route('clients.create') }}" class="btn btn-primary btn-round">Add New Client</a>
            @endcan
            </div>
        </div>
        {{-- Client contents --}}
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
                    <form method="GET" action="{{ route('clients.index') }}" id="filterForm" class="p-3">
                        <div class="mb-3">
                            <label for="name_search" class="form-label">Search Name</label>
                            <input type="text" class="form-control" id="name_search" name="name_search" placeholder="First name, last name..." value="{{ request('name_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="email_search" class="form-label">Search Email</label>
                            <input type="text" class="form-control" id="email_search" name="email_search" placeholder="Email address..." value="{{ request('email_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="location_search" class="form-label">Search Location</label>
                            <input type="text" class="form-control" id="location_search" name="location_search" placeholder="City, state, or address..." value="{{ request('location_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="dog_name_search" class="form-label">Search Dog Name</label>
                            <input type="text" class="form-control" id="dog_name_search" name="dog_name_search" placeholder="Dog name..." value="{{ request('dog_name_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="dog_breed_search" class="form-label">Search Dog Breed</label>
                            <input type="text" class="form-control" id="dog_breed_search" name="dog_breed_search" placeholder="Dog breed..." value="{{ request('dog_breed_search') }}">
                        </div>
                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_desc" {{ request('sort', 'created_desc') == 'created_desc' ? 'selected' : '' }}>Newest First</option>
                                <option value="created_asc" {{ request('sort') == 'created_asc' ? 'selected' : '' }}>Oldest First</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                <option value="email_asc" {{ request('sort') == 'email_asc' ? 'selected' : '' }}>Email (A-Z)</option>
                                <option value="email_desc" {{ request('sort') == 'email_desc' ? 'selected' : '' }}>Email (Z-A)</option>
                                <option value="dogs_desc" {{ request('sort') == 'dogs_desc' ? 'selected' : '' }}>Most Dogs First</option>
                                <option value="dogs_asc" {{ request('sort') == 'dogs_asc' ? 'selected' : '' }}>Least Dogs First</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2 align-items-center mt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                <div id="filterSidebarOverlay" class="filter-sidebar-overlay"></div>

                <!-- Clients Table -->
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">All Clients</div>
                  </div>
                  <div class="card-body">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Dogs</th>
                            <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                
                        @if($clients->count() > 0)
                          @foreach($clients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $client->full_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $client->dogs->count() }} dog(s)</span>
                                </td>
                                <td>
                                  @can('view client')
                                    <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm">View</a>
                                  @endcan
                                  @can('edit client')
                                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                  @endcan
                                  @can('delete client')
                                    <form action="{{ route('clients.destroy', $client->id) }}" method="POST" style="display:inline-block" class="delete-client-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-client-btn">
                                            Delete
                                        </button>
                                    </form>
                                  @endcan
                                </td>
                            </tr>
                          @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center">No clients found.</td>
                            </tr>
                        @endif
                      </tbody>
                    </table>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} clients
                        </div>
                        <div>
                            {{ $clients->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    
@endsection

@push('scripts')
  <script>
      // Session messages are now handled centrally in master layout

      // SweetAlert confirmation for delete client
      $(document).on('click', '.delete-client-btn', function(e) {
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

<style>
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
</style> 