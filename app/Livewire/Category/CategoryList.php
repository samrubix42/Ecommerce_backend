<?php

namespace App\Livewire\Category;

use App\Models\ProductCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Modal states
    public $showModal = false;
    public $isEdit = false;
    
    // Form data
    public $categoryId;
    public $name = '';
    public $slug = '';
    public $description = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:product_categories,slug',
        'description' => 'nullable|string',
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'search') {
            $this->resetPage();
        }
    }

    public function getCategories()
    {
        return ProductCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openModal($categoryId = null)
    {
        if ($categoryId) {
            $category = ProductCategory::find($categoryId);
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->isEdit = true;
        } else {
            $this->resetForm();
            $this->isEdit = false;
        }
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $category = ProductCategory::find($this->categoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
            ]);
            $message = 'Category updated successfully!';
        } else {
            ProductCategory::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
            ]);
            $message = 'Category created successfully!';
        }

        $this->dispatch('notify', message: $message, type: 'success');
        $this->closeModal();
        $this->resetPage();
    }

    public function delete($categoryId)
    {
        ProductCategory::find($categoryId)->delete();
        $this->dispatch('notify', message: 'Category deleted successfully!', type: 'success');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.category.category-list', [
            'categories' => $this->getCategories(),
        ]);
    }
}
