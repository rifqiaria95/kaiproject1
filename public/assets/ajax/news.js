$(document).ready(function () {
    $('.select2').select2({
        dropdownParent: $('#tambahModal')
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function () {
    $('#TableNews').DataTable({
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
                        $.each(el, function (index, perusahaan) {
                          if (perusahaan.classList !== undefined && perusahaan.classList.contains('perusahaan-nm_item')) {
                            result = result + perusahaan.lastChild.firstChild.textContent;
                          } else if (perusahaan.innerText === undefined) {
                            result = result + perusahaan.textContent;
                          } else result = result + perusahaan.innerText;
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
                        $.each(el, function (index, perusahaan) {
                          if (perusahaan.classList !== undefined && perusahaan.classList.contains('perusahaan-nm_item')) {
                            result = result + perusahaan.lastChild.firstChild.textContent;
                          } else if (perusahaan.innerText === undefined) {
                            result = result + perusahaan.textContent;
                          } else result = result + perusahaan.innerText;
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
                        $.each(el, function (index, perusahaan) {
                          if (perusahaan.classList !== undefined && perusahaan.classList.contains('perusahaan-nm_item')) {
                            result = result + perusahaan.lastChild.firstChild.textContent;
                          } else if (perusahaan.innerText === undefined) {
                            result = result + perusahaan.textContent;
                          } else result = result + perusahaan.innerText;
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
                        $.each(el, function (index, perusahaan) {
                          if (perusahaan.classList !== undefined && perusahaan.classList.contains('perusahaan-nm_item')) {
                            result = result + perusahaan.lastChild.firstChild.textContent;
                          } else if (perusahaan.innerText === undefined) {
                            result = result + perusahaan.textContent;
                          } else result = result + perusahaan.innerText;
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
                        $.each(el, function (index, perusahaan) {
                          if (perusahaan.classList !== undefined && perusahaan.classList.contains('perusahaan-nm_item')) {
                            result = result + perusahaan.lastChild.firstChild.textContent;
                          } else if (perusahaan.innerText === undefined) {
                            result = result + perusahaan.textContent;
                          } else result = result + perusahaan.innerText;
                        });
                        return result;
                      }
                    }
                  }
                }
              ]
            },
            {
              text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">Tambah Berita</span>',
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
            url: "portfolio/news/",
            type: 'GET'
        },
        columns: [{
              data: null,
              name: 'no_urut',
              title: 'No',
              orderable: false,
              searchable: false,
              render: function (data, type, full, meta) {
                  // Mengembalikan nomor urut otomatis berdasarkan index baris
                  return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            {
              data: 'thumbnail',
              name: 'thumbnail',
              render: function (data, type, full, meta) {
                  if (data) {
                    return '<img src="' + window.images_path + '/' + data + '" alt="Thumbnail" class="img-fluid" style="width: 30px; height: 30px;">';
                  } else {
                    return '<img src="https://via.placeholder.com/50" alt="Thumbnail" class="img-fluid" style="width: 30px; height: 30px;">';
                  }
              }
            },
            {
              data: 'title',
              name: 'title'
            },
            {
              data: 'slug',
              name: 'slug'
            },
            {
              data: 'content',
              name: 'content'
            },
            {
              data: 'summary',
              name: 'summary'
            },
            {
              data: 'author',
              name: 'author'
            },
            {
              data: 'category',
              name: 'category'
            },
            {
              data: 'tags',
              name: 'tags'
            },
            {
              data: 'status',
              name: 'status',
              render: function (data, type, full, meta) {
                let labelClass = '';
                let labelText = '';
                if (data === 'draft') {
                  labelClass = 'badge bg-dark';
                  labelText = 'Draft';
                } else if (data === 'archived') {
                  labelClass = 'badge bg-secondary';
                  labelText = 'Archived';
                } else if (data === 'published') {
                  labelClass = 'badge bg-success';
                  labelText = 'Published';
                } else {
                  labelClass = 'badge bg-light';
                  labelText = data;
                }
                return '<span class="' + labelClass + '">' + labelText + '</span>';
              }
            },
            {
              data: 'published_at',
              name: 'published_at'
            },
            {
                data: 'aksi',
                name: 'aksi',
                render: function (data, type, full, meta) {
                    let userPermissions = window.userPermissions || [];
                    let canEdit         = userPermissions.includes("edit_news");
                    let canDelete       = userPermissions.includes("delete_news");

                    let buttons = '<div class="d-flex align-items-center">';

                    if (canDelete) {
                        buttons += '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" data-id="' + full.id + '"><i class="ti ti-trash ti-md"></i></a>';
                    }

                    buttons += '<a href="' + data + '" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>';

                    if (canEdit) {
                        buttons += '<a href="javascript:;" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical ti-md"></i></a>';
                        buttons += '<div class="dropdown-menu dropdown-menu-end m-0">';
                        buttons += '<a href="javascript:;" class="dropdown-item" onclick="editNews(\'' + full.id + '\')">Edit</a>';
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

    $('#formNews').on('submit', function(e){
        e.preventDefault();

        $('#formNews .form-control, #formNews .form-select').removeClass('is-invalid');
        $('#formNews .text-danger.small').text('');

        // Trigger TinyMCE to save content to textarea
        if (tinymce.get('content')) {
            tinymce.get('content').save();
        }

        let formData = new FormData(this);
        let id = $('#id').val();
        let url = '';
        let method = '';

        if(id){
            url = '/portfolio/news/update/' + id;
            method = 'POST';
            formData.append('_method', 'PUT');
        } else {
            url = '/portfolio/news/store';
            method = 'POST';
        }

        $.ajax({
            url: url,
            type: method,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                if (response.status === 200) {
                    $('#tambahModal').modal('hide');
                    $('#TableNews').DataTable().ajax.reload();
                    toastr.success('Data berhasil disimpan!');
                } else {
                    toastr.error('Terjadi kesalahan, silakan coba lagi!');
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

    $('#tambahModal').on('hidden.bs.modal', function () {
        $('#formNews')[0].reset();
        $('#id').val('');
        $('#modal-judul').text('Tambah Berita');
        $('#formNews .form-control, #formNews .form-select').removeClass('is-invalid');
        $('#formNews .text-danger.small').text('');
        
        // Clear Select2
        $('#category_id').val('').trigger('change');
        $('#tags_id').val('').trigger('change');
        
        // Clear TinyMCE content
        if (tinymce.get('content')) {
            tinymce.get('content').setContent('');
        }
    });
});

function editNews(id) {
    $('#formNews .form-control, #formNews .form-select').removeClass('is-invalid');
    $('#formNews .text-danger.small').text('');

    $.ajax({
        url: '/portfolio/news/edit/' + id,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                let news = response.news;
                $('#id').val(news.id);
                $('#title').val(news.title);
                $('#slug').val(news.slug);
                
                // Set content to TinyMCE editor
                if (tinymce.get('content')) {
                    tinymce.get('content').setContent(news.content || '');
                } else {
                    $('#content').val(news.content);
                }
                
                $('#summary').val(news.summary);
                $('#status').val(news.status);
                
                // Set kategori dan tag
                $('#category_id').val(news.category_id).trigger('change');
                $('#tags_id').val(news.tags_id).trigger('change');
                
                // Format datetime untuk input datetime-local
                if (news.published_at) {
                    let publishedDate = new Date(news.published_at);
                    let formattedPublished = publishedDate.getFullYear() + '-' + 
                        String(publishedDate.getMonth() + 1).padStart(2, '0') + '-' + 
                        String(publishedDate.getDate()).padStart(2, '0') + 'T' + 
                        String(publishedDate.getHours()).padStart(2, '0') + ':' + 
                        String(publishedDate.getMinutes()).padStart(2, '0');
                    $('#published_at').val(formattedPublished);
                } else {
                    $('#published_at').val('');
                }
                
                if (news.archived_at) {
                    let archivedDate = new Date(news.archived_at);
                    let formattedArchived = archivedDate.getFullYear() + '-' + 
                        String(archivedDate.getMonth() + 1).padStart(2, '0') + '-' + 
                        String(archivedDate.getDate()).padStart(2, '0') + 'T' + 
                        String(archivedDate.getHours()).padStart(2, '0') + ':' + 
                        String(archivedDate.getMinutes()).padStart(2, '0');
                    $('#archived_at').val(formattedArchived);
                } else {
                    $('#archived_at').val('');
                }
                $('#modal-judul').text('Edit Berita');
                $('#tambahModal').modal('show');
            } else {
                toastr.error('Data news tidak ditemukan.');
            }
        },
        error: function() {
            toastr.error('Terjadi kesalahan saat mengambil data.');
        }
    });
}

$(document).on('click', '.delete-record', function () {
    let id = $(this).data('id');

    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data news akan dihapus!",
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
                url: '/portfolio/news/delete/' + id,
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
                        $('#TableNews').DataTable().ajax.reload();
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
