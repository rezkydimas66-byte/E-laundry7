<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'completion_date' => 'required|date|after_or_equal:today',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|exists:services,id',
            'services.*.qty' => 'required|integer|min:1',
        ];
    }

    public function messages():array
    {
        return[
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'customer_id.exists' => 'Data pelanggan tidak ditemukan dalam database.',
            'completion_date.required' => 'Tanggal estimasi selesai wajib diisi.',
            'completion_date.after_or_equal' => 'Tanggal estimasi tidak boleh sebelum hari ini.',
            'services.required' => 'Minimal pilih 1 layanan laundry.',
            'services.array' => 'Format data layanan tidak valid.',
            'services.*.service_id.required' => 'Layanan laundry wajib dipilih.',
            'services.*.service_id.exists' => 'Layanan yang dipilih tidak terdaftar.',
            'services.*.qty.required' => 'Jumlah kuantitas/berat wajib diisi.',
            'services.*.qty.min' => 'Jumlah kuantitas/berat minimal 1.',
        ];
    }
}
