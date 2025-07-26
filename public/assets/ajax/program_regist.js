$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Deteksi apakah ini halaman external atau internal
    const isExternalPage = window.location.pathname.includes('registration-program') || 
                          window.location.pathname.includes('external/program-regist');
    
    if (isExternalPage) {
        // Kode untuk halaman external
        initializeExternalPage();
    } else {
        // Kode untuk halaman internal (existing code)
        initializeInternalPage();
    }
});

// Function untuk inisialisasi halaman external
function initializeExternalPage() {
    console.log('Initializing external program registration page');
    
    // Initialize select2 untuk external page
    $('.select2').select2();
    
    // Konfigurasi toastr untuk external page
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    
    // Tampilkan notifikasi welcome setelah halaman dimuat
    setTimeout(function() {
        toastr.info('Selamat datang! Silakan pilih program dan isi alasan pendaftaran Anda.', 'Selamat Datang');
    }, 1000);
    
    // Handle form submission untuk external
    $('#formProgramRegist').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);
        let url = "/registration-program/store";

        // Debug: Log form data
        console.log('Form Data:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Validasi form
        let programId = $('#program_id').val();
        let alasan = $('#alasan').val();
        
        if (!programId || programId === 'Pilih Program') {
            toastr.error('Silakan pilih program terlebih dahulu', 'Validasi Gagal');
            $('#program_id').focus();
            // Tambahkan animasi shake pada select
            $('#program_id').addClass('animate__animated animate__shakeX');
            setTimeout(function() {
                $('#program_id').removeClass('animate__animated animate__shakeX');
            }, 1000);
            return;
        }
        
        if (!alasan || alasan.trim() === '') {
            toastr.error('Silakan isi alasan pendaftaran', 'Validasi Gagal');
            $('#alasan').focus();
            // Tambahkan animasi shake pada input
            $('#alasan').addClass('animate__animated animate__shakeX');
            setTimeout(function() {
                $('#alasan').removeClass('animate__animated animate__shakeX');
            }, 1000);
            return;
        }
        
        // Validasi panjang alasan
        if (alasan.trim().length < 10) {
            toastr.warning('Alasan pendaftaran terlalu pendek. Silakan isi minimal 10 karakter.', 'Validasi Gagal');
            $('#alasan').focus();
            // Tambahkan animasi shake pada input
            $('#alasan').addClass('animate__animated animate__shakeX');
            setTimeout(function() {
                $('#alasan').removeClass('animate__animated animate__shakeX');
            }, 1000);
            return;
        }
        
        // Validasi panjang maksimal alasan
        if (alasan.trim().length > 255) {
            toastr.warning('Alasan pendaftaran terlalu panjang. Maksimal 255 karakter.', 'Validasi Gagal');
            $('#alasan').focus();
            // Tambahkan animasi shake pada input
            $('#alasan').addClass('animate__animated animate__shakeX');
            setTimeout(function() {
                $('#alasan').removeClass('animate__animated animate__shakeX');
            }, 1000);
            return;
        }
        
        // Tampilkan notifikasi konfirmasi sebelum submit
        let selectedProgramName = $('#program_id option:selected').text();
        toastr.info('Mengirim pendaftaran untuk program "' + selectedProgramName + '"...', 'Memproses');

        // Disable button dan tampilkan loading
        $('#btn-simpan').prop('disabled', true).text('Mendaftar...').addClass('btn-loading');
        
        // Tampilkan notifikasi loading
        let loadingToast = toastr.info('Sedang memproses pendaftaran Anda...', 'Mohon Tunggu');
        loadingToast.css('opacity', '0.8');

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            timeout: 30000, // 30 detik timeout
            success: function(response){
                console.log('Success Response:', response);
                $('#btn-simpan').prop('disabled', false).text('Daftar').removeClass('btn-loading');
                
                // Tutup notifikasi loading
                loadingToast.remove();

                if(response.status == 200){
                    // Ambil nama program yang dipilih
                    let selectedProgramName = $('#program_id option:selected').text();
                    
                    toastr.success('Pendaftaran program "' + selectedProgramName + '" berhasil! Kami akan menghubungi Anda segera.', 'Pendaftaran Berhasil');
                    
                    // Reset form setelah berhasil
                    $('#formProgramRegist')[0].reset();
                    $('#program_id').val('').trigger('change');
                    $('#btn-simpan').removeClass('btn-success').addClass('btn-primary');
                    
                    // Tampilkan pesan sukses yang lebih detail
                    setTimeout(function() {
                        toastr.info('Terima kasih telah mendaftar. Tim kami akan memproses pendaftaran Anda dalam waktu 1-2 hari kerja.', 'Informasi');
                    }, 2000);
                    
                    // Tampilkan notifikasi tambahan setelah 5 detik
                    setTimeout(function() {
                        toastr.success('Anda akan menerima email konfirmasi pendaftaran dalam beberapa menit.', 'Email Konfirmasi');
                    }, 5000);
                    
                } else {
                    toastr.error('Terjadi kesalahan saat mendaftar. Silakan coba lagi.', 'Gagal Mendaftar');
                }
            },
            error: function(xhr, status, error){
                console.log('Error Response:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                
                $('#btn-simpan').prop('disabled', false).text('Daftar').removeClass('btn-loading');
                
                // Tutup notifikasi loading
                loadingToast.remove();

                if(status === 'timeout') {
                    toastr.error('Koneksi timeout. Silakan cek koneksi internet Anda dan coba lagi.', 'Timeout');
                } else if(status === 'error' && xhr.status === 0) {
                    toastr.error('Tidak dapat terhubung ke server. Silakan cek koneksi internet Anda.', 'Koneksi Gagal');
                } else if(xhr.status === 401) {
                    // User belum login
                    toastr.error('Anda harus login terlebih dahulu untuk mendaftar program', 'Akses Ditolak');
                    // Redirect ke halaman login setelah 3 detik
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 3000);
                } else if(xhr.status === 400) {
                    let response = xhr.responseJSON;
                    
                    if (response.message) {
                        if (response.message.includes('sudah mendaftar')) {
                            toastr.warning('Anda sudah mendaftar pada program ini sebelumnya', 'Pendaftaran Gagal');
                        } else if (response.message.includes('kuota sudah habis')) {
                            toastr.warning('Mohon maaf, kuota program sudah penuh', 'Pendaftaran Gagal');
                        } else {
                            toastr.error(response.message, 'Pendaftaran Gagal');
                        }
                    } else {
                        toastr.error('Terjadi kesalahan pada data yang dimasukkan', 'Validasi Gagal');
                    }
                } else if(xhr.status === 404) {
                    toastr.error('Program yang dipilih tidak ditemukan', 'Program Tidak Ditemukan');
                } else if(xhr.status === 422) {
                    // Validation error
                    let response = xhr.responseJSON;
                    if (response.errors) {
                        let errorMessages = [];
                        $.each(response.errors, function(key, value) {
                            errorMessages.push(value[0]);
                        });
                        toastr.error(errorMessages.join('<br>'), 'Validasi Gagal');
                    } else {
                        toastr.error('Data yang dimasukkan tidak valid', 'Validasi Gagal');
                    }
                } else if(xhr.status === 500) {
                    toastr.error('Terjadi kesalahan pada server. Silakan coba lagi nanti.', 'Error Server');
                } else {
                    toastr.error('Terjadi kesalahan yang tidak diketahui. Silakan coba lagi.', 'Error Tidak Diketahui');
                }
            }
        });
    });
    
    // Event listener untuk memberikan feedback saat user memilih program
    $('#program_id').on('change', function() {
        let selectedProgram = $(this).find('option:selected').text();
        if (selectedProgram !== 'Pilih Program') {
            toastr.success('Program "' + selectedProgram + '" telah dipilih', 'Program Dipilih');
        }
    });
    
    // Event listener untuk memberikan feedback saat user mengetik alasan
    $('#alasan').on('input', function() {
        let alasanLength = $(this).val().length;
        if (alasanLength > 0 && alasanLength <= 50) {
            // Hapus notifikasi sebelumnya jika ada
            $('.toast-info').remove();
            toastr.info('Alasan pendaftaran sedang diketik...', 'Mengetik');
        }
    });
    
    // Event listener untuk memberikan feedback saat user selesai mengetik alasan
    $('#alasan').on('blur', function() {
        let alasan = $(this).val().trim();
        if (alasan.length > 0) {
            toastr.success('Alasan pendaftaran telah diisi', 'Alasan Terisi');
        }
    });
    
    // Event listener untuk mengecek apakah form sudah lengkap
    function checkFormComplete() {
        let programId = $('#program_id').val();
        let alasan = $('#alasan').val().trim();
        
        if (programId && programId !== 'Pilih Program' && alasan.length >= 10) {
            // Form sudah lengkap, tampilkan notifikasi
            toastr.success('Form pendaftaran sudah lengkap! Anda dapat menekan tombol "Daftar" untuk mendaftar.', 'Form Siap');
            $('#btn-simpan').removeClass('btn-secondary').addClass('btn-success');
        } else {
            $('#btn-simpan').removeClass('btn-success').addClass('btn-primary');
        }
    }
    
    // Event listener untuk mengecek form setiap kali ada perubahan
    $('#program_id, #alasan').on('change keyup', function() {
        checkFormComplete();
    });
}

