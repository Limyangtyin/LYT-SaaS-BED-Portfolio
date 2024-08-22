<?php

use App\Models\Companies;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);
testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all companies', function () {
    $data = [
        'success' => true,
        'message' => 'Companies retrieved successfully',
        'data' => [
            [
                'id' => 1,
                'name' => 'Company 1',
                'city' => 'Perth',
                'state' => 'WA',
                'country' => 'AUS',
                'logo' => null,
                'created_at' => '2024-07-01T00:00:00.000000Z',
                'updated_at' => '2024-07-01T00:00:00.000000Z',
            ],
            [
                'id' => 2,
                'name' => 'Company 2',
                'city' => 'Melbourne',
                'state' => 'VIC',
                'country' => 'AUS',
                'logo' => null,
                'created_at' => '2024-07-01T00:00:00.000000Z',
                'updated_at' => '2024-07-01T00:00:00.000000Z',
            ]
        ]
    ];

    foreach ($data['data'] as $company) {
        Companies::create($company);
    }

    $response = $this->getJson('/api/v1/companies');

    $response->assertStatus(200)->assertJson($data);
});

it('can fetch a company', function () {
    $data = [
        'success' => true,
        'message' => 'A company retrieved successfully',
        'data' => [
                'id' => 1,
                'name' => 'Company 1',
                'city' => 'Perth',
                'state' => 'WA',
                'country' => 'AUS',
                'logo' => null,
                'created_at' => '2024-07-01T00:00:00.000000Z',
                'updated_at' => '2024-07-01T00:00:00.000000Z',
        ]
    ];

    $company = Companies::create($data['data']);

    $response = $this->getJson("/api/v1/companies/{$company->id}");

    $response->assertStatus(200)->assertJson($data);
});


it('can add a new company', function () {
    $data = [
        'success' => true,
        'message' => 'New company added successfully',
        'data' => [
                'id' => 3,
                'name' => 'Company 3',
                'city' => 'Sydney',
                'state' => 'NSW',
                'country' => 'AUS',
                'logo' => null,
                'created_at' => '2024-07-01T00:00:00.000000Z',
                'updated_at' => '2024-07-01T00:00:00.000000Z',
        ]
    ];

    $company = $data['data'];

    $response = $this->postJson('/api/v1/companies', $company);

    $response->assertStatus(200)->assertJson($data);
});

it('can edit a company data', function () {
   $data = [
       'success' => true,
       'message' => "Company's data has been updated successfully",
       'data' => [
           'id' => 3,
           'name' => 'Company 3',
           'city' => 'Brisbane',
           'state' => 'QLD',
           'country' => 'AUS',
           'logo' => null,
           'created_at' => '2024-07-01T00:00:00.000000Z',
           'updated_at' => '2024-07-01T00:00:00.000000Z',
       ]
   ];

   $originaldata = Companies::create($data['data']);

   $updatedata = [
       'id' => 3,
       'name' => 'Company 3',
       'city' => 'Sydney',
       'state' => 'NSW',
       'country' => 'AUS',
       'logo' => null,
   ];

   $response = $this->putJson("/api/v1/companies/{$originaldata->id}", $updatedata);

   $response->assertStatus(200)->assertJson($data);

});

// ToDo: Soft-delete and Soft-undo test case







