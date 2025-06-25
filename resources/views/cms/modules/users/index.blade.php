{{-- File: resources/views/cms/dashboard.blade.php --}}
@extends('cms.layouts.master')

@section('title', 'User Management')

@section('content')

@php
    $loggedInUser = auth()->user();
@endphp
    
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">User Management</h3>
                <h6 class="op-7 mb-2">Overview of all registered users in the system</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
            @can('view roles')
            <a href="{{ route('roles.index') }}" class="btn btn-label-info btn-round me-2">Manage Roles</a>
            @endcan
            @can('view permissions')
            <a href="{{ route('permissions.index') }}" class="btn btn-label-info btn-round me-2">Manage Permissions</a>
            @endcan
            @can('add user')
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-round">Add New User</a>
            @endcan
            </div>
        </div>
        {{-- User contents --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">All Users</div>
                  </div>
                  <div class="card-body">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                
                        @if($users->count() > 0)
                          @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->fname }} {{ $user->mname }} {{ $user->lname }}</td>
                                <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                <td>
                                  @php
                                      $isLoggedInSuper = $loggedInUser->hasAnyRole(['Admin', 'Super Admin']);
                                      $isUserSuperAdmin = $user->hasRole('Super Admin');
                                  @endphp

                                  {{-- Allow actions only if logged-in user is Admin/Super Admin and the listed user is not Super Admin --}}
                                  @if($isLoggedInSuper && !$isUserSuperAdmin)
                                    @can('edit user')
                                      <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endcan
                                    @can('delete user')
                                      <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block" class="delete-user-form">
                                          @csrf
                                          @method('DELETE')
                                          <button type="submit" class="btn btn-danger btn-sm delete-user-btn">
                                              Delete
                                          </button>
                                      </form>
                                    @endcan
                                  @else
                                      <span class="badge bg-secondary">Protected</span>
                                  @endif

                                </td>
                            </tr>
                          @endforeach
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

      // SweetAlert confirmation for delete user
      $(document).on('click', '.delete-user-btn', function(e) {
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
