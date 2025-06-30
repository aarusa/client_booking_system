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
                  </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    
@endsection

@push('scripts')
  <script>
      // Check for success message
      @if (session('success'))
          swal({
              title: "Success!",
              text: "{{ session('success') }}",
              icon: "success",
              button: "OK",
          });
      @endif

      // Check for error message
      @if (session('error'))
          swal({
              title: "Error!",
              text: "{{ session('error') }}",
              icon: "error",
              button: "OK",
          });
      @endif

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
  </script>
@endpush 