// Function untuk inisialisasi halaman internal
function initializeInternalPage() {
    console.log('Initializing internal program registration page');
    
    // Pastikan loading dan error hidden saat halaman load
    $('#modal-loading').hide();
    $('#modal-error').hide();
    
    // Reset modal state saat halaman dimuat
    resetModalState();
    
    // Tambahan: Reset modal saat halaman selesai dimuat
    $(window).on('load', function() {
        resetModalState();
    });
}

// Function untuk reset state modal (tanpa reset form)
function resetModalState() {
    $('#modal-loading').hide();
    $('#modal-error').hide();
    $('#btn-simpan').prop('disabled', false).text('Simpan');
    $('#formProgramRegist input, #formProgramRegist select, #formProgramRegist textarea').prop('disabled', false);
    $('.text-danger').remove();
    $('.is-invalid').removeClass('is-invalid');
}

// Handler: Reset error & loader saat modal akan dibuka
$(document).on('show.bs.modal', '#tambahModal', function () {
    resetModalState();
    // Jika mode tambah, reset form
    if ($('#id').val() == '' || $('#id').val() == '0') {
        $('#formProgramRegist')[0].reset();
    }
});

// Handler: Reset error & loader saat modal ditutup
$(document).on('hidden.bs.modal', '#tambahModal', function () {
    resetModalState();
});

