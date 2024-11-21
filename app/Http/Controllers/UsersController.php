<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function index()
    {
        if (Gate::denies('browse', User::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try{
            $users = User::all();
            return ApiResponseClass::sendResponse(
                $users, "Users retrieved successfully", 200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, "Failed to retrieve users", 500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function store(Request $request)
    {
        if (Gate::denies('create', User::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }
        try {
            $validated = $request->validate([
                'nickname' => 'required|string|max:255',
                'given_name' => 'required|string|max:255',
                'family_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:500',
                'company_id' => 'nullable|exists:companies,id',
                'user_type' => 'required|in:client,staff,applicant,administrator,super-user',
                'status' => 'required|in:active,unconfirmed,suspended,banned,unknown',
                'password' => 'required|string|min:8'
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            return ApiResponseClass::sendResponse(
                $user, "New user added successfully", 201
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, "Failed to create user", 500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function show(int $id)
    {
        try {
            $user = User::findOrFail($id);
            if (Gate::denies('read', $user)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }
            return ApiResponseClass::sendResponse(
                $user, "A user retrieved successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'User not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to retrieve user', 500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function update(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);
            if (Gate::denies('update', $user)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $user->update($request->all());
            return ApiResponseClass::sendResponse(
                $user, "User's data has been updated successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'User not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to update user', 500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function destroy(int $id)
    {
        try{
            $user = User::findOrFail($id);
            if (Gate::denies('delete', $user)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $user->delete();
            return ApiResponseClass::sendResponse(
                $user, "A user has been removed successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'User not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to delete user', 500
            );
        }
    }


    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function restore(int $id)
    {
        if (Gate::denies('restore', User::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try{
            $user = User::withTrashed()->findorfail($id);
            $user->restore();
            return ApiResponseClass::sendResponse(
                $user, "A user has been restore successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'User not found in trash', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to restore user', 500
            );
        }
    }

    /**
     * Restore all users from storage
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function restoreAll()
    {
        if (Gate::denies('restoreAll', User::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }
        try {
            $users = User::onlyTrashed()->get();
            foreach ($users as $user) {
                $user->restore();
            }
            return ApiResponseClass::sendResponse(
                null, "All users have been restore successfully", 200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to restore all users', 500
            );
        }
    }

    /**
     * Remove the specified user from trash
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function removeFromTrash(int $id)
    {


        try {
            $user = User::onlyTrashed()->findOrFail($id);

            if (Gate::denies('trash', User::class)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }
            $user->forceDelete();
            return ApiResponseClass::sendResponse(
                null, "The user has been permanently deleted from trash", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'User not found in trash', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to permanently delete a user', 500
            );
        }
    }

    /**
     * Remove all users from trash
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Users
     */
    public function removeAllFromTrash()
    {
        if (Gate::denies('trashAll', User::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try {
            $users = User::onlyTrashed()->get();
            foreach ($users as $user) {
                $user->forceDelete();
            }
            return ApiResponseClass::sendResponse(
                null, "All users have been permanently deleted from trash", 200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to permanently delete all users', 500
            );
        }
    }
}
