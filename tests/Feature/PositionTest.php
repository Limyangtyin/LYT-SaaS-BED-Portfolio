<?php

use App\Models\Position;
use Database\Factories\PositionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all positions', function () {
    $positions = Position::factory()->count(2)->create();

    $data = [
        'success' => true,
        'message' => 'Positions retrieved successfully',
        'data' => $positions->toArray()
    ];

    $response = $this->getJson('/api/v1/positions');

    $response->assertStatus(200)->assertJson($data);
});

it('can fetch a position', function () {
    $positions = Position::factory(2)->create();

    $data = [
        'success' => true,
        'message' => 'A position retrieved successfully',
        'data' => $positions[0]->toArray()
    ];

    $response = $this->getJson("api/v1/positions/{$positions[0]->id}");

    $response->assertStatus(200)->assertJson($data);
});


it('can add a new position', function () {
    $company = \App\Models\Company::factory()->create();

    $position = Position::factory()->make([
        'company_id' => $company->id,
    ]);

    $data = [
        'success' => true,
        'message' => 'New position added successfully',
        'data' => $position->toArray()
    ];

    $response = $this->postJson('/api/v1/positions', $position->toArray());

    $response->assertStatus(200)->assertJson($data);
});

it('can edit a position data', function () {
    $position = Position::factory()->create();

    $updatedData = [
        'position_title' => 'Position 1',
        'position_description' =>  'Do this... and do that...',
        'position_keywords' => 'PPP',
        'minimum_salary' => 2000,
        'maximum_salary' => 20000,
        'salary_currency' => 'AUD'
    ];

    $data = [
        'success' => true,
        'message' => "Position's data has been updated successfully",
        'data' => array_merge($position->toArray(), $updatedData, ['id' => $position->id])
    ];

    $response = $this->putJson("/api/v1/positions/{$position->id}/update", $updatedData);

    $response->assertStatus(200)->assertJson($data);

});

it('can delete a position', function () {
    $position = Position::factory()->create();

    $data = [
        'success' => true,
        'message' => "A position has been removed successfully",
        'data' => []
    ];

    $response = $this->deleteJson("/api/v1/positions/{$position->id}/delete");

    $response->assertStatus(200)->assertJson($data);

});

it('can restore a position', function () {
    $position = Position::factory()->create();

    $deletedata = [
        'success' => true,
        'message' => "A position has been removed successfully",
        'data' => []
    ];

    $restoredata = [
        'success' => true,
        'message' => "A position has been restore successfully",
        'data' => $position->toArray()
    ];

    $response = $this->deleteJson("/api/v1/positions/{$position->id}/delete");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->putJson("/api/v1/positions/{$position->id}/restore");
    $response->assertStatus(200)->assertJson($restoredata);

});
