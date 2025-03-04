$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function () {
    $('#TableMGroup').DataTable({
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
                text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Tambah Menu</span>',
                className: 'add-new btn btn-primary waves-effect waves-light mx-4',
                attr: {
                    'data-bs-toggle': 'offcanvas',
                    'data-bs-target': '#offcanvasAddMenu',

                }
            }
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: "/menu-groups/",
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
                data: 'jenis_menu',
                name: 'jenis_menu',
                render: function (data, type, full, meta) {
                    if(full.jenis_menu == 1) {
                        return '<span class="badge bg-label-primary">Master</span>'
                    } else if (full.jenis_menu == 2) {
                        return '<span class="badge bg-label-secondary">Transaksi</span>'
                    } else {
                        return '<span class="badge bg-label-info">Settings</span>'
                    }
                }
            },
            {
                data: 'icon',
                name: 'icon'
            },
            {
                data: 'order',
                name: 'order'
            },
            {
                data: 'aksi',
                name: 'aksi',
                render: function (data, type, full, meta) {
                    return (
                        '<div class="d-flex align-items-center">' +
                        '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" data-id="' + full.id + '"><i class="ti ti-trash ti-md"></i></a>' +
                        '<a href="' + data + '" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>' +
                        '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>' +
                        '<div class="dropdown-menu dropdown-menu-end m-0">' +
                        '<a href="javascript:;" class="dropdown-item" onclick="ViewData(' + full.id + ')">Edit</a>' +
                        '</div>' +
                        '</div>'
                    );
                }
            },
        ],
        order: [
            [0, 'asc']
        ],

    });

    // Fungsi untuk menampilkan data ke dalam offcanvas (Edit)
    window.ViewData = function (id) {
        $.ajax({
            url: `/menu-groups/edit/${id}/`, // Pastikan route ini benar
            type: "GET",
            success: function (response) {
                if (response.success) {
                    selectedId = id;
                    $("#id").val(response.menu.id);
                    $("#nama_menu").val(response.menu.name);
                    $("#jenis_menu").val(response.menu.jenis_menu);
                    $("#icon").val(response.menu.icon);
                    $("#order").val(response.menu.order);

                    // Ubah tombol submit agar tahu ini update
                    $(".data-submit").text("Update").attr("id", "updateMenu");

                    $("#offcanvasAddMenu").offcanvas("show");
                }
            },
            error: function () {
                toastr.error('Gagal mengambil data!');
            }
        });
    };

    // Submit Form: Tambah & Update
    $("#formMenu").submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let id = $("#id").val();
        let url = "/menu-groups/store";
        let method = "POST";

        if (id) {
            url = "/menu-groups/update/" + id;
            formData.append("_method", "PUT"); // Tambahkan method PUT untuk Laravel
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

                    $('#TableMGroup').DataTable().ajax.reload(null, false);
                    $("#formMenu")[0].reset();
                    $("#id").val(""); // Reset ID agar tidak salah update nanti

                    let offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById("offcanvasAddMenu"));
                    if (offcanvas) offcanvas.hide();

                    $(".data-submit").text("Submit").removeAttr("id");
                    selectedId = null;
                }
            },
            error: function () {
                toastr.error('Gagal menyimpan data!');
            }
        });
    });

});
