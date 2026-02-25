<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $categoryFilter = '';

    public ?int $deleteId = null;

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function categories()
    {
        return ProductCategory::where('status', true)->orderBy('title')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(int $id)
    {
        $product = Product::findOrFail($id);
        $newStatus = match ($product->status) {
            'active' => 'inactive',
            'inactive' => 'active',
            'draft' => 'active',
        };
        $product->update(['status' => $newStatus]);

        $this->dispatch('toast-show', [
            'message' => 'Product status updated!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function delete()
    {
        if ($this->deleteId) {
            Product::findOrFail($this->deleteId)->delete();

            $this->dispatch('toast-show', [
                'message' => 'Product deleted successfully!',
                'type' => 'success',
                'position' => 'top-right',
            ]);

            $this->dispatch('close-delete-modal');
            $this->reset('deleteId');
            $this->resetPage();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Query
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function products()
    {
        return Product::query()
            ->with(['category', 'defaultVariant', 'primaryImage', 'variants'])
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->latest()
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.product.product-list');
    }
}
