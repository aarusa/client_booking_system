{{-- File: resources/views/cms/modules/services/index.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Service Management')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Service Management</h3>
                <h6 class="op-7 mb-2">Overview of all available services in the system</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('add service')
            <a href="{{ route('services.create') }}" class="btn btn-primary btn-round">Add New Service</a>
            @endcan
            </div>
        </div>
        {{-- Service contents --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">All Services</div>
                  </div>
                  <div class="card-body">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Description</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Status</th>
                            <th scope="col">Pricing</th>
                            <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                
                        @if($services->count() > 0)
                          @foreach($services as $index => $service)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $service->name }}</td>
                                <td>{{ Str::limit($service->description, 50) }}</td>
                                <td>{{ $service->duration }} minutes</td>
                                <td>
                                    @if($service->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($service->prices as $price)
                                        <small class="d-block">
                                            {{ ucfirst($price->dog_size) }}: ${{ number_format($price->price, 2) }}
                                        </small>
                                    @endforeach
                                </td>
                                <td>
                                  @can('edit service')
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                  @endcan
                                  @can('delete service')
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline-block" class="delete-service-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-service-btn">
                                            Delete
                                        </button>
                                    </form>
                                  @endcan
                                </td>
                            </tr>
                          @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No services found.</td>
                            </tr>
                        @endif
                      </tbody>
                    </table>
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

      // SweetAlert confirmation for delete service
      $(document).on('click', '.delete-service-btn', function(e) {
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
  </script>
@endpush 