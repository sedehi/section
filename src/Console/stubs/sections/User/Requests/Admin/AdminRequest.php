<?php

namespace App\Http\Controllers\User\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $action = $this->route()->getActionMethod();
        switch ($action) {
            case 'destroy':
                return [
                    'deleteId' => 'required|array',
                ];
                break;
            case 'store':
                return [
                    'first_name' => 'required|max:190',
                    'last_name'  => 'required|max:190',
                    'mobile'     => ['nullable', 'bail', 'numeric', 'iran_mobile', Rule::unique('admins')->whereNull('deleted_at')],
                    'email'      => ['required', 'email', Rule::unique('admins')->whereNull('deleted_at'), 'max:150'],
                    'password'   => 'required|min:6|max:150',
                    'role'       => 'required|array|min:1',
                ];
                break;
            case 'update':
                return [
                    'first_name' => 'required|max:190',
                    'last_name'  => 'required|max:190',
                    'mobile'     => [
                        'nullable',
                        'bail',
                        'numeric',
                        'iran_mobile',
                        Rule::unique('admins')->ignore($this->route()->parameter('admin'))->whereNull('deleted_at')
                    ],
                    'email'      => [
                        'required',
                        'email',
                        Rule::unique('admins')->ignore($this->route()->parameter('admin'))->whereNull('deleted_at'),
                        'max:150'
                    ],
                    'password'   => 'nullable|min:6|max:150',
                    'role'       => 'required|array|min:1',
                ];
                break;
        }

        return [];
    }

    public function messages()
    {
        return ['role.required' => 'حداقل باید یک گروه کاربری انتخاب شود.'];
    }
}
