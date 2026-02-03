<?php

namespace App\Livewire\Category;

use App\Models\ProductCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $paginationTheme = 'bootstrap';

    public bool $showModal = false;
    public bool $isEdit = false;

    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public ?string $description = '';
    public ?int $parentId = null;
    public bool $isSubcategory = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getCategories(): LengthAwarePaginator
    {
        return ProductCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(3);
    }

    public function sort(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openModal(?int $categoryId = null): void
    {
        if ($categoryId) {
            $category = ProductCategory::find($categoryId);
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->parentId = $category->parent_id;
            $this->isSubcategory = !is_null($category->parent_id);
            $this->isEdit = true;
        } else {
            $this->resetForm();
            $this->isEdit = false;
        }
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->parentId = null;
        $this->isSubcategory = false;
        $this->resetErrorBag();
    }
    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);
    }

    public function save(): void
    {
        if (!$this->isSubcategory) {
            $this->parentId = null;
        }

        $this->validate();

        if ($this->isEdit) {
            $category = ProductCategory::find($this->categoryId);
            $category->update([
                'parent_id' => $this->parentId ?: null,
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
            ]);
            $message = 'Category updated successfully!';
        } else {
            ProductCategory::create([
                'parent_id' => $this->parentId ?: null,
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

    public function confirmDelete(int $categoryId): void
    {
        $this->deleteId = $categoryId;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function delete(): void
    {
        if ($this->deleteId) {
            ProductCategory::find($this->deleteId)?->delete();
            $this->dispatch('notify', message: 'Category deleted successfully!', type: 'success');
            $this->resetPage();
        }

        $this->cancelDelete();
    }

    protected function rules(): array
    {
        $slugRule = 'required|string|max:255|unique:product_categories,slug';

        if ($this->isEdit && $this->categoryId) {
            $slugRule .= ',' . $this->categoryId;
        }

        $parentRule = $this->isSubcategory
            ? 'required|exists:product_categories,id'
            : 'nullable|exists:product_categories,id';

        return [
            'name' => 'required|string|max:255',
            'slug' => $slugRule,
            'description' => 'nullable|string',
            'parentId' => $parentRule,
            'isSubcategory' => 'boolean',
        ];
    }

    #[Layout('layouts.admin')]
    #[Title('Category Management')]
    public function render(): View
    {
        return view('livewire.category.category-list', [
            'categories' => $this->getCategories(),
            'parentCategories' => ProductCategory::whereNull('parent_id')->get(),
        ]);
    }
}
