<?php

namespace App\Http\Controllers\Role\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'destroy':
                return [
                    'deleteId'   => 'required|array',
                    'deleteType' => 'required',
                ];
                break;
            case 'store':
            case 'update':
                return [
                    'title' => 'required',
                ];
                break;
            default:
                return [];
                break;
        }
    }
}
