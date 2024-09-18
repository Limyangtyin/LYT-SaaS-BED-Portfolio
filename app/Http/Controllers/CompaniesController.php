<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompaniesController extends Controller implements HasMiddleware
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
    public function index()
    {
        $companies = Company::all();
        return ApiResponseClass::sendResponse(
            $companies, "Company retrieved successfully"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        $company = Company::create($validated);

        return ApiResponseClass::sendResponse(
            $company, "New company added successfully"
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $company = Company::findOrFail($id);
            return ApiResponseClass::sendResponse(
                $company, "A company retrieved successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
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
            $company = Company::findOrFail($id);

            $company->update($request->all());

            return ApiResponseClass::sendResponse(
                $company, "Company's data has been updated successfully"
            );
            } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
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
            $company = Company::findOrFail($id);
            $company->delete();
            return ApiResponseClass::sendResponse(
                $company, "A company has been removed successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found',
                'data' => []
            ], 404);
        }
    }

    public function destroyAll()
    {
        try{
            $companies = Company::all();
            foreach ($companies as $company) {
                $company->delete();
            }
            return ApiResponseClass::sendResponse(
                null, "All companies have been removed successfully");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to delete all companies',
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
            $company = Company::withTrashed()->findorfail($id);
            $company->restore();
            return ApiResponseClass::sendResponse(
                $company, "A company has been restore successfully"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found in trash',
                'data' => []
            ], 404);
        }
    }

    public function restoreAll() {
        try {
            $companies = Company::onlyTrashed()->get();
            foreach ($companies as $company) {
                $company->restore();
            }
            return ApiResponseClass::sendResponse(
                null, "All companies have been restore successfully");
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to restore all companies',
                'data' => []
            ], 500);
        }
    }

    public function removeFromTrash(int $id)
    {
        try {
            $company = Company::onlyTrashed()->findOrFail($id);
            $company->forceDelete();
            return ApiResponseClass::sendResponse(
                null, "The company has been permanently deleted from trash"
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found in trash',
                'data' => []
            ], 404);
        }
    }

    public function removeAllFromTrash()
    {
        try {
            $companies = Company::onlyTrashed()->get();
            foreach ($companies as $company) {
                $company->forceDelete();
            }
            return ApiResponseClass::sendResponse(
                null, "All companies have been permanently deleted from trash"
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error occurred when trying to permanently delete all companies',
                'data' => []
            ], 500);
        }
    }
}
