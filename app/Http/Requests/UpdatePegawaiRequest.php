<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePegawaiRequest extends FormRequest
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
            'nm_pegawai'         => 'required|string|max:255',
            'foto_pegawai'       => 'nullable|mimes:jpg,png|max:2048',
            'tgl_lahir_pegawai'  => 'required|string|max:255',
            'tgl_masuk_pegawai'  => 'required|string|max:255',
            'tgl_keluar_pegawai' => 'nullable|string|max:255',
            'gaji_pegawai'       => 'required',
            'id_kota'            => 'required',
            'id_provinsi'        => 'required',
            'alamat_pegawai'     => 'required|string|max:255',
            'jenis_kelamin'      => 'required|string|max:255',
            'no_ktp_pegawai'     => 'required|string|max:255',
            'no_sim_pegawai'     => 'nullable|string|max:255',
            'no_npwp_pegawai'    => 'nullable|string|max:255',
            'no_telp_pegawai'    => 'nullable|string|max:255',
            'jabatan_pegawai'    => 'required|string|max:255',
            'active'             => 'required|in:0,1',
            'user_id'            => 'required|integer',
            'email'              => 'required|email|unique:users,email,' . $this->user_id . ',id'
        ];
    }
}
