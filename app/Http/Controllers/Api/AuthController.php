<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle user registration request.
     *
     * Validates the incoming request data, creates a new user, and returns a JSON response
     * containing the user data and an authentication token.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing user registration data.
     * @return \Illuminate\Http\JsonResponse       JSON response with user data and access token, or validation errors.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Handle user login request.
     *
     * Attempts to authenticate the user using the provided email and password.
     * If authentication fails, returns a 401 Unauthorized response.
     * On successful authentication, generates a new API token for the user and returns it
     * along with a success message and token type.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming HTTP request containing 'email' and 'password'.
     * @return \Illuminate\Http\JsonResponse      JSON response with access token or error message.
     */
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Logs out the authenticated user by revoking all of their API tokens.
     *
     * This method deletes all tokens associated with the currently authenticated user,
     * effectively logging them out from all devices and sessions.
     *
     * @return \Illuminate\Http\JsonResponse JSON response indicating successful logout.
     */
    public function logout() {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'logout success'
        ]);
    }
}
