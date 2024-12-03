<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequests\UserCreateRequest;
use App\Http\Requests\UserRequests\UserUpdateRequest;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Traits\JsonResponseTrait;
use App\Models\User;
use App\Services\LoggingService;

/**
 * Controller for the User model.
 *
 * @package App\Http\Controllers
 *
 */
class UserController extends Controller
{
    use JsonResponseTrait;

    /**
     * Constructor for the UserController class.
     *
     * @param UserService $userService The user service dependency.
     */
    public function __construct(
     protected UserService $userService,
     protected LoggingService $logService
    ){
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function index(Request $request)
    {
        try {
            $this->authorize('view', User::class);
            $data = $this->userService->all();
            return $this->successResponse($data, 'Users retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\UserCreateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function store(UserCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $response = $this->userService->create($data);
            return $this->successResponse($response, 'User created successfully', 201);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Display the specified user.
     *
     * @param string $uuid The user uuid.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function show(Request $request, string $uuid)
    {
        try {
            $this->authorize('view', User::class);
            $data = $this->userService->getUser($uuid);
            if(!$data) {
                return $this->errorResponse('User not retrieved', 'Uuid not found', 404);
            }
            return $this->successResponse($data, 'User retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  string  $uuid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function update(UserUpdateRequest $request, string $uuid)
    {
        try {
            $this->authorize('cud', User::class);
            $data = $request->validated();
            $response = $this->userService->updateByUuid($uuid, $data);
            if(!$response) {
                return $this->errorResponse('User not updated', 'User not found', 404);
            }
            return $this->successResponse($response, 'User updated successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Delete the specified user from storage.
     *
     * @param string $uuid The UUID of the user to be deleted.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function destroy(Request $request, string $uuid)
    {
        try {
            $this->authorize('cud', User::class);
            $response = $this->userService->deleteByUuid($uuid);
            if(!$response) {
                return $this->errorResponse('User not deleted', 'User not found', 404);
            }
            return $this->successResponse($response, 'User deleted successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Search for users based on a query data.
     *
     * @param Request $request The search request containing the query data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function search(Request $request){
        try {
            $this->authorize('view', User::class);
            $data = $this->userService->search($request->search);
            return $this->successResponse($data, 'Users retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), $request->all());
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Retrieve the number of active users on a weekly basis.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the weekly active users data.
     */
    protected function weeklyActiveUsers(){
        try {
            $response = $this->userService->weeklyActiveUsers();
            return $this->successResponse($response, 'Weekly active users retrieved successfully', 200);
        } catch (\Throwable $th) {
            $this->logService->logError($th->getMessage(), null);
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage(), 500);
        }
    }

    
}