if ($.fn.DataTable.isDataTable('#TableProgramRegist')) {
    $('#TableProgramRegist').DataTable().destroy();
}

$('#TableProgramRegist').DataTable({
    dom:
        '<"row me-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
    language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search..'
    },
    buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-secondary dropdown-toggle mx-4 waves-effect waves-light',
          text: '<i class="ti ti-upload me-2 ti-xs"></i>Export',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-2" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be print
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('pelanggan-nm_pelanggan')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              },
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', headingColor)
                  .css('border-color', borderColor)
                  .css('background-color', bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-2" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('program-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('program-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('program-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-2" ></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [1, 2, 3, 4, 5],
                // prevent avatar to be display
                format: {
                  body: function (inner, coldex, rowdex) {
                    if (inner.length <= 0) return inner;
                    var el = $.parseHTML(inner);
                    var result = '';
                    $.each(el, function (index, item) {
                      if (item.classList !== undefined && item.classList.contains('program-name')) {
                        result = result + item.lastChild.firstChild.textContent;
                      } else if (item.innerText === undefined) {
                        result = result + item.textContent;
                      } else result = result + item.innerText;
                    });
                    return result;
                  }
                }
              }
            }
          ]
        },
        ...(window.userPermissions && window.userPermissions.includes('create_program_registration') ? [{
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Tambah Registrasi</span>',
          className: 'add-new btn btn-primary waves-effect waves-light',
          attr: {
            'data-bs-toggle': 'modal',
            'data-bs-target': '#tambahModal',
          },
          action: function (e, dt, node, config) {
            // Reset modal state sebelum dibuka
            resetModalState();
            ViewData(0);
          }
        }] : [])
    ],
    processing: true,
    serverSide: true,
    ajax: {
        url: "/program/program-registration",
        type: 'GET'
    },
    columns: [{
            data: null,
            name: 'id',
            render: function (data, type, full, meta) {
                return meta.row + 1;
            }
        },
        {
            data: 'program',
            name: 'program'
        },
        {
            data: 'user_id',
            name: 'user_id'
        },
        {
            data: 'status',
            name: 'status',
            render: function (data, type, full, meta) {
                return data == 'pending' ? '<span class="badge bg-label-warning">Pending</span>' : data == 'approved' ? '<span class="badge bg-label-success">Approved</span>' : '<span class="badge bg-label-danger">Rejected</span>';
            }
        },
        {
            data: 'alasan',
            name: 'alasan',
        },
        {
            data: 'notes_admin',
            name: 'notes_admin'
        },
        {
            data: 'registered_at',
            name: 'registered_at',
            render: function(data, type, full, meta) {
                if (!data) return '-';
                let dateObj = new Date(data);
                let day = String(dateObj.getDate()).padStart(2, '0');
                let month = String(dateObj.getMonth() + 1).padStart(2, '0');
                let year = dateObj.getFullYear();
                return day + '/' + month + '/' + year;
            }
        },
        {
            data: 'aksi',
            name: 'aksi',
            render: function (data, type, full, meta) {
                let userPermissions = window.userPermissions || [];
                let canEdit         = userPermissions.includes("edit_program_registration");
                let canDelete       = userPermissions.includes("delete_program_registration");

                let buttons = '<div class="d-flex align-items-center">';

                if (canDelete) {
                    buttons += '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" data-id="' + full.id + '"><i class="ti ti-trash ti-md"></i></a>';
                }

                buttons += '<a href="' + data + '" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>';

                if (canEdit) {
                    buttons += '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>';
                    buttons += '<div class="dropdown-menu dropdown-menu-end m-0">';
                    buttons += '<a href="javascript:;" class="dropdown-item" onclick="ViewData(\'' + full.id + '\')">Edit</a>';
                    buttons += '</div>';
                }

                buttons += '</div>';

                return buttons;
            }
        }
    ],
    order: [
        [0, 'asc']
    ],

});

