<?php

namespace Alexusmai\EasySettings\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class GroupSchemaRequest extends FormRequest
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
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'groupTitle'    => 'required|max:255',
            'name.*'        => 'required|alpha_dash|max:255',
            'type.*'        => 'required|alpha',
            'rules.*'       => 'nullable',
            'description.*' => 'required|max:255',
        ];

        // if edit
        if ( $request->has('id') ){
            // add rule
            $rules['id'] = 'required|integer';
        } else {
            // add rule
            $rules['groupName'] = 'required|alpha_dash|unique:easy_settings,name|max:255';
        }

        return $rules;
    }
}
