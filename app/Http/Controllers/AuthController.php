<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    } //end __construct()


    public function allUsers()
    {
        $users = User::get();
        return response()->json($users->toArray());
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username'    => 'required',
                'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $token_validity = (24 * 60);

        $this->guard()->factory()->setTTL($token_validity);

        if (!$token = $this->guard()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    } //end login()


    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:users',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone'    => 'required|min:11',
                'birthdate' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        $user = User::create(
            array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            )
        );

        return response()->json(['message' => 'User created successfully', 'user' => $user]);
    } //end register()


    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'User logged out successfully']);
    } //end logout()


    public function getuserById(Request $request)
    {
        $user = User::find($request->id);

        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }

        if (!$user) return $this->returnError('404', 'Not Found');

        return response()->json([
            'message' => 'Get User Successfully',
            'user' => $user
        ]);
    } //end getusedById()

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'username' => 'required',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:6',
                'phone'    => 'required|min:11',
                'birthdate' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [$validator->errors()],
                422
            );
        }
        $user = User::find($request->id);
        if ($user) {
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->birthdate = $request->birthdate;
            $user->password = $request->password;
            $user->update();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Successfully updated.',
                    'user'   => $user,
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User not found',
                ]
            );
        }
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        if ($user) {
            $user->delete();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Successfully deleted.',
                    'user'   => $user,
                ]
            );
        } else {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'User not found',
                ]
            );
        }
    }

    public function profile()
    {
        return response()->json($this->guard()->user());
    } //end profile()

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    } //end refresh()

    protected function respondWithToken($token)
    {
        return response()->json(
            [
                'token'          => $token,
                'token_type'     => 'bearer',
                'token_validity' => ($this->guard()->factory()->getTTL() * 60) . ' - / 3600 convert to hour',
            ]
        );
    } //end respondWithToken()

    protected function guard()
    {
        return Auth::guard();
    } //end guard()

}
