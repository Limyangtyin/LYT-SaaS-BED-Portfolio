<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
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
    public function destroy(string $id)
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
}
