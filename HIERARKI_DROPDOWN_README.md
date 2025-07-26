# Implementasi Hierarki Dropdown

## Deskripsi
Sistem ini mengimplementasikan hierarki dropdown dimana:
- **Cabang** hanya dapat dipilih setelah **Perusahaan** dipilih
- **Departemen** hanya dapat dipilih setelah **Divisi** dipilih

## Fitur yang Diimplementasikan

### 1. API Endpoints
- `GET /cabang/get-by-perusahaan/{id_perusahaan}` - Mendapatkan cabang berdasarkan perusahaan
- `GET /departemen/get-by-divisi/{id_divisi}` - Mendapatkan departemen berdasarkan divisi
- `GET /pegawai/get-cabang/{id_perusahaan}` - Mendapatkan cabang untuk form pegawai
- `GET /pegawai/get-departemen/{id_divisi}` - Mendapatkan departemen untuk form pegawai

### 2. Controller Methods
- `CabangController::getByPerusahaan()` - Mengambil cabang berdasarkan ID perusahaan
- `DepartemenController::getByDivisi()` - Mengambil departemen berdasarkan ID divisi
- `PegawaiController::getCabangByPerusahaan()` - Mengambil cabang untuk form pegawai
- `PegawaiController::getDepartemenByDivisi()` - Mengambil departemen untuk form pegawai

### 3. View Components
- Filter dropdown di halaman cabang dan departemen
- Form dengan hierarki dropdown
- JavaScript untuk menangani perubahan dropdown

### 4. JavaScript Functions
- `hierarchy-dropdown.js` - File JavaScript untuk menangani hierarki dropdown
- Event handlers untuk perubahan dropdown
- AJAX calls untuk mengambil data berdasarkan hierarki

## Cara Penggunaan

### 1. Di Form
```html
<!-- Perusahaan Dropdown -->
<select id="id_perusahaan" name="id_perusahaan" class="form-select">
    <option value="" selected disabled>Pilih Perusahaan</option>
    @foreach ($perusahaan as $p)
        <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
    @endforeach
</select>

<!-- Cabang Dropdown (akan diisi berdasarkan perusahaan) -->
<select id="id_cabang" name="id_cabang" class="form-select">
    <option value="" selected disabled>Pilih Cabang</option>
</select>
```

### 2. Include JavaScript
```html
<script src="{{ url('assets/js/hierarchy-dropdown.js') }}"></script>
```

### 3. Di Controller
```php
public function getCabangByPerusahaan($id_perusahaan)
{
    $cabang = Cabang::where('id_perusahaan', $id_perusahaan)->get();
    return response()->json($cabang);
}
```

### 4. Di Routes
```php
Route::get('/get-cabang/{id_perusahaan}', [PegawaiController::class, 'getCabangByPerusahaan'])->name('pegawai.get-cabang');
```

## Alur Kerja

1. **User memilih Perusahaan**
   - JavaScript menangkap event change
   - AJAX call ke endpoint `/pegawai/get-cabang/{id_perusahaan}`
   - Dropdown cabang di-reset dan diisi dengan data yang sesuai

2. **User memilih Divisi**
   - JavaScript menangkap event change
   - AJAX call ke endpoint `/pegawai/get-departemen/{id_divisi}`
   - Dropdown departemen di-reset dan diisi dengan data yang sesuai

3. **Filter di Halaman Index**
   - User dapat memfilter data berdasarkan perusahaan/divisi
   - Data yang ditampilkan hanya yang sesuai dengan filter

## Keuntungan

1. **Data Integrity** - Memastikan data yang dipilih sesuai dengan hierarki
2. **User Experience** - User tidak bingung dengan pilihan yang tidak relevan
3. **Performance** - Hanya menampilkan data yang diperlukan
4. **Scalability** - Mudah ditambahkan ke form lain

## Contoh Implementasi di Form Lain

Untuk menambahkan hierarki dropdown di form lain, ikuti langkah berikut:

1. **Tambahkan dropdown parent dan child**
```html
<select id="parent_id" name="parent_id">
    <option value="">Pilih Parent</option>
</select>

<select id="child_id" name="child_id">
    <option value="">Pilih Child</option>
</select>
```

2. **Tambahkan event handler di JavaScript**
```javascript
$('#parent_id').on('change', function() {
    let parentId = $(this).val();
    let childSelect = $('#child_id');
    
    childSelect.empty().append('<option value="">Pilih Child</option>');
    
    if (parentId) {
        $.ajax({
            url: `/api/get-child/${parentId}`,
            type: 'GET',
            success: function(response) {
                response.forEach(function(item) {
                    childSelect.append(`<option value="${item.id}">${item.name}</option>`);
                });
            }
        });
    }
});
```

3. **Tambahkan route dan controller method**
```php
Route::get('/api/get-child/{parent_id}', [Controller::class, 'getChildByParent']);

public function getChildByParent($parent_id)
{
    $children = Child::where('parent_id', $parent_id)->get();
    return response()->json($children);
}
```

## Troubleshooting

1. **Dropdown tidak terisi**
   - Periksa route dan controller method
   - Periksa response dari AJAX call
   - Periksa console browser untuk error

2. **Data tidak sesuai**
   - Periksa relationship di model
   - Periksa query di controller
   - Periksa foreign key di database

3. **JavaScript error**
   - Periksa ID element di HTML
   - Periksa URL endpoint
   - Periksa CSRF token 