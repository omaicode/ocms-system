<?php

namespace Modules\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDatabaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'db_host'     => 'required|max:30',
            'db_port'     => 'required|numeric|max:65535',
            'db_database' => 'required|max:50',
            'db_username' => 'required|max:255',
            'db_password' => 'required|max:255',
            'admin_username' => 'required|min:6|max:30',
            'admin_password' => 'required|min:6|max:30',
            'admin_email'    => 'required|email'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
