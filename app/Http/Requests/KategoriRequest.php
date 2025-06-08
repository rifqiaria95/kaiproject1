<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriRequest extends FormRequest
{
    /**
     * Menentukan apakah user diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Mendapatkan rules validasi yang berlaku untuk request.
     * Pisahkan validasi untuk insert (store) dan update.
     *
     * @return array
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            // Validasi untuk insert data baru
            return [
                'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
                'deskripsi'     => 'nullable|string',
            ];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Validasi untuk update data
            $id = $this->route('id');
            return [
                'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori,' . $id,
                'deskripsi'     => 'nullable|string',
            ];
        }

        // Default (jika method tidak dikenali)
        return [
            'nama_kategori' => 'required|string|max:255',
        ];
    }
}
