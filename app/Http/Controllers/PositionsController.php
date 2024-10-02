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
use Illuminate\Support\Facades\Gate;

class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function index()
    {
        try {
            if (Gate::allows('browse')) {
                $positions = Position::paginate(10);
            } else {
                $positions = Position::limit(6)->get();
            }

            return ApiResponseClass::sendResponse(
                $positions, "Positions retrieved successfully", 200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to retrieve positions', 500
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function store(Request $request)
    {
        if (Gate::denies('create', Position::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try {
            $validated = $request->validate([
                'advertising_start_date' => 'required|date_format:Y-m-d',
                'advertising_end_date' => 'required|date_format:Y-m-d',
                'position_title' => 'required|string|max:255',
                'position_description' => 'required|string|max:500',
                'position_keywords' => 'nullable|string|max:255',
                'minimum_salary' => 'required|numeric|min:0',
                'maximum_salary' => 'required|numeric|min:0|gte:minimum_salary',
                'salary_currency' => 'required|string|size:3',
                'benefits' => 'nullable|string|max:500',
                'requirements' => 'nullable|string|max:500',
                'position_type' => 'required|in:permanent,contract,part-time,casual,internship',
            ]);

            $user = $request->user();

            $validated['user_id'] = $user->id;
            $validated['company_id'] = $user->company_id;

            $position = Position::create($validated);
            return ApiResponseClass::sendResponse(
                $position, "New position added successfully", 201
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Position not created', 500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function show(int $id)
    {
        if (Gate::denies('read', Position::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try {
            $position = Position::findOrFail($id);
            return ApiResponseClass::sendResponse(
                $position, "A position retrieved successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Position not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to retrieve position', 500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function update(Request $request, int $id)
    {
        try {
            $position = Position::findOrFail($id);
            if (Gate::denies('update', $position)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $position->update($request->all());
            return ApiResponseClass::sendResponse(
                $position, "Position's data has been updated successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Position not found', 404
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function destroy(int $id)
    {
        try{
            $position = Position::findOrFail($id);
            if (Gate::denies('delete', $position)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $position->delete();
            return ApiResponseClass::sendResponse(
                $position, "A position has been removed successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Position not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to remove position', 500
            );
        }
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     * .
     */
    public function restore(int $id)
    {
        try{
            $position = Position::onlyTrashed()->findorfail($id);
            if (Gate::denies('restore', $position)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $position->restore();
            return ApiResponseClass::sendResponse(
                $position, "A position has been restore successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Position not found in trash', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to restore position', 500
            );
        }
    }

    /**
     * Restore all soft-deleted resources from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function restoreAll()
    {
        if (Gate::denies('restoreAll', Position::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try {
            $positions = Position::onlyTrashed()->get();
            foreach ($positions as $position) {
                $position->restore();
            }

            return ApiResponseClass::sendResponse(
                null, "All positions have been restore successfully", 200);
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to restore positions', 500
            );
        }
    }

    /**
     * Permanently delete the specified resource from trash.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function removeFromTrash(int $id)
    {
        if (Gate::denies('trash', Position::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }
        try {
            $position = Position::onlyTrashed()->findOrFail($id);
            $position->forceDelete();
            return ApiResponseClass::sendResponse(
                null, "The position has been permanently deleted from trash", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Position not found in trash', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to permanently delete position from trash', 500
            );
        }
    }


    /**
     * Permanently delete all resources from trash.
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Positions
     */
    public function removeAllFromTrash()
    {
        if (Gate::denies('trashAll', Position::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try {
            $positions = Position::onlyTrashed()->get();
            foreach ($positions as $position) {
                $position->forceDelete();
            }
            return ApiResponseClass::sendResponse(
                null, "All positions have been permanently deleted from trash", 200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to permanently delete positions from trash', 500
            );
        }
    }
}
