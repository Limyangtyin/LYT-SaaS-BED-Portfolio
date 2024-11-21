<?php

use App\Models\Company;
use App\Models\Position;
use App\Models\User;
use Database\Factories\PositionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all positions', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');
    Position::factory(2)->create();
    Position::paginate(10);
    $response = $this->getJson("/api/v1/positions");

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'advertising_start_date',
                    'advertising_end_date',
                    'position_title',
                    'position_description',
                    'position_keywords',
                    'minimum_salary',
                    'maximum_salary',
                    'salary_currency',
                    'company_id',
                    'user_id',
                    'benefits',
                    'requirements',
                    'position_type',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
            ],
            'first_page_url',
            'last_page_url',
            'next_page_url',
            'prev_page_url',
            'total',
            'per_page',
        ],
    ]);

    $response->assertJson([
        'success' => true,
        'message' => 'Positions retrieved successfully',
    ]);
});

it('can fetch a position', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');
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
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $company = Company::factory()->create([
        'user_id' => $user->id,
    ]);

    $user->company_id = $company->id;
    $user->save();

    $position = Position::factory()->make([
        'user_id' => $user->id,
        'company_id' => $user->company_id,
    ]);

    $data = [
        'success' => true,
        'message' => 'New position added successfully',
        'data' => $position->toArray()
    ];

    $response = $this->postJson('/api/v1/positions', $position->toArray());

    $response->assertStatus(201)->assertJson($data);
});

it('can edit a position data', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $position = Position::factory()->create();

    $updatedData = [
        'position_title' => 'Position 1',
        'position_description' =>  'Do this... and do that...',
        'position_keywords' => 'PPP',
        'minimum_salary' => 2000,
        'maximum_salary' => 20000,
        'salary_currency' => 'AUD',
        'advertising_start_date' => '2024-11-12',
        'advertising_end_date' => '2024-12-12',
        'position_type' => 'permanent',
    ];

    $data = [
        'success' => true,
        'message' => "Position's data has been updated successfully",
        'data' => array_merge($position->toArray(), $updatedData, ['id' => $position->id])
    ];

    $response = $this->putJson("/api/v1/positions/{$position->id}", $updatedData);

    $response->assertStatus(200)->assertJson($data);

});

it('can delete a position', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $position = Position::factory()->create();

    $data = [
        'success' => true,
        'message' => "A position has been removed successfully",
        'data' => []
    ];

    $response = $this->deleteJson("/api/v1/positions/{$position->id}");

    $response->assertStatus(200)->assertJson($data);

});


it('can restore a position', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $position = Position::factory()->create();

    $restoredata = [
        'success' => true,
        'message' => "A position has been restore successfully",
        'data' => $position->toArray()
    ];

    $this->delete("/api/v1/positions/{$position->id}");

    $response = $this->putJson("/api/v1/positions/{$position->id}/restore");
    $response->assertStatus(200)->assertJson($restoredata);

});


it('can remove a position from trash', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $position = Position::factory()->create();
    $position->delete();

    $permanentDeleteData = [
        'success' => true,
        'message' => "The position has been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/positions/{$position->id}/removeTrash");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});

