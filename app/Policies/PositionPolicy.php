<?php

namespace App\Policies;

use App\Models\Position;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PositionPolicy
{
    // Determine if the user can browse positions
    public function browse(User $user)
    {
        return in_array($user->type, ['applicant', 'client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can read a position
    public function read(User $user, Position $position)
    {
        return $user->type === 'applicant' ||
            in_array($user->type, ['client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can edit a position
    public function update(User $user, Position $position)
    {
        return ($user->type === 'client' && $position->user_id === $user->id) ||
            in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can create a position
    public function create(User $user)
    {
        return in_array($user->type, ['client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can delete a position
    public function delete(User $user, Position $position)
    {
        return ($user->type === 'client' && $position->user_id === $user->id) ||
            in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can search positions
    public function search(User $user)
    {
        return in_array($user->type, ['applicant', 'client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can restore a position
    public function restore(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can restore all positions
    public function restoreAll(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can permanently delete a position from trash
    public function trash(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can permanently delete all positions from trash
    public function trashAll(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }
}
