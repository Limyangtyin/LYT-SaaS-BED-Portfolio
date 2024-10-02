<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can browse all users.
     */
    public function browse(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /**
     * Determine if the user can read a user.
     */
    public function read(User $user, User $targetUser)
    {
        if ($user->user_type === 'applicant' || $user->user_type === 'client') {
            return $targetUser->id === $user->id;
        }

        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /**
     * Determine if the user can update a user.
     */
    public function update(User $user, User $targetUser)
    {
        if ($user->user_type === 'applicant' || $user->user_type === 'client') {
            return $targetUser->id === $user->id;
        }

        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /**
     * Determine if the user can create a new user.
     */
    public function create(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /**
     * Determine if the user can delete a user.
     */
    public function delete(User $user, User $targetUser)
    {
        if ($user->user_type === 'applicant' || $user->user_type === 'client') {
            return $targetUser->id === $user->id;
        }

        if ($user->user_type === 'staff') {
            return $user->id !== $targetUser->id && $targetUser->user_type !== 'administrator' && $targetUser->user_type !== 'super-user';
        }

        if ($user->user_type === 'administrator') {
            return $user->id !== $targetUser->id && $targetUser->user_type !== 'super-user';
        }

        return $user->user_type === 'super-user' && $user->id !== $targetUser->id;
    }

    /**
     * Determine if the user can search for users.
     */
    public function search(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /**
     * Determine if the user can restore a user.
     */
    public function restore(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /**
     * Determine if the user can restore all users.
     */
    public function restoreAll(User $user)
    {
        return in_array($user->user_type, ['administrator', 'super-user']);
    }

    /**
     * Determine if the user can permanently delete a user.
     */
    public function trash(User $user)
    {
        return in_array($user->user_type, ['administrator', 'super-user']);
    }

    /**
     * Determine if the user can permanently delete all users.
     */
    public function trashAll(User $user)
    {
        return $user->user_type === 'super-user';
    }

}
