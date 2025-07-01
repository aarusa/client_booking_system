  {{-- File: resources/views/cms/dashboard.blade.php --}}
  @extends('cms.layouts.master')

  @section('title', 'Role Management')

  @section('content')

  @php
      $loggedInUser = auth()->user();
  @endphp
      
      <div class="page-inner">
          <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
              <div>
                  <h3 class="fw-bold mb-3">Role Management</h3>
                  <h6 class="op-7 mb-2">Overview of all user roles in the system</h6>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                @can('add role')
                  <a href="{{ route('roles.create') }}" class="btn btn-primary btn-round">Add New Role</a>
                @endcan
              </div>
          </div>
          {{-- User contents --}}
          <div class="row">
              <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <div class="card-title">All Roles</div>
                    </div>
                    <div class="card-body">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col">Role</th>
                              <th scope="col">Permissions</th>
                              <th scope="col">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                  
                          @if($roles->count() > 0)
                            @foreach($roles as $index => $role)
                              <tr>
                                  <td>{{ $index + 1 }}</td>
                                  <td>{{ $role->name }}</td>
                                  <td>
                                    @can('manage permission')
                                      <a href="{{ route('permissions.role', $role->id) }}" class="btn btn-info btn-sm">
                                          Manage Permissions 
                                          <span class="badge bg-light text-dark ms-1">{{ $role->permissions->count() }}</span>
                                      </a>
                                    @endcan
                                  </td>
                                  <td>
                                    @if(strtolower($role->name) !== 'super admin')
                                    @can('edit role')
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    @endcan
                                    @can('delete role')
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block" class="delete-role-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm delete-role-btn">
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
      // Session messages are now handled centrally in master layout

      // SweetAlert confirmation for delete role
      $(document).on('click', '.delete-role-btn', function(e) {
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
