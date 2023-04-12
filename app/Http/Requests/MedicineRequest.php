<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicineRequest extends FormRequest
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
    public function rules()
    {
        if (request()->isMethod("POST")) {
            // the unique rule is should be skip the soft deleted records
            $checkUnique = Rule::unique('medicines', 'name');
            $checkRequired = 'required';
        } elseif (request()->isMethod("PUT") || request()->isMethod("PATCH")) {
            $checkUnique =  Rule::unique('medicines', 'name')->ignore($this->medicine);
            $checkRequired = 'nullable';
        }

        return [
            'name'           => ['required', $checkUnique, 'string', 'max:255'],
            "description"    => ['required', 'string'],
            "type_id"        => ['required', 'exists:types,id'],
            "origin_id"      => ['required', 'exists:origins,id'],
            "price"          => ['required', 'numeric'],
            "quantity"       => ['required', 'numeric'],
            "image"          => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            "manufacture_at" => [$checkRequired, 'date'],
            "expire_at"      => [$checkRequired, 'date'],
            // "manufacture_at" => ['required', 'date', 'before:expire_at'],
            // "expire_at"      => ['required', 'date', 'after:manufacture_at'],
        ];
    }
    public function messages()
    {
        return [
            "name.required"           => "Name is required",
            "name.unique"             => "Name is already exists",
            "description.required"    => "Description is required",
            "type_id.required"        => "Type is required",
            "type_id.exists"          => "Type is not exists",
            "origin_id.required"      => "Origin is required",
            "origin_id.exists"        => "Origin is not exists",
            "price.required"          => "Price is required",
            "price.numeric"           => "Price must be a number",
            "quantity.required"       => "Quantity is required",
            "quantity.numeric"        => "Quantity must be a number",
            "image.image"             => "Image must be an image",
            "image.mimes"             => "Image must be a file of type: jpeg, png, jpg, gif, svg",
            "image.max"               => "Image may not be greater than 2048 kilobytes",
            "manufacture_at.required" => "Manufacture date is required",
            "manufacture_at.date"     => "Manufacture date must be a date",
            "manufacture_at.before"   => "Manufacture date must be a date before expire date",
            "expire_at.required"      => "Expire date is required",
            "expire_at.date"          => "Expire date must be a date",
            "expire_at.after"         => "Expire date must be a date after manufacture date",
        ];
    }
}
