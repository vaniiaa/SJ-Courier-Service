<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check(); // Hanya pengguna yang terautentikasi
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pickupAddress' => 'required|string|max:255',
            'pickupKecamatan' => 'required|string|max:100',
            'pickupLatitude' => 'required|numeric|between:-90,90',
            'pickupLongitude' => 'required|numeric|between:-180,180',
            'receiverName' => 'required|string|max:100',
            'receiverAddress' => 'required|string|max:255',
            'receiverKecamatan' => 'required|string|max:100',
            'receiverLatitude' => 'required|numeric|between:-90,90',
            'receiverLongitude' => 'required|numeric|between:-180,180',
            'receiverPhoneNumber' => 'required|string|regex:/^[0-9\+\-\(\)\s]{7,20}$/',
            'itemType' => 'required|string|max:100',
            'weightKG' => 'required|numeric|min:0.1|max:1000', // Sesuaikan max weight
            'notes' => 'nullable|string|max:500',
            
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'pickupAddress.required' => 'Alamat penjemputan wajib diisi.',
            'pickupKecamatan.required' => 'Kecamatan penjemputan wajib diisi.',
            'pickupLatitude.required' => 'Koordinat latitude penjemputan wajib ada.',
            'pickupLatitude.numeric' => 'Koordinat latitude penjemputan harus angka.',
            'pickupLatitude.between' => 'Koordinat latitude penjemputan tidak valid.',
            'pickupLongitude.required' => 'Koordinat longitude penjemputan wajib ada.',
            'pickupLongitude.numeric' => 'Koordinat longitude penjemputan harus angka.',
            'pickupLongitude.between' => 'Koordinat longitude penjemputan tidak valid.',
            'receiverName.required' => 'Nama penerima wajib diisi.',
            'receiverAddress.required' => 'Alamat penerima wajib diisi.',
            'receiverKecamatan.required' => 'Kecamatan penerima wajib diisi.',
            'receiverLatitude.required' => 'Koordinat latitude penerima wajib ada.',
            'receiverLatitude.numeric' => 'Koordinat latitude penerima harus angka.',
            'receiverLatitude.between' => 'Koordinat latitude penerima tidak valid.',
            'receiverLongitude.required' => 'Koordinat longitude penerima wajib ada.',
            'receiverLongitude.numeric' => 'Koordinat longitude penerima harus angka.',
            'receiverLongitude.between' => 'Koordinat longitude penerima tidak valid.',
            'receiverPhoneNumber.required' => 'Nomor telepon penerima wajib diisi.',
            'receiverPhoneNumber.regex' => 'Format nomor telepon penerima tidak valid.',
            'itemType.required' => 'Jenis barang wajib diisi.',
            'weightKG.required' => 'Berat barang wajib diisi.',
            'weightKG.numeric' => 'Berat barang harus berupa angka.',
            'weightKG.min' => 'Berat barang minimal 0.1 Kg.',
        ];
    }
}
