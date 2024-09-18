<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return ApiResponseClass::sendResponse(
            $users, "Users retrieved successfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'given_name' => 'required|string|max:255',
            'family_name' => 'required|string|max:255',
            'email' => 'required|string|max:500',
//            'company_id' => 'required|exists:companies,id',
            'user_type' => 'required|in:client,staff,applicant',
            'status' => 'required|in:active,unconfirmed,suspended,banned,unknown',
            'password' => 'required'
        ]);

        try {
            $user = User::create($validated);
            return ApiResponseClass::sendResponse(
                $user, "New user added successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not created',
                'data' => []
            ], 404);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $user = User::findOrFail($id);
            return ApiResponseClass::sendResponse(
                $user, "A user retrieved successfully",
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => []
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update($request->all());

            return ApiResponseClass::sendResponse(
                $user, "User's data has been updated successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => []
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();
            return ApiResponseClass::sendResponse(
                $user, "A user has been removed successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => []
            ], 404);
        }
    }

    public function destroyAll()
    {
        try{
            $users = User::all();
            foreach ($users as $user) {
                $user->delete();
            }
            return ApiResponseClass::sendResponse(
                null, "All users have been removed successfully");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to delete all users',
                'data' => []
            ], 500);
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        try{
            $user = User::withTrashed()->findorfail($id);
            $user->restore();
            return ApiResponseClass::sendResponse(
                $user, "A user has been restore successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in trash',
                'data' => []
            ], 404);
        }
    }
    public function restoreAll() {
        try {
            $users = User::onlyTrashed()->get();
            foreach ($users as $user) {
                $user->restore();
            }
            return ApiResponseClass::sendResponse(
                null, "All users have been restore successfully");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to restore all users',
                'data' => []
            ], 500);
        }
    }

    public function removeFromTrash(int $id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->forceDelete();
            return ApiResponseClass::sendResponse(
                null, "The user has been permanently deleted from trash"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in trash',
                'data' => []
            ], 404);
        }
    }

    public function removeAllFromTrash()
    {
        try {
            $users = User::onlyTrashed()->get();
            foreach ($users as $user) {
                $user->forceDelete();
            }
            return ApiResponseClass::sendResponse(
                null, "All users have been permanently deleted from trash"
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to permanently delete all users',
                'data' => []
            ], 500);
        }
    }
}
