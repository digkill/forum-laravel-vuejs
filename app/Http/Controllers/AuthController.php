<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private $guard;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
        $this->guard = "api";
    }


    /**  Get a JWT via given credentials.
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) { // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return $this->respondWithToken($token);
    }

    /**  Log the user out (Invalidate the token).
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**  Refresh a token.
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /** Get the token array structure.
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60,
            'user' => auth()->user()->name,
            'role' => auth()->user()->role
        ]);
    }

    /**  Get the authenticated User.
     * @return JsonResponse
     */
    public function me()
    {
        $user = JWTAuth::user();
        if (count((array)$user) > 0) {
            return response()->json(['status' => 'success', 'user' => $user, 'id' => $user->id]);
        } else {
            return response()->json(['status' => 'fail'], 401);
        }
    }

    public function signup(SignupRequest $request)
    {
        User::create($request->all());
        return $this->login($request);
    }
}
