<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kd_barcode'     => 'nullable|string|max:255',
            'nm_item'        => 'required|string|max:255',
            'jenis_item'     => 'required|string|max:255',
            'merk'           => 'required|string|max:255',
            'tgl_masuk_item' => 'required|string|max:255',
            'stok'           => 'nullable|integer|max:255',
            'hrg_beli'       => 'required|numeric',
            'hrg_jual'       => 'required|numeric',
            'rak'            => 'nullable|string|max:225',
            'deskripsi'      => 'nullable|string|max:255',
            'foto_item'      => 'nullable|mimes:jpg,png|max:2048',
            'id_satuan'      => 'required',
            'id_vendor'      => 'required',
        ];
    }
}
