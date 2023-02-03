<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'in:1,2',
            'checkin_longitude' => 'required|numeric',
            'checkin_latitude' => 'required|numeric',
            'checkout_longitude' => 'numeric',
            'checkout_latitude' => 'numeric',
            'checkin_time' => 'required|date_format:H:i:s',
            'checkout_time' => 'date_format:H:i:s',
        ];
    }

    public function messages()
    {
        return [
            'type.in' => 'Jenis Absen tidak ada dalam daftar',
            'checkin_longitude.numeric' => 'Hanya bisa angka',
            'checkin_latitude.numeric' => 'Hanya bisa angka',
            'checkout_longitude.numeric' => 'Hanya bisa angka',
            'checkout_longitude.numeric' => 'Hanya bisa angka',
            'checkin_time.date_format' => 'Maaf, jam tidak sesuai format',
            'checkout_time.date_format' => 'Maaf, jam tidak sesuai format',
        ];
    }
}
