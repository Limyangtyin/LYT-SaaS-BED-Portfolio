<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use App\Models\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::all();
        return ApiResponseClass::sendResponse(
            $positions, "Position retrieved successfully"
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
            'benefits' => 'nullable|string|max:500',
            'requirements' => 'nullable|string|max:500',
            'position_type' => 'required|in:permanent,contract,part-time,casual,internship',
        ]);

        $position = Position::create($validated);

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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        try{
            $position = Position::withTrashed()->findorfail($id);
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
}
