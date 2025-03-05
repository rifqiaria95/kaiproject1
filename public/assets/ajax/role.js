$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#TableRole').DataTable({
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
                          if (item.classList !== undefined && item.classList.contains('pegawai-nm_pegawai')) {
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
                          if (item.classList !== undefined && item.classList.contains('pegawai-nm_pegawai')) {
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
                          if (item.classList !== undefined && item.classList.contains('pegawai-nm_pegawai')) {
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
                          if (item.classList !== undefined && item.classList.contains('pegawai-nm_pegawai')) {
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
                          if (item.classList !== undefined && item.classList.contains('pegawai-nm_pegawai')) {
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
                text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Tambah Role</span>',
                className: 'add-new btn btn-primary waves-effect waves-light',
                action: function () {  // Gunakan event action agar tetap bisa dipanggil dalam DataTables
                    ViewData(0);
                }
            }
        ],
        processing: true,
        serverSide: true, //aktifkan server-side
        ajax: {
            url: "/role/",
            type: 'GET'
        },
        columns: [
            {
                data: null,
                name: 'id',
                render: function (data, type, full, meta) {
                    return meta.row + 1;
                }
            },
            {
                data: 'name',
                name: 'name',
                render: function (data, type, full, meta) {
                    return '<span class="badge bg-label-success">'+ data +'</span>'
                }
            },
            {
                data: 'aksi',
                name: 'aksi',
                render: function (data, type, full, meta) {
                    let userPermissions = window.userPermissions || [];
                    let canEdit         = userPermissions.includes("edit role");
                    let canDelete       = userPermissions.includes("delete role");

                    let buttons = '<div class="d-flex align-items-center">';

                    if (canDelete) {
                        buttons += '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" data-id="' + full.id + '"><i class="ti ti-trash ti-md"></i></a>';
                    }

                    buttons += '<a href="' + data + '" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>';

                    if (canEdit) {
                        buttons += '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>';
                        buttons += '<div class="dropdown-menu dropdown-menu-end m-0">';
                        buttons += '<a href="javascript:;" class="dropdown-item" onclick="ViewData(' + full.id + ')">Edit</a>';
                        buttons += '</div>';
                    }

                    buttons += '</div>';

                    return buttons;
                }
            }
        ],
        order: [
            [0, 'desc']
        ],

    });

    $("#tambahModal").on("show.bs.modal", function () {
        $("#id").val("");
        $("#formRole")[0].reset();
    });

    $(document).on("click", ".dropdown-edit", function () {
        var roleId = $(this).data("id");
        ViewData(roleId);
    });

    $(document).on("click", ".add-new", function () {
        ViewData(0);
    });

    $('#selectAll').change(function () {

        var isChecked = $(this).prop('checked');

        $('.permission-checkbox').prop('checked', isChecked);
    });


    $('.permission-checkbox').change(function () {

        if ($('.permission-checkbox:checked').length === $('.permission-checkbox').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }
    });

    // Fungsi untuk menampilkan data ke dalam modal (Tambah/Edit)
    function ViewData(id) {
        $('#tambahModal').modal('show');

        if (id === 0) {
            // Mode Tambah
            $('#modal-judul').text('Tambah Role');
            $('#formRole')[0].reset();
            $('#btn-update').val('create');
            $(".permission-checkbox").prop("checked", false);

        } else {
            // Mode Edit
            $('#modal-judul').text('Edit Role');
            $('#btn-update').val('update');

            $.ajax({
                url: `/role/edit/${id}/`,
                type: "GET",
                success: function (response) {
                    console.log("Response dari server:", response);

                    if (!response.role) {
                        toastr.error("Role tidak ditemukan.");
                        return;
                    }

                    // Set Nama Role
                    $("#id").val(response.role.id);
                    $("#name").val(response.role.name);

                    // Uncheck semua checkbox dulu
                    $(".permission-checkbox").prop("checked", false);

                    // Centang checkbox sesuai permission yang dimiliki role
                    response.rolePermissions.forEach(function(permissionId) {
                        $("input.permission-checkbox[value='" + permissionId + "']").prop("checked", true);
                    });

                },
                error: function (xhr) {
                    toastr.error("Gagal mengambil data role.");
                }
            });
        }
    }

    // Submit Form: Tambah & Update
    $(document).ready(function () {
        $("#formRole").on("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let id = $("#id").val();
            let url = id ? `/role/update/${id}` : "/role/store";
            let method = id ? "POST" : "POST"; // Laravel butuh _method untuk PUT

            if (id) {
                formData.append("_method", "PUT");
            }

            // Debug: Lihat data sebelum dikirim
            for (let pair of formData.entries()) {
                console.log(pair[0] + ": " + pair[1]);
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log("Response dari server:", response);
                    if (response.success) {
                        $("#tambahModal").modal("hide");
                        $("#formRole")[0].reset();
                        toastr.success(response.message);
                        $("#TableRole").DataTable().ajax.reload(null, false);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    console.error("Error response:", xhr.responseJSON);
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = "";
                    $.each(errors, function (key, value) {
                        errorMessages += value + "<br>";
                    });
                    toastr.error(errorMessages);
                }
            });
        });
    });

    $(document).on('click', '.delete-record', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data role akan dihapus!",
            icon: 'warning',
            customClass: {
                confirmButton: 'btn btn-primary waves-effect waves-light',
                cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            showCancelButton: true,
            cancelButtonText: 'Batal',
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/role/delete/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                            $('#TableRole').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Oops!', errorMessage, 'error');
                    }
                });
            }
        });
    });

});
