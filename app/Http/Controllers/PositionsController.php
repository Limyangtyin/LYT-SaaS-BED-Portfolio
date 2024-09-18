<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use App\Models\Position;
use http\Env\Response;
use http\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PositionsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        // TODO: Implement middleware() method.
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $positions = Position::paginate(6);
        } else {
            $positions = Position::limit(6)->get();
        }

        return ApiResponseClass::sendResponse(
            $positions, "Positions retrieved successfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'advertising_start_date' => 'required|date_format:Y-m-d',
            'advertising_end_date' => 'required|date_format:Y-m-d',
            'position_title' => 'required|string|max:255',
            'position_description' => 'required|string|max:500',
            'position_keywords' => 'nullable|string|max:255',
            'minimum_salary' => 'required|numeric|min:0',
            'maximum_salary' => 'required|numeric|min:0|gte:minimum_salary',
            'salary_currency' => 'required|string|size:3',
            'company_id' => 'required|exists:companies,id',
//            'user_id' => 'required|exists:users,id',
            'benefits' => 'nullable|string|max:500',
            'requirements' => 'nullable|string|max:500',
            'position_type' => 'required|in:permanent,contract,part-time,casual,internship',
        ]);
        $position = $request->user()->position()->create($validated);

        return ApiResponseClass::sendResponse(
            $position, "New position added successfully"
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $position = Position::findOrFail($id);
            return ApiResponseClass::sendResponse(
                $position, "A position retrieved successfully",
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
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
            $position = Position::findOrFail($id);

            $position->update($request->all());

            return ApiResponseClass::sendResponse(
                $position, "Position's data has been updated successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
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
            $position = Position::findOrFail($id);
            $position->delete();
            return ApiResponseClass::sendResponse(
                $position, "A position has been removed successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found',
                'data' => []
            ], 404);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAll()
    {
        try{
            $positions = Position::all();
            foreach ($positions as $position) {
                $position->delete();
            }
            return ApiResponseClass::sendResponse(
                null, "All positions have been removed successfully");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to delete all positions',
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
            $position = Position::onlyTrashed()->findorfail($id);
            $position->restore();
            return ApiResponseClass::sendResponse(
                $position, "A position has been restore successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found in trash',
                'data' => []
            ], 404);
        }
    }

    public function restoreAll() {
        try {
            $positions = Position::onlyTrashed()->get();
            foreach ($positions as $position) {
                $position->restore();
            }
            return ApiResponseClass::sendResponse(
                null, "All positions have been restore successfully");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to restore all positions',
                'data' => []
            ], 500);
        }
    }

    public function removeFromTrash(int $id)
    {
        try {
            $position = Position::onlyTrashed()->findOrFail($id);
            $position->forceDelete();
            return ApiResponseClass::sendResponse(
                null, "The position has been permanently deleted from trash"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Position not found in trash',
                'data' => []
            ], 404);
        }
    }

    public function removeAllFromTrash()
    {
        try {
            $positions = Position::onlyTrashed()->get();
            foreach ($positions as $position) {
                $position->forceDelete();
            }
            return ApiResponseClass::sendResponse(
                null, "All positions have been permanently deleted from trash"
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to permanently delete all positions',
                'data' => []
            ], 500);
        }
    }
}
