<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
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
            'npk' => 'required|string|max:5|exists:master_karyawan,npk',
            'kode' => 'required|string|max:4|exists:master_item,kode',
            'tanggal_transaksi' => 'required|date',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'bayar' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'npk.required' => 'NPK harus diisi',
            'npk.exists' => 'NPK tidak ditemukan',
            'kode.required' => 'Kode item harus diisi',
            'kode.exists' => 'Kode item tidak ditemukan',
            'qty.required' => 'Quantity harus diisi',
            'qty.min' => 'Quantity minimal 1',
            'harga.required' => 'Harga harus diisi',
            'harga.min' => 'Harga tidak boleh negatif'
        ];
    }
}
