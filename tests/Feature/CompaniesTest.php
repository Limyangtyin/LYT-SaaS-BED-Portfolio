<?php

use App\Models\Company;
use App\Models\User;
use Database\Factories\CompanyFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all companies', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $companies = Company::factory(2)->create([
        'user_id' => $user->id,
    ]);

    $data = [
        'success' => true,
        'message' => 'Company retrieved successfully',
        'data' => $companies->toArray()
    ];

    $response = $this->getJson('/api/v1/companies');
    $response->assertStatus(200)->assertJson($data);
});

it('can fetch a company', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $companies = Company::factory(2)->create([
        'user_id' => $user->id,
    ]);

    $data = [
        'success' => true,
        'message' => 'A company retrieved successfully',
        'data' => $companies[0]->toArray()
    ];

    $response = $this->getJson("api/v1/companies/{$companies[0]->id}");

    $response->assertStatus(200)->assertJson($data);
});


it('can add a new company', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    // Create one company instance without saving
    $company = Company::factory()->make([
        'user_id' => $user->id,
    ]);

    $data = [
        'success' => true,
        'message' => 'New company added successfully',
        'data' => $company->toArray()
    ];

    $response = $this->postJson('/api/v1/companies', $company->toArray());

    $response->assertStatus(201)->assertJson($data);
});

it('can edit a company data', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

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
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $company = Company::factory()->create();

    $data = [
        'success' => true,
        'message' => "A company has been removed successfully",
        'data' => []
    ];

    $response = $this->deleteJson("/api/v1/companies/{$company->id}");

    $response->assertStatus(200)->assertJson($data);

});


it('can restore a company', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $company = Company::factory()->create();

    $restoredata = [
        'success' => true,
        'message' => "A company has been restore successfully",
        'data' => $company->toArray()
    ];

    $company->delete();

    $response = $this->putJson("/api/v1/companies/{$company->id}/restore");
    $response->assertStatus(200)->assertJson($restoredata);

});


it('can remove a company from trash', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $company = Company::factory()->create();
    $company->delete();

    $permanentDeleteData = [
        'success' => true,
        'message' => "The company has been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/companies/{$company->id}/removeTrash");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});

