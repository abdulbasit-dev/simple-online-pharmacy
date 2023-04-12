<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function profile()
    {
        return view('admin.profile.index');
    }

    public function updateProfile(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            "name" => ['sometimes', 'required'],
            "email" => ['sometimes', 'required', 'email', 'unique:users,email,' . auth()->id()],
            "address" => ['sometimes',],
            "phone" => ['sometimes', 'unique:users,phone,' . auth()->id()],
            // "phone_alt" => ['sometimes', 'unique:users,phone_alt,' . auth()->id()],
            "phone_alt" => ['sometimes'],
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(false, __('api.invalid_inputs'), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        try {
            // Retirve the validated input data 
            $validated = $validator->validated();

            $user = auth()->user();
            // Update the user
            $user->update($validated);

            if ($request->has('file')) {
                // delete old product image
                $mediaItems = $user->getMedia();

                if (count($mediaItems) > 0) {
                    $mediaItems->each(function ($item, $key) {
                        $item->delete();
                    });
                }

                // get file name from requets and find this file in the storage
                $filePath = storage_path('tmp/uploads/' . $request->file);

                // attach image to product
                // this will move the file from its current path to the storage path
                $user->addMedia($filePath)->usingName($request->name)->toMediaCollection();
            }

            $returnUser = $user->select('id', 'name', 'email', 'address', 'phone', 'phone_alt')->first();
            
            return $this->jsonResponse(true, __('messages.success'), Response::HTTP_OK, $returnUser);
        } catch (\Throwable $th) {
            return $this->jsonResponse(true, __('api.internal_server_error'), Response::HTTP_INTERNAL_SERVER_ERROR, null, showErrorMessage($th));
        }
    }

    public function updatePassword(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            "current_password" => ['required', 'string', 'min:8'],
            "new_password" => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(false, __('api.invalid_inputs'), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors());
        }

        try {
            //compare the current password with the password in the database
            if (!Hash::check($request->current_password, auth()->user()->password)) {
                return $this->jsonResponse(false, __('api.password_not_match'), Response::HTTP_UNAUTHORIZED);
            }

            // Update the user pasword
            auth()->user()->update([
                "password" => Hash::make($request->new_password)
            ]);

            return $this->jsonResponse(true, __('messages.success'), Response::HTTP_OK, auth()->user());
        } catch (\Throwable $th) {
            return $this->jsonResponse(true, __('api.internal_server_error'), Response::HTTP_INTERNAL_SERVER_ERROR, null, showErrorMessage($th));
        }
    }
}
