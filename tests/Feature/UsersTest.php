<?php

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all users', function () {
    $users = User::factory(2)->create();

    $data = [
        'success' => true,
        'message' => 'Users retrieved successfully',
        'data' => $users->toArray()
    ];

    $response = $this->getJson('/api/v1/users');

    $response->assertStatus(200)->assertJson($data);
});

it('can fetch a user', function () {
    $users = User::factory(2)->create();

    $data = [
        'success' => true,
        'message' => 'A user retrieved successfully',
        'data' => $users[0]->toArray()
    ];

    $response = $this->getJson("api/v1/users/{$users[0]->id}");

    $response->assertStatus(200)->assertJson($data);
});


it('can add a new user', function () {
    $company = \App\Models\Company::factory()->create();

    // Create one user instance without saving
    $user = User::factory()->make([
        'company_id' => $company->id,
    ]);

    $data = [
        'success' => true,
        'message' => 'New user added successfully',
        'data' => $user->toArray()
    ];

    $response = $this->postJson('/api/v1/users', $user->toArray());

    $response->assertStatus(200)->assertJson($data);
});

it('can edit a user profile', function () {
    $user = User::factory()->create();

    $updatedData = [
        'given_name' => 'User1',
        'user_type' =>  'staff',
        'status' => 'unconfirmed',
    ];

    $data = [
        'success' => true,
        'message' => "User's data has been updated successfully",
        'data' => array_merge($user ->toArray(), $updatedData, ['id' => $user->id])
    ];

    $response = $this->putJson("/api/v1/users/{$user->id}/update", $updatedData);

    $response->assertStatus(200)->assertJson($data);

    $this->assertDatabaseHas('users', $updatedData);

});

it('can delete a user', function () {
    $user = User::factory()->create();

    $data = [
        'success' => true,
        'message' => "A user has been removed successfully",
        'data' => []
    ];

    $response = $this->deleteJson("/api/v1/users/{$user->id}/delete");

    $response->assertStatus(200)->assertJson($data);

});

it('can delete all users', function () {
    $users = User::factory(3)->create();

    $data = [
        'success' => true,
        'message' => "All users have been removed successfully",
        'data' => $users->toArray()
    ];

    $response = $this->deleteJson("/api/v1/users/delete-all");

    $response->assertStatus(200)->assertJson($data);

});

it('can restore a user', function () {
    $user = User::factory()->create();

    $deletedata = [
        'success' => true,
        'message' => "A user has been removed successfully",
        'data' => []
    ];

    $restoredata = [
        'success' => true,
        'message' => "A user has been restore successfully",
        'data' => $user->toArray()
    ];

    $response = $this->deleteJson("/api/v1/users/{$user->id}/delete");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->putJson("/api/v1/users/{$user->id}/restore");
    $response->assertStatus(200)->assertJson($restoredata);

});

it('can restore all users', function () {
    $users = User::factory(3)->create();

    $deletedata = [
        'success' => true,
        'message' => "All users have been removed successfully",
        'data' => []
    ];

    $restoredata = [
        'success' => true,
        'message' => "All users have been restore successfully",
        'data' => $users->toArray()
    ];

    $response = $this->deleteJson("/api/v1/users/delete-all");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->putJson("/api/v1/users/restore-all");
    $response->assertStatus(200)->assertJson($restoredata);

});


it('can remove a user from trash', function () {
    $user = User::factory()->create();

    $deletedata = [
        'success' => true,
        'message' => "A user has been removed successfully",
        'data' => []
    ];

    $permanentDeleteData = [
        'success' => true,
        'message' => "The user has been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/positions/{$user->id}/delete");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->deleteJson("/api/v1/positions/{$user->id}/removeTrash");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});

it('can remove all users from trash', function () {
    $users = User::factory(3)->create();

    $deletedata = [
        'success' => true,
        'message' => "All users have been removed successfully",
        'data' => []
    ];

    $permanentDeleteData = [
        'success' => true,
        'message' => "All users have been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/users/delete-all");
    $response->assertStatus(200)->assertJson($deletedata);

    $response = $this->deleteJson("/api/v1/users/{removeTrash-all");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});
