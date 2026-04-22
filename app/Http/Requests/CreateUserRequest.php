<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\DoctorDepartment;

/**
 * Class CreateUserRequest
 */
class CreateUserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = User::$rules;
    
        $rules['department_id'] = 'required';
    
        if ($this->department_id == 2) {
            if (DoctorDepartment::count() == 0) {
                $rules['department_id'] = [
                    'required',
                    function ($attribute, $value, $fail) {
                        $fail(__('messages.user.please_first_create_doctor_department'));
                    }
                ];
            } else {
                $rules['doctor_department'] = 'required';
            }
        }
    
        return $rules;
    }

    public function messages(): array
    {
        return [
            'phone.digits' => __('messages.user.phone_number_must_be_10_digits'),
            'email.regex' => __('messages.user.valid_email'),
            'photo.mimes' => __('messages.user.validate_image_type'),
        ];
    }
    
}
