<?php

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Spatie\PestPluginTestTime\testTime;

uses(RefreshDatabase::class);

testTime()->freeze('2024-07-01 00:00:00');

it('can fetch all users', function () {
    $users = User::factory(3)->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($users[0], 'sanctum');

    $data = [
        'success' => true,
        'message' => 'Users retrieved successfully',
        'data' => $users->toArray()
    ];

    $response = $this->getJson('/api/v1/users');

    $response->assertStatus(200)->assertJson($data);
});

it('can fetch a user', function () {
    $users = User::factory(3)->create([
        'user_type' => 'staff',
    ]);
    $this->actingAs($users[0], 'sanctum');

    $data = [
        'success' => true,
        'message' => 'A user retrieved successfully',
        'data' => $users[2]->toArray()
    ];

    $response = $this->getJson("api/v1/users/{$users[2]->id}");

    $response->assertStatus(200)->assertJson($data);
});


it('can add a new user', function () {
    // Create a super-user for authentication
    $admin = User::factory()->create([
        'user_type' => 'super-user',
    ]);

    $this->actingAs($admin, 'sanctum');

    $userData = [
        'nickname' => 'testuser',
        'given_name' => 'Test',
        'family_name' => 'User',
        'email' => 'testuser@example.com',
        'user_type' => 'client',
        'status' => 'active',
        'password' => 'Password123',
    ];

    $response = $this->postJson('/api/v1/users', $userData);

    $expectedData = [
        'success' => true,
        'message' => 'New user added successfully',
        'data' => [
            'nickname' => $userData['nickname'],
            'given_name' => $userData['given_name'],
            'family_name' => $userData['family_name'],
            'email' => $userData['email'],
            'user_type' => $userData['user_type'],
            'status' => $userData['status'],
        ]
    ];

    $response->assertStatus(201)->assertJson($expectedData);

    $this->assertDatabaseHas('users', [
        'email' => $userData['email'],
    ]);
});


it('can edit a user profile', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $user1 = User::factory()->create();

    $updatedData = [
        'given_name' => 'User1',
        'user_type' =>  'staff',
        'status' => 'unconfirmed',
    ];

    $data = [
        'success' => true,
        'message' => "User's data has been updated successfully",
        'data' => array_merge($user1 ->toArray(), $updatedData, ['id' => $user1->id])
    ];

    $response = $this->putJson("/api/v1/users/{$user1->id}", $updatedData);

    $response->assertStatus(200)->assertJson($data);

    $this->assertDatabaseHas('users', $updatedData);

});

it('can delete a user', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $user1 = User::factory()->create();

    $data = [
        'success' => true,
        'message' => "A user has been removed successfully",
        'data' => []
    ];

    $response = $this->deleteJson("/api/v1/users/{$user1->id}");

    $response->assertStatus(200)->assertJson($data);

});


it('can restore a user', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $user1 = User::factory()->create();


    $restoredata = [
        'success' => true,
        'message' => "A user has been restore successfully",
        'data' => $user->toArray()
    ];

    $user1->delete();

    $response = $this->putJson("/api/v1/users/{$user->id}/restore");
    $response->assertStatus(200)->assertJson($restoredata);

});


it('can remove a user from trash', function () {
    $user = User::factory()->create([
        'user_type' => 'super-user',
    ]);
    $this->actingAs($user, 'sanctum');

    $user1 = User::factory()->create();

    $user1->delete();

    $permanentDeleteData = [
        'success' => true,
        'message' => "The user has been permanently deleted from trash",
        'data' => null
    ];

    $response = $this->deleteJson("/api/v1/users/{$user1->id}/removeTrash");
    $response->assertStatus(200)->assertJson($permanentDeleteData);

});

