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
                'icon' => 'ri-layout-grid-line',
                'url' => route('admin.dashboard'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object) [
                'title' => 'Categories',
                'icon' => 'ri-folder-open-line',
                'url' => route('admin.categories'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object) [
                'title' => 'Products',
                'icon' => 'ri-box-3-line',
                'url' => 'javascript:void(0)',
                'hasSubmenu' => true,
                'submenu' => [
                    (object) [
                        'title' => 'All Products',
                        'icon' => 'ri-list-view',
                        'url' => route('admin.products.index'),
                    ],
                    (object) [
                        'title' => 'New Product',
                        'icon' => 'ri-add-circle-line',
                        'url' => route('admin.add-product'),
                    ],
                    (object) [
                        'title' => 'Attributes',
                        'icon' => 'ri-settings-4-line',
                        'url' => route('admin.attributes'),
                    ],
                ],
            ],
             (object) [
                'title' => 'Inventory',
                'icon' => 'ri-database-2-line',
                'url' => route('admin.stock'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object) [
                'title' => 'Coupons',
                'icon' => 'ri-ticket-2-line',
                'url' => route('admin.coupons'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
           
        ]);
    }
}
