<?php

namespace App\Livewire\Admin\Coupon;

use App\Models\Coupon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CouponList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public ?int $couponId = null;
    public string $code = '';
    public string $type = 'fixed';
    public $value;
    public $min_cart_amount;
    public $max_discount;
    public $usage_limit;
    public $per_user_limit;
    public bool $is_active = true;
    public $starts_at;
    public $expires_at;

    public ?int $deleteId = null;

    protected function rules()
    {
        return [
            'code' => 'required|unique:coupons,code,' . $this->couponId,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_cart_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'per_user_limit' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    /* =========================
       Modal Handlers
    ==========================*/

    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal');
    }

    public function openEditModal(int $id)
    {
        $coupon = Coupon::findOrFail($id);

        $this->couponId       = $coupon->id;
        $this->code           = $coupon->code;
        $this->type           = $coupon->type;
        $this->value          = $coupon->value;
        $this->min_cart_amount = $coupon->min_cart_amount;
        $this->max_discount   = $coupon->max_discount;
        $this->usage_limit    = $coupon->usage_limit;
        $this->per_user_limit = $coupon->per_user_limit;
        $this->is_active      = (bool) $coupon->is_active;
        $this->starts_at      = $coupon->starts_at ? $coupon->starts_at->format('Y-m-d\TH:i') : null;
        $this->expires_at     = $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : null;

        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();

        Coupon::updateOrCreate(
            ['id' => $this->couponId],
            [
                'code'            => $this->code,
                'type'            => $this->type,
                'value'           => $this->value,
                'min_cart_amount' => $this->min_cart_amount ?: null,
                'max_discount'    => $this->max_discount ?: null,
                'usage_limit'     => $this->usage_limit ?: null,
                'per_user_limit'  => $this->per_user_limit ?: null,
                'is_active'       => $this->is_active,
                'starts_at'       => $this->starts_at ?: null,
                'expires_at'      => $this->expires_at ?: null,
            ]
        );

        $this->dispatch('toast-show', [
            'message' => 'Coupon saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function deleteConfirmed()
    {
        Coupon::findOrFail($this->deleteId)->delete();
        $this->reset('deleteId');

        $this->dispatch('toast-show', [
            'message' => 'Coupon deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-delete-modal');
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'couponId',
            'code',
            'type',
            'value',
            'min_cart_amount',
            'max_discount',
            'usage_limit',
            'per_user_limit',
            'is_active',
            'starts_at',
            'expires_at'
        ]);
        $this->type = 'fixed';
        $this->is_active = true;
    }

    public function getCoupons()
    {
        return Coupon::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.coupon.coupon-list', [
            'coupons' => $this->getCoupons(),
        ]);
    }
}
