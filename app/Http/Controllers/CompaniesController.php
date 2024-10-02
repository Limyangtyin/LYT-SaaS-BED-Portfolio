<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompaniesController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function index()
    {
        if (Gate::denies('browse', Company::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try{
            $companies = Company::all();

            return ApiResponseClass::sendResponse(
                $companies, "Company retrieved successfully", 200
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to retrieved companies', 500
            );
        }

    }

    /**
     * Store a newly created resource in storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function store(Request $request)
    {
        if (Gate::denies('create', Company::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'logo' => 'nullable|string|max:255',
            ]);

            $user = $request->user();

            $validated['user_id'] = $user->id;

            $company = Company::create($validated);

            $user->company_id = $company->id;
            $user->save();

            return ApiResponseClass::sendResponse(
                $company, "New company added successfully", 201
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to create company', 500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function show(int $id)
    {
        try {
            $company = Company::findOrFail($id);
            if (Gate::denies('read', $company)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            return ApiResponseClass::sendResponse(
                $company, "A company retrieved successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Company not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to retrieved company', 500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function update(Request $request, int $id)
    {
        try {
            $company = Company::findOrFail($id);
            if (Gate::denies('update', $company)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $company->update($request->all());

            return ApiResponseClass::sendResponse(
                $company, "Company's data has been updated successfully", 200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Company not found', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to update company', 500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function destroy(int $id)
    {
        try{
            $company = Company::findOrFail($id);
            if (Gate::denies('delete', $company)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }

            $company->delete();
            return ApiResponseClass::sendResponse(
                $company, "A company has been removed successfully"
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Company not found', 404);
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to remove company', 500
            );
        }
    }


    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function restore(int $id)
    {
        if (Gate::denies('restore', Company::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try{
            $company = Company::withTrashed()->findorfail($id);

            $company->restore();
            return ApiResponseClass::sendResponse(
                $company, "A company has been restore successfully"
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Company not found in trash', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to restore company', 500
            );
        }
    }

    /**
     * Restore all soft-deleted resources from storage
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function restoreAll()
    {
        if (Gate::denies('restoreAll', Company::class)) {
            return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
        }

        try {
            $companies = Company::onlyTrashed()->get();
            foreach ($companies as $company) {
                $company->restore();
            }
            return ApiResponseClass::sendResponse(
                null, "All companies have been restore successfully");
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to restore companies', 500
            );
        }
    }

    /**
     * Permanently delete the specified resource from trash
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function removeFromTrash(int $id)
    {
        try {
            $company = Company::onlyTrashed()->findOrFail($id);
            if (Gate::denies('trash', $company)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }
            $company->forceDelete();
            return ApiResponseClass::sendResponse(
                null, "The company has been permanently deleted from trash"
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseClass::sendResponse(
                null, 'Company not found in trash', 404
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to permanently delete company from trash', 500
            );
        }
    }

    /**
     * Permanently delete all resource from trash
     *
     * @return \Illuminate\Http\JsonResponse
     * @group Companies
     */
    public function removeAllFromTrash()
    {
        try {
            $companies = Company::onlyTrashed()->get();
            if (Gate::denies('trashAll', $companies)) {
                return ApiResponseClass::sendResponse(null, 'Unauthorized', 403);
            }
            foreach ($companies as $company) {
                $company->forceDelete();
            }
            return ApiResponseClass::sendResponse(
                null, "All companies have been permanently deleted from trash"
            );
        } catch (\Exception $e) {
            return ApiResponseClass::sendResponse(
                null, 'Failed to permanently delete companies from trash', 500
            );
        }
    }
}
