@extends('layouts.main')
@section('css')
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/@form-validation/form-validation.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection
@section('content')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row g-6 mb-6">
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Session</span>
                            <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">21,459</h4>
                            <p class="text-success mb-0">(+29%)</p>
                            </div>
                            <small class="mb-0">Total Users</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-users ti-26px"></i>
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Paid Users</span>
                            <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">4,567</h4>
                            <p class="text-success mb-0">(+18%)</p>
                            </div>
                            <small class="mb-0">Last week analytics </small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-danger">
                            <i class="ti ti-user-plus ti-26px"></i>
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Active Users</span>
                            <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">{{ totalUser() }}</h4>
                            <p class="text-danger mb-0">(-14%)</p>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                            <i class="ti ti-user-check ti-26px"></i>
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="text-heading">Pending Users</span>
                            <div class="d-flex align-items-center my-1">
                            <h4 class="mb-0 me-2">237</h4>
                            <p class="text-success mb-0">(+42%)</p>
                            </div>
                            <small class="mb-0">Last week analytics</small>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                            <i class="ti ti-user-search ti-26px"></i>
                            </span>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                <div class="col-md-4 user_role"></div>
                <div class="col-md-4 user_plan"></div>
                <div class="col-md-4 user_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table id="TableRole" class="datatables-users table">
                <thead class="border-top">
                    <tr>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                </table>
            </div>
            <!-- Add Role Modal -->
            <div class="modal fade" id="tambahModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-6">
                                <h4 class="role-title mb-2">Add New Role</h4>
                                <p>Set role permissions</p>
                            </div>
                            <!-- Add role form -->
                            <form id="formRole" class="row g-6" onsubmit="return false">
                                <input type="hidden" id="id" name="id">
                                <div class="col-12">
                                <label class="form-label" for="name">Nama Role</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    placeholder="Masukkan nama role"
                                />
                                </div>
                                <div class="col-12">
                                <h5 class="mb-6">Role Permissions</h5>
                                <!-- Permission table -->
                                <div class="table-responsive">
                                    @php
                                        // Mapping nama permission menjadi label singkat
                                        $permissionLabels = [
                                            'view'   => 'View',
                                            'create' => 'Create',
                                            'edit'   => 'Edit',
                                            'delete' => 'Delete'
                                        ];
                                    @endphp

                                    <table class="table table-flush-spacing">
                                        <tbody>
                                            <!-- Header -->
                                            <tr>
                                                <td class="text-nowrap fw-medium text-heading">
                                                    Superadmin Access
                                                    <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                                </td>
                                                <td colspan="3">
                                                    <div class="d-flex justify-content-end">
                                                        <div class="form-check mb-0">
                                                            <input class="form-check-input" type="checkbox" id="selectAll" />
                                                            <label class="form-check-label" for="selectAll"> Select All </label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            @php
                                                $groupedPermissions = $permissions->groupBy(function ($permission) {
                                                    return optional($permission->menuDetails->first())->id . '-' . optional($permission->menuDetails->first())->id;
                                                });
                                            @endphp

                                            @foreach ($groupedPermissions as $key => $permissionGroup)
                                                @php
                                                    $firstPermission = $permissionGroup->first();
                                                    $menuGroup       = $firstPermission->menuDetails->first();
                                                @endphp
                                                <tr>
                                                    <!-- Menampilkan Menu Group -->
                                                    <td class="text-nowrap fw-medium text-heading">
                                                        {{ $menuGroup ? $menuGroup->name : 'Tidak ada menu group' }}
                                                    </td>

                                                    <!-- Menampilkan Permissions dengan Label Singkat -->
                                                    <td class="d-flex gap-3">
                                                        <div class="d-flex justify-content-end">
                                                            @foreach ($permissionGroup as $permission)
                                                                @php
                                                                    // Ambil nama permission (misalnya "view item", "create item")
                                                                    $permissionName = strtolower($permission->name);
                                                                    $label = null;

                                                                    // Cek apakah permission memiliki salah satu dari label singkat
                                                                    foreach ($permissionLabels as $key => $shortLabel) {
                                                                        if (str_contains($permissionName, $key)) {
                                                                            $label = $shortLabel;
                                                                            break;
                                                                        }
                                                                    }

                                                                    // Jika tidak cocok, gunakan nama asli
                                                                    $label = $label ?? ucfirst($permission->name);
                                                                @endphp
                                                                <div class="form-check mb-0 me-4 me-lg-12">
                                                                    <input id="permission-checkbox-{{ $permission->id }}" class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                                                                    <label class="form-check-label" for="permission-checkbox-{{ $permission->id }}">{{ $label }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Permission table -->
                                </div>
                                <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-simpan me-3">Submit</button>
                                <button
                                    type="reset"
                                    class="btn btn-label-secondary"
                                    data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                                </div>
                            </form>
                        <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <!-- / Content -->
@endsection
@section('script')
<script>
    window.userPermissions = @json(auth()->user()->getAllPermissions()->pluck('name'));
</script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/popular.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/auto-focus.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
<script src="{{ asset('assets/ajax/role.js') }}"></script>
@endsection
