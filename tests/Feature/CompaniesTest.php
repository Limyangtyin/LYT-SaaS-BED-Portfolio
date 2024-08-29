<?php

use App\Models\Company;
use Database\Factories\CompanyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all companies', function () {
    $companies = Company::factory()->count(2)->create();

    $data = [
        'success' => true,
        'message' => 'Company retrieved successfully',
        'data' => $companies->toArray()
    ];

    $response = $this->getJson('/api/v1/companies');

    $response->assertStatus(200)->assertJson($data);
});

it('can fetch a company', function () {
    $companies = Company::factory(2)->create();

    $data = [
        'success' => true,
        'message' => 'A company retrieved successfully',
        'data' => $companies[0]->toArray()
    ];

    $response = $this->getJson("api/v1/companies/{$companies[0]->id}");

    $response->assertStatus(200)->assertJson($data);
});


it('can add a new company', function () {
    // Create one company instance without saving
    $company = Company::factory()->make();

    $data = [
        'success' => true,
        'message' => 'New company added successfully',
        'data' => $company->toArray()
    ];

    $response = $this->postJson('/api/v1/companies', $company->toArray());

    $response->assertStatus(200)->assertJson($data);
});

it('can edit a company data', function () {
    $company = Company::factory()->create();

    $updatedData = [
        'name' => 'Company 1',
        'city' =>  'Perth',
        'state' => 'WA',
        'country' => 'Australia'
    ];

    $data = [
        'success' => true,
        'message' => "Company's data has been updated successfully",
        'data' => array_merge($company->toArray(), $updatedData, ['id' => $company->id])
    ];

   $response = $this->putJson("/api/v1/companies/{$company->id}", $updatedData);

   $response->assertStatus(200)->assertJson($data);

});

// ToDo: Soft-delete and Soft-undo test case







