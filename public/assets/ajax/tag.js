$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function () {
    $('#Tabletag').DataTable({
        dom:
            '<"row me-2"' +
            '<"col-md-2"<"me-3"l>>' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-3"fB>>' +
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
                text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Tambah tag</span>',
                className: 'add-new btn btn-primary waves-effect waves-light',
                attr: {
                    'data-bs-toggle': 'offcanvas',
                    'data-bs-target': '#offcanvasAddtag',

                }
            }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "/tag/",
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
                data: 'slug',
                name: 'slug',
                render: function (data, type, full, meta) {
                    if (!data || data.trim() === "") {
                        return '-';
                    }
                    return data;
                }
            },
            {
                data: 'aksi',
                name: 'aksi',
                render: function (data, type, full, meta) {
                    let userPermissions = window.userPermissions || [];
                    let canEdit         = userPermissions.includes("edit tag");
                    let canDelete       = userPermissions.includes("delete tag");

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

    // Reset form when add new tag button is clicked
    $('.card').on('click', '.dt-action-buttons .add-new', function() {
        $('#id').val('');
        $('#formTag')[0].reset();
        $('#offcanvasAddtagLabel').text('Tambah Tag');
        $('.data-submit').text('Submit');
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.text-danger').text('');
    });

    let selectedId = null;

    window.ViewData = function (id) {
        $('.form-control, .form-select').removeClass('is-invalid');
        $('.text-danger').text('');

        $.ajax({
            url: `/tag/edit/${id}/`,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    selectedId = id;
                    $("#id").val(response.tag.id);
                    $("#name").val(response.tag.name);
                    $("#slug").val(response.tag.slug);
                    $('#offcanvasAddtagLabel').text('Edit Tag');
                    // Ubah tombol submit agar tahu ini update
                    $(".data-submit").text("Update").attr("id", "updateMenu");

                    $("#offcanvasAddtag").offcanvas("show");
                }
            },
            error: function () {
                toastr.error('Gagal mengambil data!');
            }
        });
    }

    // Submit Form: Tambah & Update
    $("#formTag").submit(function (e) {
        e.preventDefault();

        $('.form-control, .form-select').removeClass('is-invalid');
        $('.text-danger').text('');

        let formData = new FormData(this);
        let id       = $("#id").val();
        let url      = "/tag/store";
        let method   = "POST";

        if (id) {
            url = "/tag/update/" + id;
            formData.append("_method", "PUT");
        }

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);

                    $('#Tabletag').DataTable().ajax.reload(null, false);
                    $("#formTag")[0].reset();
                    $("#id").val(""); // Reset ID agar tidak salah update nanti

                    $("#offcanvasAddtag").offcanvas("hide");

                    $(".data-submit").text("Submit").removeAttr("id");
                    selectedId = null;
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, value) {
                        $('#' + key).addClass('is-invalid');
                        $('#' + key + '-error').text(value[0]);
                    });
                } else {
                    toastr.error('Gagal menyimpan data!');
                }
            }
        });
    });

    $(document).on('click', '.delete-record', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data tag akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/tag/delete/' + id,
                    type: 'DELETE',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === 200) {
                            Swal.fire(
                                'Deleted!',
                                'Data Tag Berhasil Dihapus.',
                                'success'
                            );
                            $('#Tabletag').DataTable().ajax.reload(null, false);
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
