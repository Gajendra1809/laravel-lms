<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\UserRequests\ResetPasswordRequest;
use App\Http\Requests\UserRequests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\JsonResponseTrait;
use App\Services\UserService;

/**
 * Controller for Auth endpoints.
 *
 * @package App\Http\Controllers
 *
 */
class AuthController extends Controller
{

    use JsonResponseTrait;

    /**
     * Constructor for the AuthController class.
     *
     * @param UserService $userService The user service dependency.
     */
    public function __construct(
     protected UserService $userService
    ){
    }

    /**
     * Attempt to log in a user.
     *
     * @param Request $request The login request data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function login(LoginRequest $request)
    {
        try {
            $response = $this->userService->login($request);
            if(!$response) {
                return $this->errorResponse('User not logged in', 'Wrong credentials', 401);
            }
            return $this->successResponse($response, 'User logged in successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse('User not logged in', $th->getMessage(), 500);
        }
    }

    /**
     * Logout the user from the application.
     *
     * @param Request $request The logout request data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function logout(Request $request){
        try {
            $request->user()->token()->revoke();
            return $this->successResponse('User logged out successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage());
        }
    }

    /**
     * Generates a reset token for the user, given their email address.
     *
     * @param Request $request The request data, containing the user's email address.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function requestToken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse('Validation error', $validator->errors(), 400);
            }
            $token = $this->userService->generateResetToken($request->email);
            return $this->successResponse($token, 'Token generated successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse('Error', $th->getMessage(), 500);
        }
    }

    /**
     * Resets the user's password.
     *
     * @param ResetPasswordRequest $request The request data, containing the user's email, token, and new password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $message = $this->userService->resetPassword($request);
            if(!$message) {
                return $this->errorResponse('Password reset failed', 'Invalid token or Expired token', 400);
            }
            return $this->successResponse($message, 'Password reset successfully');
        } catch (\Throwable $th) {
            return $this->errorResponse('Something went wrong', $th->getMessage(), 500);
        }
    }
}
