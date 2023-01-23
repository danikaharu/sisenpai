<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAttendanceRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'exists:users,id',
            'type' => 'in:1,2,3,4',
            'longitude' => 'required',
            'latitude' => 'required',
            'time' => 'required|date_format:H:i:s',
            'photo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'Pengguna tidak terdaftar',
            'longitude.required' => 'Longitude wajib diisi',
            'latitude.required' => 'Latitude wajib diisi',
            'time.required' => 'Waktu absen tidak boleh kosong',
            'time.date_format' => 'Maaf tidak sesuai format',
            'photo.required' => 'Selfie wajib',
        ];
    }
}
