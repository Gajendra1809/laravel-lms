<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Facades\Auth;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Class UserService
 *
 * includes methods for user management
 *
 * @package App\Services
 *
 */
class UserService
{

    /**
     * Constructs a new instance of the UserService, injecting
     * the required UserRepository via the constructor.
     *
     * @param UserRepository $userRepository The user repository to be used.
     */
    public function __construct(
        protected UserRepository $userRepository
    ){
    }

    /**
     * Returns all users from the database.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(){
        return $this->userRepository->all();
    }

    /**
     * Search for users by name, email, or role.
     *
     * @param string $query The search query to search for.
     *
     * @return \Illuminate\Support\Collection
     */
    public function search($query){
        return $this->userRepository->search($query);
    }

    /**
     * Get a user by their UUID.
     *
     * @param string $uuid The UUID of the user to retrieve.
     *
     * @return \App\Models\User|false The user if found, false otherwise.
     */
    public function getUser(string $uuid){
        return $this->getByUuid($uuid);
    }

    /**
     * Retrieve a user by their UUID.
     *
     * @param string $uuid The UUID of the user to retrieve.
     *
     * @return \App\Models\User|false The user if found, false otherwise.
     */
    public function getByUuid(string $uuid){
        $user = $this->userRepository->findByUuid($uuid);
        if(!$user) {
            return false;
        }
        return $user;
    }

    /**
     * Create a new user with the given data.
     *
     * @param array $data The data to be used for creating the user.
     * The password will be hashed and the role will be set to "user".
     *
     * @return \App\Models\User The created user.
     */
    public function create(array $data){
        $data['password'] = Hash::make($data['password']);
        $data['role'] = UserRoleEnum::USER->value;
        return $this->userRepository->create($data);
    }

    /**
     * Update a user by their UUID with the given data.
     *
     * @param string $uuid The UUID of the user to update.
     * @param array $data The data to be used for updating the user.
     *
     * @return \App\Models\User
     */
    public function updateByUuid(string $uuid, array $data){
        return $this->userRepository->update($uuid, $data);
    }

    /**
     * Delete a user by their UUID.
     *
     * @param string $uuid The UUID of the user to delete.
     *
     * @return bool True if the user is successfully deleted, false otherwise.
     */
    public function deleteByUuid(string $uuid){
        return $this->userRepository->delete($uuid);
    }

    /**
     * Log in a user using the given request data.
     *
     * @param \Illuminate\Http\Request $request The request data, containing the user's email and password.
     *
     * @return array|false The user's access token and user data if the login is successful,
     * false otherwise.
     */
    public function login($request){
        $caredentials = $request->only('email', 'password');
        if(Auth::attempt($caredentials)){
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $token = $user->createToken('auth_token')->accessToken;
            return [
                "access_token" => $token,
                "token_type" => "Bearer",
                "User" => $user
            ];
        }else{
            return false;
        }
    }

    /**
     * Generates a reset token for the user with the given email address.
     *
     * @param string $email
     *
     * @return string The generated reset token.
     */
    public function generateResetToken($email){
        $token = rand(100000, 999999);
        PasswordReset::updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );
        return $token;
    }

    /**
     * Resets the user's password.
     *
     * @param Request $request The request data, containing the user's email address, reset token, and new password.
     *
     * @return bool Returns true if the password was reset successfully, false otherwise.
     */
    public function resetPassword($request){
        $reset = PasswordReset::where('email', $request->email)->first();
        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return false;
        }
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return false;
        }
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        PasswordReset::where('email', $request->email)->delete();
        return true;
    }
}
