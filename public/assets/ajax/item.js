$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function () {
    $('#TableItem').DataTable({
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
                          if (item.classList !== undefined && item.classList.contains('item-nm_item')) {
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
                          if (item.classList !== undefined && item.classList.contains('item-nm_item')) {
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
                          if (item.classList !== undefined && item.classList.contains('item-nm_item')) {
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
                          if (item.classList !== undefined && item.classList.contains('item-nm_item')) {
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
                          if (item.classList !== undefined && item.classList.contains('item-nm_item')) {
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
            {
              text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Tambah Item</span>',
              className: 'add-new btn btn-primary waves-effect waves-light',
              attr: {
                'data-bs-toggle': 'modal',
                'data-bs-target': '#tambahModal',

              }
            }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "/item/",
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
                data: 'kd_barcode',
                name: 'kd_barcode'
            },
            {
                data: 'kd_item',
                name: 'kd_item'
            },
            {
                data: 'nm_item',
                name: 'nm_item'
            },
            {
                data: 'jenis_item',
                name: 'jenis_item',
                render: function (data, type, full, meta) {
                    var $sparepart = '<span class="badge bg-label-primary">Sparepart</span>';
                    var $tools = '<span class="badge bg-label-info">Tools</span>';
                    if (data == 1) {
                        return $sparepart;
                    } else if (data == 2) {
                        return $tools;
                    } else {
                        '<span class="badge bg-label-dark">Lainnya</span>'
                    }
                    return '';
                }
            },
            {
                data: 'stok_satuan',
                name: 'stok_satuan'
            },
            {
                data: 'hrg_beli',
                name: 'hrg_beli',
                render: function (data) {
                    return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(data);
                }
            },
            {
                data: 'hrg_jual',
                name: 'hrg_jual',
                render: function (data) {
                    return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(data);
                }
            },
            {
                data: 'aksi',
                name: 'aksi',
                render: function (data, type, full, meta) {
                    let userPermissions = window.userPermissions || [];
                    let canEdit         = userPermissions.includes("edit pelanggan");
                    let canDelete       = userPermissions.includes("delete pelanggan");

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

    $("#hrg_beli, #hrg_jual").on("input", function () {
        let value = $(this).val().replace(/[^0-9]/g, '');

        if (value === "") {
            $(this).val("");
            return;
        }

        let number = parseInt(value, 10);
        let formattedValue = number.toLocaleString('id-ID');

        $(this).val(formattedValue);
    });

    $("#formItem").on("submit", function () {
        $("#hrg_beli, #hrg_jual").each(function () {
            let value = $(this).val().replace(/\./g, "");
            $(this).val(value);
        });
    });


    $('#formItem').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);
        let id = $('#id').val();
        let url = '';

        if(id == "" || id == 0){
            url = "/item/store";
        } else {
            url = "/item/update/" + id;
            formData.append('_method', 'PUT');
        }

        // Bersihkan error sebelumnya
        $('.text-danger').remove();
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response){
                if(response.status == 200){
                    toastr.success('Data berhasil disimpan!');
                    $('#formItem')[0].reset();
                    $('#TableItem').DataTable().ajax.reload(null, false);
                    let modal = bootstrap.Modal.getInstance(document.getElementById('tambahModal'));
                    modal.hide();
                } else {
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            },
            error: function(xhr){
                if(xhr.status === 400) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = "Harap periksa kembali inputan Anda!";

                    $.each(errors, function(key, value){
                        let inputField = $('[name="' + key + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<span class="text-danger">' + value[0] + '</span>');
                        toastr.error(value[0]);
                    });

                } else {
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            }
        });
    });

    // Function untuk mengisi modal saat update
    window.ViewData = function (id) {
        $('#tambahModal').modal('show');

        if (id === 0) {
            // Mode Insert (Tambah Data)
            $('#modal-judul').text('Tambah Item');
            $('#formItem')[0].reset();
            $('#btn-simpan').val('create');
        } else {
            // Mode Edit (Ambil data dari API)
            $('#modal-judul').text('Edit Item');
            $('#btn-simpan').val('update');

            $.ajax({
                url: '/item/edit/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        $('#id').val(response.id);
                        $('#id_satuan').val(response.id_satuan);
                        $('#id_vendor').val(response.id_vendor);
                        $('#nm_item').val(response.nm_item);
                        $('#jenis_item').val(response.jenis_item);
                        $('#tgl_masuk_item').val(response.tgl_masuk_item);
                        $('#merk').val(response.merk);
                        $('#stok').val(response.stok);
                        $('#hrg_beli').val(formatRupiah(response.hrg_beli));
                        $('#hrg_jual').val(formatRupiah(response.hrg_jual));
                        $('#rak').val(response.rak);
                        $('#deskripsi').val(response.deskripsi);
                        $('#foto_item').val(response.foto_item).trigger('change');
                    }
                },
                error: function() {
                    alert('Gagal mengambil data!');
                }
            });
        }
    }

    // Fungsi untuk format angka ke 900.000,00
    function formatRupiah(angka) {
        let number = parseFloat(angka).toFixed(2)
            .replace(".", ",");

        return number.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $(document).on('click', '.delete-record', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data role akan dihapus!",
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
                    url: '/item/delete/' + id,
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
                            $('#TableItem').DataTable().ajax.reload();
                        } else {
                            Swal.fire(
                                'Error!',
                                response.errors,
                                'error'
                            );
                        }
                    },
                    error: function () {
                        Swal.fire(
                            'Oops!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                });
            }
        });
    });


});
