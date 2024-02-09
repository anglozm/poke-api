<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::orderBy('id', 'ASC')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        try {
            $pokemon = User::create($request->validated());

            return new UserResource($pokemon);
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => 'failure to store User',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): UserResource|JsonResponse
    {
        try {
            $user->update($request->validated());

            return new UserResource($user);
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => 'failure to update User',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return response()->json(
                null,
                Response::HTTP_NO_CONTENT
            );
        } catch (Exception $exception) {
            return response()->json(
                [
                    'message' => 'failure to delete Pokemon',
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
