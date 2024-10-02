<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /*
     * Determine if the user can browse companies
     */
    public function browse(User $user)
    {
        return in_array($user->user_type, ['client', 'staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can read a company
     */
    public function read(User $user, Company $company)
    {
        if ($user->user_type === 'client') {
            return $company->user_id === $user->id;
        }

        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can edit a company
     */
    public function update(User $user, Company $company)
    {
        if ($user->user_type === 'client') {
            return $company->user_id === $user->id;
        }

        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can add a company
     */
    public function create(User $user)
    {
        return in_array($user->user_type, ['client', 'staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can delete a company
     */
    public function delete(User $user, Company $company)
    {
        if ($user->user_type === 'client') {
            return $company->user_id === $user->id;
        }

        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can search companies
     */
    public function search(User $user)
    {
        return in_array($user->user_type, ['client', 'staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can restore a company
     */
    public function restore(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can restore all companies
     */
    public function restoreAll(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can permanently delete a company
     */
    public function trash(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }

    /*
     * Determine if the user can permanently delete all companies
     */
    public function trashAll(User $user)
    {
        return in_array($user->user_type, ['staff', 'administrator', 'super-user']);
    }
}
