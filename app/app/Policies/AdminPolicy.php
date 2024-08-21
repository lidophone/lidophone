<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    private bool $isAdmin;

    public function __construct()
    {
        $this->isAdmin = User::isAdmin();
    }

    public function viewAny(): bool
    {
        return $this->isAdmin;
    }

    public function view(): bool
    {
        return $this->isAdmin;
    }

    public function create(): bool
    {
        return $this->isAdmin;
    }

    public function update(): bool
    {
        return $this->isAdmin;
    }

    public function replicate(): bool
    {
        return $this->isAdmin;
    }

    public function delete(): bool
    {
        return $this->isAdmin;
    }
}