$('.select2').select2({
    dropdownParent: $('#tambahModal')
});

// Form submission untuk internal page
$('#formProgramRegist').on('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);
    let id = $('#id').val();
    let url = '';

    if(id == "" || id == 0){
        url = "/program/program-registration/store";
    } else {
        url = "/program/program-registration/update/" + id;
        formData.append('_method', 'PUT');
    }

    // Debug: Log form data
    console.log('Form Data:');
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Bersihkan error sebelumnya
    $('.text-danger').remove();
    $('.is-invalid').removeClass('is-invalid');
    $('#modal-error').css('display', 'none');

    // Tampilkan loading dan disable form
    $('#modal-loading').show();
    $('#btn-simpan').prop('disabled', true).text('Menyimpan...');
    $('#formProgramRegist input, #formProgramRegist select, #formProgramRegist textarea').prop('disabled', true);

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            console.log('Success Response:', response);
            // Sembunyikan loading dan enable form
            $('#modal-loading').hide();
            $('#btn-simpan').prop('disabled', false).text('Simpan');
            $('#formProgramRegist input, #formProgramRegist select, #formProgramRegist textarea').prop('disabled', false);

            if(response.status == 200){
                toastr.success('Data berhasil disimpan!');
                $('#formProgramRegist')[0].reset();
                // Reset select2 ke default
                $('#program_id').val('').trigger('change');
                $('#user_id').val('').trigger('change');
                $('#status').val('').trigger('change');
                // Reset input date dan textarea
                $('#registered_at').val('');
                $('#alasan').val('');
                $('#notes_admin').val('');
                $('#TableProgramRegist').DataTable().ajax.reload(null, false);
                let modal = bootstrap.Modal.getInstance(document.getElementById('tambahModal'));
                modal.hide();
            } else {
                toastr.error('Terjadi kesalahan, silakan coba lagi!');
            }
        },
        error: function(xhr, status, error){
            console.log('Error Response:', xhr.responseText);
            console.log('Status:', status);
            console.log('Error:', error);
            
            // Sembunyikan loading dan enable form
            $('#modal-loading').hide();
            $('#btn-simpan').prop('disabled', false).text('Simpan');
            $('#formProgramRegist input, #formProgramRegist select, #formProgramRegist textarea').prop('disabled', false);

            if(xhr.status === 400) {
                let response = xhr.responseJSON;
                
                // Jika ada error message dari server (seperti kuota habis)
                if (response.message) {
                    $('#modal-error-message').text(response.message);
                    $('#modal-error').show();
                    toastr.error(response.message);
                } else {
                    // Error validasi form biasa
                    let errors = response.errors;
                    let errorMessage = "Harap periksa kembali inputan Anda!";

                    $.each(errors, function(key, value){
                        let inputField = $('[name="' + key + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<span class="text-danger">' + value[0] + '</span>');
                        toastr.error(value[0]);
                    });
                }

            } else {
                toastr.error('Terjadi kesalahan, silakan coba lagi!');
            }
        }
    });
});

