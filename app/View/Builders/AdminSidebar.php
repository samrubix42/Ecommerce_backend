<?php

namespace App\View\Builders;

use Illuminate\Support\Collection;

class AdminSidebar
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public static function menu($user): self
    {
        return new self($user);
    }

    public function get(): Collection
    {
        return collect([
            (object) [
                'title' => 'Dashboard',
                'icon' => 'ri-dashboard-line',
                'url' => route('admin.dashboard'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object) [
                'title' => 'Categories',
                'icon' => 'ri-folder-line',
                'url' => route('admin.categories'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
          
        ]);
    }
}
