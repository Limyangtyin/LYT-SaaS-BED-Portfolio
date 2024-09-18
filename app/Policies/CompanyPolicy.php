<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    // Determine if the user can browse companies
    public function browse(User $user)
    {
        return in_array($user->type, ['client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can read a company
    public function read(User $user, Company $company)
    {
        return in_array($user->type, ['client', 'staff', 'administrator', 'super-user']) ||
            ($user->type === 'client' && $company->user_id === $user->id);
    }

    // Determine if the user can edit a company
    public function update(User $user, Company $company)
    {
        return ($user->type === 'client' && $company->user_id === $user->id) ||
            in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can add a company
    public function create(User $user)
    {
        return in_array($user->type, ['client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can delete a company
    public function delete(User $user, Company $company)
    {
        return ($user->type === 'client' && $company->user_id === $user->id) ||
            in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can search companies
    public function search(User $user)
    {
        return in_array($user->type, ['client', 'staff', 'administrator', 'super-user']);
    }

    // Determine if the user can restore a company
    public function restore(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can restore all companies
    public function restoreAll(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can restore all companies
    public function trash(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }

    // Determine if the user can restore all companies
    public function trashAll(User $user)
    {
        return in_array($user->type, ['staff', 'administrator', 'super-user']);
    }
}
