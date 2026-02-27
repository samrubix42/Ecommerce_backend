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
            (object) [
                'title' => 'Products',
                'icon' => 'ri-product-hunt-line',
                'url' => 'javascript:void(0)',
                'hasSubmenu' => true,
                'submenu' => [

                    (object) [
                        'title' => 'Manage Attributes',
                        'icon' => 'ri-list-check',
                        'url' => route('admin.attributes'),
                    ],
                    (object) [
                        'title' => 'Product List',
                        'icon' => 'ri-list-check',
                        'url' => route('admin.products.index'),
                    ],
                    (object) [
                        'title' => 'Add Product',
                        'icon' => 'ri-add-line',
                        'url' => route('admin.add-product'),
                    ],
                ],
            ],
            (object) [
                'title' => 'Coupons',
                'icon' => 'ri-coupon-line',
                'url' => route('admin.coupons'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object) [
                'title' => 'Stock',
                'icon' => 'ri-inventory-2-line',
                'url' => route('admin.stock'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],


        ]);
    }
}
