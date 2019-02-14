<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        switch ($this->route()->getName()) {
            case 'api.addStoreBranch.create':
                return [
                    'id' => 'required|numeric|max:1000|min:1',
                    'name' => 'string|max:100|min:1',
                    'parent'=> 'required|numeric|max:1000|min:1',
                ];

            case 'api.updateStoreBranch.update':
                return [
                    'id' => 'required|numeric|max:1000|min:1',
                    'name' => 'required|string|max:100|min:1',
                ];

            case 'api.moveStoreBranch.move':
                return [
                    'id' => 'required|numeric|max:1000|min:1',
                    'parent' => 'required|numeric|max:1000|min:1',
                ];

            case 'api.viewStoreBranch.view':
                return [
                    'id' => 'required|numeric|max:1000|min:1',
                ];

            case 'api.viewGroupStoreBranch.view':
                return [
                    'id' => 'required|numeric|max:1000|min:1',
                ];

            case 'api.viewAllStoreBranch.view':
                return [];

            case 'api.deleteGroupStoreBranch.delete':
                return [
                    'id' => 'required|numeric|max:1000|min:1',
                ];

        }
    }
}
