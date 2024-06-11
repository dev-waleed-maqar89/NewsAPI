<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UserLoginRequest;
use App\Http\Requests\Api\V1\UserRegisterRequest;
use App\Http\Requests\Api\v1\UserUpdateRequest;
use App\Http\Resources\Main\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\ImageTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ImageTrait, ApiResponseTrait;
    public function register(UserRegisterRequest $request)
    {
        $img = '';
        if ($request->hasFile('image')) {
            $img = $this->singleImageUpload($request->file('image'), 'user', $request->email);
        }
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'image' => $img
            ]
        );
        $token = $user->createToken('token_for_' . $user->name)->plainTextToken;
        $user = new UserResource($user);
        $data = compact(['user', 'token']);
        $msg = 'Registered successfully';
        return $this->apiSuccess($data, $msg);
    }

    public function login(UserLoginRequest $request)
    {
        $user = Auth::attempt($request->only('email', 'password'));
        $user = User::where('email', $request->email)->first();
        $user = new UserResource($user);
        if (!$user) {
            return $this->apiError('some thing wrong', 403);
        } else {
            $token = $user->createToken('token_for_' . $user->name)->plainTextToken;
            $data = compact(['user', 'token']);
            $msg = 'Logged in successfully';
            return $this->apiSuccess($data, $msg);
        }
    }

    public function profile()
    {
        $user = new UserResource(Auth::user());
        return $this->apiSuccess(compact('user'));
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        $msg = 'Logged out successfully';
        return $this->apiSuccess([], $msg);
    }
    public function update(UserUpdateRequest $request)
    {
        $user = User::find(Auth::id());
        $name = $request->name ?? $user->name;
        $email = $request->email ?? $user->email;
        $password = $request->password ? bcrypt($request->password) : $user->password;
        $img = $user->image;
        if ($request->hasFile('image')) {
            $img = $this->singleImageUpload($request->file('image'), 'user', $user->email);
        }
        $user->update([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'image' => $img
        ]);
        $user = new UserResource(Auth::user());
        $data = compact(['user']);
        $msg = 'User has been updated successfully';
        return $this->apiSuccess($data, $msg);
    }
}