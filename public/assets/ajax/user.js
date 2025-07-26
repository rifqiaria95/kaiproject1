$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#TableUser').DataTable({
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
                          if (item.classList !== undefined && item.classList.contains('user-name')) {
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
                          if (item.classList !== undefined && item.classList.contains('user-name')) {
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
                          if (item.classList !== undefined && item.classList.contains('user-name')) {
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
                          if (item.classList !== undefined && item.classList.contains('user-name')) {
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
                          if (item.classList !== undefined && item.classList.contains('user-name')) {
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
        ],
        processing: true,
        serverSide: true, //aktifkan server-side
        ajax: {
            url: "admin/users/",
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
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'active',
                name: 'active'
            },
            {
                data: 'role',
                name: 'role'
            },
            {
                data: 'aksi',
                name: 'aksi',
                render: function (data, type, full, meta) {
                    let userPermissions = window.userPermissions || [];
                    let canEdit         = userPermissions.includes("edit_user_management");
                    let canShow         = userPermissions.includes("show_user_management");
                    let canDelete       = userPermissions.includes("delete_user_management");

                    return (
                        '<div class="d-flex align-items-center">' +
                        (canDelete ? '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" data-id="' + full.id + '"><i class="ti ti-trash ti-md"></i></a>' : '') +
                        (canShow ? '<a href="/admin/users/profile/' + full.id + '" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>' : '') +
                        (canEdit ? '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>' +
                        '<div class="dropdown-menu dropdown-menu-end m-0">' +
                        '<a href="javascript:;" class="dropdown-item dropdown-edit" onclick="ViewData(' + full.id + ')">Edit</a>' +
                        '</div>' : '') +
                        '</div>'
                    );
                }
            },
        ],
        order: [
            [0, 'desc']
        ],

    });

    // Event klik tombol Edit
    $(document).on('click', '.dropdown-edit', function () {
        var userId = $(this).attr('onclick').match(/\d+/)[0];
        ViewData(userId);
    });

    // Fungsi untuk menampilkan data user di modal edit
    window.ViewData = function (id) {
        $.ajax({
            url: "admin/users/edit/" + id,
            type: "GET",
            success: function (res) {
                console.log(res);
                if (res) {
                    $('#id').val(res.user.id);
                    $('#name').val(res.user.name);
                    $('#email').val(res.user.email);
                    $('#active').val(res.user.active);
                    
                    // Reset password fields
                    $('#password').val('');
                    $('#password_confirmation').val('');

                    // Kosongkan dropdown dan isi dengan data role dari server
                    $('#role').empty();
                    $('#role').append('<option value="">Pilih Role</option>');
                    $.each(res.roles, function (key, role) {
                        var selected = res.userRole == role.id ? "selected" : "";
                        $('#role').append('<option value="' + role.id + '" ' + selected + '>' + role.name + '</option>');
                    });

                    // Tampilkan modal edit
                    $('#editModal').modal('show');
                    $('#modalJudulEdit').text('Edit User');
                }
            }
        });
    }

    // Event submit form update
    $('#formEdit').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        var id = $('#id').val();

        $.ajax({
            url: "admin/users/update/" + id,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#btn-update').prop('disabled', true).text('Updating...');
            },
            success: function (res) {
                if (res.status === 200) {
                    toastr.success('Data berhasil disimpan!');
                    $('#editModal').modal('hide');
                    $('#formEdit')[0].reset();
                    
                    // Clear error messages
                    $('.text-danger').remove();
                    $('.is-invalid').removeClass('is-invalid');
                    
                    // Reset select2 dropdowns
                    $('#role').val('').trigger('change');
                    $('#active').val('').trigger('change');
                    
                    $('#TableUser').DataTable().ajax.reload(null, false);
                } else {
                    alert(res.errors);
                }
            },
            error: function (xhr) {
                if(xhr.status === 400 || xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    
                    // Clear previous error messages
                    $('.text-danger').remove();
                    $('.is-invalid').removeClass('is-invalid');

                    $.each(errors, function(key, value){
                        let inputField = $('[name="' + key + '"]');
                        inputField.addClass('is-invalid');
                        inputField.after('<span class="text-danger">' + value[0] + '</span>');
                        toastr.error(value[0]);
                    });

                } else {
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
                }
            },
            complete: function () {
                $('#btn-update').prop('disabled', false).text('Simpan');
            }
        });
    });

    $(document).on('click', '.delete-record', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data user akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'admin/users/delete/' + id,
                    type: 'DELETE',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 200) {
                            Swal.fire(
                                'Deleted!',
                                'Data Pegawai Berhasil Dihapus.',
                                'success'
                            );
                            $('#TableUser').DataTable().ajax.reload(null, false);
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
