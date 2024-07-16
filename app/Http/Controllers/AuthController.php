<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Validator;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    protected $companyController;

    public function __construct(CompanyController $companyController)
    {
        $this->companyController = $companyController;
    }

    public function register(RegisterRequest $request)
    {

        try {
            // init the transaction on Database
            DB::beginTransaction();
            // create a new instance of user model
            $newUser = new User;
            // assign the properties
            $newUser->email = $request->email;
            $newUser->password = bcrypt($request->password);
            $newUser->phone_number = $request->phone_number;
            $newUser->document_type = $request->document_type;
            $newUser->address = $request->address;
            $newUser->role_id = $request->role_id;
            // save the user
            $newUser->save();
            $additional_information = null;

            // validating if the role id passed in request is 1
            if ($request->role_id == 1) {
                // the company data ($request), will be all keys of this request.
                $companyData = $request->all();
                // add the user id property
                // the user id will be the user that was created on this function.
                $companyData['user_id'] = $newUser->id;
                // init the try catch to get the exceptions throw by companyController
                try {
                    // execute the method store of companyController
                    // this method throw exceptions if the validation is incorrect
                    $company = $this->companyController->store($companyData);
                    // the addtional information will be the info about the company created using method store.
                    $additional_information = $company;
                    // get the exception validation exception
                } catch (ValidationException $e) {
                    // rollback the querys that were executed
                    DB::rollBack();
                    // return the errors, and HTTP code 422 (unprocesable content)
                    return response()->json(['errors' => $e->validator->errors()], 422);
                }
            }

            
            // save the querys that were executed on database.
            DB::commit();

            // return the user created data and company created data by company controller using store method
            // http status code 201 created
            return response()->json([
                'user' => new UserResource($newUser->load('role')),
                'additional_information' => $additional_information

            ], 201);
            // get the exception, only if a server error has ocurred.
        }  catch (\Exception $e) {
            // rollback the queries that were executed
            DB::rollBack();
            return response()->json(['error' => 'Server error', 'status_code' => 500], 500);
        }
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        // init try catch code
        try {
            // the credentials will be the email and password passed on request.
            $credentials = $request->only('email', 'password');

            // attemp to login user using $credentials
            // $token will be the boolean value obtained in the attemp of login
            if (!$token = auth()->attempt($credentials)) {
                // if $token is false, return the message invalid credentials
                // http status code 401 unauthorized.
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // else, return the jwt, token using the method respondWithToken
            return $this->respondWithToken($token);
            // get the exception, only if a server error has ocurred.
        } catch (\Exception $e) {
            // return the error
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
