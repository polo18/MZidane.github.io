<?php

namespace App\Http\Controllers\Back;

use App\Http\Requests\Back\UserRequest;
use App\Models\User;

class UserController extends ResourceController
{

    public function update($id)
    {
        $request = app()->make(UserRequest::class);

        $request->merge([
            'valid' => $request->has('valid'),
        ]);

        User::findOrFail($id)->update($request->all());

        return back()->with('ok', __('The user has been successfully updated'));
    }

    public function valid(User $user)
    {
        $user->valid = true;
        $user->save();

        return response()->json();
    }

    public function unvalid(User $user)
    {
        $user->valid = false;
        $user->save();

        return response()->json();
    }
}
