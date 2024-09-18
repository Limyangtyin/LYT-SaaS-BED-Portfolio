<?php

use App\Models\Company;
use Database\Factories\CompanyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all companies', function () {
    $companies = Company::factory(2)->create();

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

   $this->assertDatabaseHas('companies', $updatedData);

});

it('can delete a company', function () {
    $company = Company::factory()->create();

    $data = [
        'success' => true,
        'message' => "A company has been removed successfully",
        'data' => []
    ];

    $response = $this->deleteJson("/api/v1/companies/{$company->id}/delete");

    $response->assertStatus(200)->assertJson($data);

});

it('can delete all companies', function () {
    $companies = Company::factory(3)->create();

    $data = [
        'success' => true,
        'message' => "All companies have been removed successfully",
        'data' => $companies->toArray()
    ];

    $response = $this->deleteJson("/api/v1/companies/delete-all");

    $response->assertStatus(200)->assertJson($data);

});

it('can restore a company', function () {
    $company = Company::factory()->create();

    $deletedata = [
        'success' => true,
        'message' => "A company has been removed successfully",
        'data' => []
    ];

    $restoredata = [
        'success' => true,
        'message' => "A company has been restore successfully",
        'data' => $company->toArray()
    ];

    $response = $this->deleteJson("/api/v1/companies/{$company->id}/delete");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->putJson("/api/v1/companies/{$company->id}/restore");
    $response->assertStatus(200)->assertJson($restoredata);

});

it('can restore all companies', function () {
    $companies = Company::factory(3)->create();

    $deletedata = [
        'success' => true,
        'message' => "All companies have been removed successfully",
        'data' => []
    ];

    $restoredata = [
        'success' => true,
        'message' => "All companies have been restore successfully",
        'data' => $companies->toArray()
    ];

    $response = $this->deleteJson("/api/v1/companies/delete-all");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->putJson("/api/v1/companies/restore-all");
    $response->assertStatus(200)->assertJson($restoredata);

});


it('can remove a company from trash', function () {
    $company = Company::factory()->create();

    $deletedata = [
        'success' => true,
        'message' => "A company has been removed successfully",
        'data' => []
    ];

    $permanentDeleteData = [
        'success' => true,
        'message' => "The company has been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/positions/{$company->id}/delete");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->deleteJson("/api/v1/positions/{$company->id}/removeTrash");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});

it('can remove all companies from trash', function () {
    $companies = Company::factory(3)->create();

    $deletedata = [
        'success' => true,
        'message' => "All companies have been removed successfully",
        'data' => []
    ];

    $permanentDeleteData = [
        'success' => true,
        'message' => "All companies have been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/companies/delete-all");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->deleteJson("/api/v1/companies/{removeTrash-all");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});