// Function untuk mengisi modal saat update
window.ViewData = function (id) {
    // Reset modal state sebelum dibuka
    resetModalState();
    
    // Pastikan modal benar-benar di-reset sebelum ditampilkan
    setTimeout(function() {
        $('#tambahModal').modal('show');
    }, 50);

    if (id === 0) {
        // Mode Insert (Tambah Data)
        $('#modal-judul').text('Tambah Program Regist');
        $('#formProgramRegist')[0].reset();
        $('#btn-simpan').val('create');
    } else {
        // Mode Edit (Ambil data dari API)
        $('#modal-judul').text('Edit Program Regist');
        $('#btn-simpan').val('update');

        $.ajax({
            url: '/program/program-registration/edit/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response) {
                    $('#id').val(response.id);
                    $('#program_id').val(response.program_id);
                    $('#user_id').val(response.user_id);
                    $('#registered_at').val(response.registered_at);
                    $('#alasan').val(response.alasan);
                    $('#status').val(response.status);
                    $('#notes_admin').val(response.notes_admin);
                    $.ajax({
                        url: "/program/get-program/" + response.program_id,
                        type: "GET",
                        success: function(kotaResponse) {
                            $('#program_id').empty().append('<option selected disabled>Pilih Program</option>');

                            kotaResponse.forEach(function (data) {
                                $('#program_id').append('<option value="' + data.id + '">' + data.name + '</option>');
                            });

                            $('#program_id').val(response.program_id).trigger('change');
                        }
                    });
                }
            },
            error: function() {
                alert('Gagal mengambil data!');
            }
        });
    }

}

// Tambahan: Reset modal saat tombol close diklik
$(document).on('click', '[data-bs-dismiss="modal"]', function() {
    setTimeout(function() {
        resetModalState();
    }, 100);
});

// Tambahan: Reset modal saat escape key ditekan
$(document).on('keydown', function(e) {
    if (e.key === 'Escape' && $('#tambahModal').hasClass('show')) {
        setTimeout(function() {
            resetModalState();
        }, 100);
    }
});

// Tambahan: Reset modal saat klik di luar modal
$(document).on('click', '.modal-backdrop', function() {
    setTimeout(function() {
        resetModalState();
    }, 100);
});

// Tambahan: Reset modal saat modal di-hide secara programmatic
$(document).on('hide.bs.modal', '#tambahModal', function() {
    resetModalState();
});

// Tambahan: Reset modal saat modal mulai dibuka
$(document).on('show.bs.modal', '#tambahModal', function () {
    resetModalState();
});

// Tambahan: Reset modal dengan timeout untuk memastikan modal sudah siap
$(document).on('shown.bs.modal', '#tambahModal', function () {
    setTimeout(function() {
        resetModalState();
    }, 100);
});

// Tambahan: Reset modal saat modal mulai di-hide
$(document).on('hide.bs.modal', '#tambahModal', function () {
    resetModalState();
});

// Tambahan: Reset modal saat modal selesai di-hide
$(document).on('hidden.bs.modal', '#tambahModal', function () {
    setTimeout(function() {
        resetModalState();
    }, 100);
});



$(document).on('click', '.delete-record', function () {
    let id = $(this).data('id');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data registrasi program akan dihapus!",
        icon: 'warning',
        customClass: {
            confirmButton: 'btn btn-primary waves-effect waves-light ml-3',
            cancelButton: 'btn btn-label-secondary waves-effect waves-light'
        },
        showCancelButton: true,
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        didRender: function () {
            $('.swal2-actions').css('gap', '10px');
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/program/program-registration/delete/' + id,
                type: 'DELETE',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            customClass: {
                              confirmButton: 'btn btn-success waves-effect waves-light'
                            }
                        });
                        $('#TableProgramRegist').DataTable().ajax.reload();
                    }
                },
                error: function () {
                    Swal.fire('Oops!', 'Terjadi kesalahan saat menghapus data.', 'error');
                }
            });
        }
    });
});

