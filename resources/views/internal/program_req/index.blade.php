@extends('layouts.main')
@section('css')
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/vendor/libs/@form-validation/form-validation.css') }}" />
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
                    <small class="mb-0">Total pelanggan</small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-primary">
                      <i class="ti ti-pelanggans ti-26px"></i>
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
                    <span class="text-heading">Paid pelanggan</span>
                    <div class="d-flex align-items-center my-1">
                      <h4 class="mb-0 me-2">4,567</h4>
                      <p class="text-success mb-0">(+18%)</p>
                    </div>
                    <small class="mb-0">Last week analytics </small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-danger">
                      <i class="ti ti-pelanggan-plus ti-26px"></i>
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
                    <span class="text-heading">Active pelanggan</span>
                    <div class="d-flex align-items-center my-1">
                      <h4 class="mb-0 me-2"></h4>
                      <p class="text-danger mb-0">(-14%)</p>
                    </div>
                    <small class="mb-0">Last week analytics</small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-success">
                      <i class="ti ti-pelanggan-check ti-26px"></i>
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
                    <span class="text-heading">Pending pelanggan</span>
                    <div class="d-flex align-items-center my-1">
                      <h4 class="mb-0 me-2">237</h4>
                      <p class="text-success mb-0">(+42%)</p>
                    </div>
                    <small class="mb-0">Last week analytics</small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-warning">
                      <i class="ti ti-pelanggan-search ti-26px"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- pelanggans List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                <div class="col-md-4 pelanggan_role"></div>
                <div class="col-md-4 pelanggan_plan"></div>
                <div class="col-md-4 pelanggan_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table id="TableProgramReq" class="datatables-program-req table">
                <thead class="border-top">
                    <tr>
                    <th>#</th>
                    <th>Program</th>
                    <th>Field</th>
                    <th>Operator</th>
                    <th>Value</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                </table>
            </div>
            <div class="modal fade text-start" id="tambahModal" tabindex="-1" aria-labelledby="myModalLabel18" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modal-judul">Tambah Program Requirement</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="formProgram" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="id" id="id">
                                <ul id="save_errorList"></ul>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="nav-align-top nav-tabs mb-6">
                                            <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <button
                                                type="button"
                                                class="nav-link active"
                                                role="tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#navs-top-home"
                                                aria-controls="navs-top-home"
                                                aria-selected="true">
                                                Informasi Program Requirement   
                                                </button>
                                            </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="jenis_program">Program</label>
                                                            <select id="program_id" class="select2 form-select" name="program_id">
                                                                <option selected disabled>Pilih Program</option>
                                                                @foreach ($program as $prg)
                                                                    <option value="{{ $prg->id }}">{{ $prg->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pelanggan-company">Field</label>
                                                            <input
                                                                type="text"
                                                                id="field"
                                                                class="form-control"
                                                                placeholder="Field"
                                                                aria-label="Field"
                                                                name="field"
                                                            />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pelanggan-company">Operator</label>
                                                            <input
                                                                type="text"
                                                                id="operator"
                                                                class="form-control"
                                                                placeholder="Operator"
                                                                aria-label="Operator"
                                                                name="operator"
                                                            />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pelanggan-company">Value</label>
                                                            <input
                                                                type="text"
                                                                id="value"
                                                                class="form-control"
                                                                placeholder="Value"
                                                                aria-label="Value"
                                                                name="value"
                                                            />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-block" id="btn-simpan" value="create">Simpan
                                </button>
                            </div>
                        </form>
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
<script src="{{ asset('assets/ajax/program_req.js') }}"></script>
@endsection
