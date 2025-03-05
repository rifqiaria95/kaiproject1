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
                    <small class="mb-0">Total Pegawai</small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-primary">
                      <i class="ti ti-pegawais ti-26px"></i>
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
                    <span class="text-heading">Paid Pegawai</span>
                    <div class="d-flex align-items-center my-1">
                      <h4 class="mb-0 me-2">4,567</h4>
                      <p class="text-success mb-0">(+18%)</p>
                    </div>
                    <small class="mb-0">Last week analytics </small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-danger">
                      <i class="ti ti-pegawai-plus ti-26px"></i>
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
                    <span class="text-heading">Active Pegawai</span>
                    <div class="d-flex align-items-center my-1">
                      <h4 class="mb-0 me-2"></h4>
                      <p class="text-danger mb-0">(-14%)</p>
                    </div>
                    <small class="mb-0">Last week analytics</small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-success">
                      <i class="ti ti-pegawai-check ti-26px"></i>
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
                    <span class="text-heading">Pending Pegawai</span>
                    <div class="d-flex align-items-center my-1">
                      <h4 class="mb-0 me-2">237</h4>
                      <p class="text-success mb-0">(+42%)</p>
                    </div>
                    <small class="mb-0">Last week analytics</small>
                  </div>
                  <div class="avatar">
                    <span class="avatar-initial rounded bg-label-warning">
                      <i class="ti ti-pegawai-search ti-26px"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Pegawais List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Filters</h5>
                <div class="d-flex justify-content-between align-items-center row pt-4 gap-4 gap-md-0">
                <div class="col-md-4 pegawai_role"></div>
                <div class="col-md-4 pegawai_plan"></div>
                <div class="col-md-4 pegawai_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table id="TableItem" class="datatables-pegawai table">
                <thead class="border-top">
                    <tr>
                    <th>ID</th>
                    <th>Kode Barcode</th>
                    <th>Kode Item</th>
                    <th>Nama Item</th>
                    <th>Jenis Item</th>
                    <th>Stok</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                </table>
            </div>
            <div class="modal fade text-start" id="tambahModal" tabindex="-1" aria-labelledby="myModalLabel18" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modal-judul">Tambah Item</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="formItem" class="form-horizontal" enctype="multipart/form-data">
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
                                                Informasi Item
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button
                                                type="button"
                                                class="nav-link"
                                                role="tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#navs-top-profile"
                                                aria-controls="navs-top-profile"
                                                aria-selected="false">
                                                Vendor
                                                </button>
                                            </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pegawai-fullname">Nama Item</label>
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="nm_item"
                                                                placeholder="Oli Shell HELIX AX7"
                                                                name="nm_item"
                                                                aria-label="Oli Shell HELIX AX7" />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pegawai-email">Merk</label>
                                                            <input
                                                                type="text"
                                                                id="merk"
                                                                class="form-control"
                                                                placeholder="Shell"
                                                                aria-label="Shell"
                                                                name="merk" />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <fieldset class="form-group">
                                                                <label class="form-label">Jenis Item</label>
                                                                <select class="select form-select" name="jenis_item" id="jenis_item" required>
                                                                    <option>Pilih Jenis Item</option>
                                                                    <option value="1">Sparepart</option>
                                                                    <option value="2">Tools</option>
                                                                    <option value="3">Lainnya</option>
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pegawai-fullname">Tanggal Masuk</label>
                                                            <input
                                                                type="date"
                                                                class="form-control"
                                                                id="tgl_masuk_item"
                                                                name="tgl_masuk_item"
                                                            />
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="add-pegawai-email">Stok</label>
                                                            <input
                                                                type="number"
                                                                id="stok"
                                                                class="form-control"
                                                                placeholder="5000"
                                                                aria-label="stok"
                                                                name="stok"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="id_satuan">Satuan</label>
                                                            <select id="id_satuan" class="select2 form-select" name="id_satuan">
                                                                <option selected disabled>Pilih Satuan</option>
                                                                @foreach ($satuan as $st)
                                                                    <option value="{{ $st->id }}">{{ $st->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="rak">Rak</label>
                                                            <input
                                                                type="number"
                                                                id="rak"
                                                                class="form-control"
                                                                placeholder="3"
                                                                aria-label="jdoe1"
                                                                name="rak"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="foto-item">Foto Item</label>
                                                            <input
                                                                type="file"
                                                                id="foto_item"
                                                                class="form-control"
                                                                name="foto_item"
                                                            />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="harga-beli">Harga Beli</label>
                                                            <input
                                                                type="text"
                                                                id="hrg_beli"
                                                                class="form-control"
                                                                placeholder="Rp 500"
                                                                aria-label="hrg_beli"
                                                                name="hrg_beli" />
                                                        </div>
                                                        <div class="col-xl-6 mb-6">
                                                            <label class="form-label" for="harga-jual">Harga Jual</label>
                                                            <input
                                                                type="text"
                                                                id="hrg_jual"
                                                                class="form-control"
                                                                placeholder="Rp 800"
                                                                aria-label="hrg_jual"
                                                                name="hrg_jual"
                                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-xl-12 mb-6">
                                                            <label class="form-label" for="id_vendor">Vendor</label>
                                                            <select id="id_vendor" class="select2 form-select" name="id_vendor">
                                                                <option selected disabled>Pilih Vendor</option>
                                                                @foreach ($vendor as $vd)
                                                                    <option value="{{ $vd->id }}">{{ $vd->nm_vendor }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-xl-12 mb-6">
                                                            <label class="form-label" for="add-pegawai-company">Deskripsi</label>
                                                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
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
<script src="{{ asset('assets/ajax/item.js') }}"></script>
@endsection
