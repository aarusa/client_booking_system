{{-- File: resources/views/cms/modules/dogs/index.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'Dog Management')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dog Management</h3>
                <h6 class="op-7 mb-2">Overview of all registered dogs in the system</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('add dog')
            <a href="{{ route('dogs.create') }}" class="btn btn-primary btn-round">Add New Dog</a>
            @endcan
            </div>
        </div>
        {{-- Dog contents --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">All Dogs</div>
                  </div>
                  <div class="card-body">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Owner</th>
                            <th scope="col">Breed</th>
                            <th scope="col">Age</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                
                        @if($dogs->count() > 0)
                          @foreach($dogs as $index => $dog)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $dog->name }}</strong></td>
                                <td>{{ $dog->client->full_name ?? 'Unknown' }}</td>
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
                                <td>
                                  @can('view dog')
                                    <a href="{{ route('dogs.show', $dog->id) }}" class="btn btn-info btn-sm">View</a>
                                  @endcan
                                  @can('edit dog')
                                    <a href="{{ route('dogs.edit', $dog->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                  @endcan
                                  @can('delete dog')
                                    <form action="{{ route('dogs.destroy', $dog->id) }}" method="POST" style="display:inline-block" class="delete-dog-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-dog-btn">
                                            Delete
                                        </button>
                                    </form>
                                  @endcan
                                </td>
                            </tr>
                          @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No dogs found.</td>
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

      // SweetAlert confirmation for delete dog
      $(document).on('click', '.delete-dog-btn', function(e) {
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