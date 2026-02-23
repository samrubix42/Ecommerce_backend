<?php

namespace App\Livewire\Category;

use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public bool $status = true;
    public ?int $parentId = null;
    public ?int $deleteId = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'slug' => 'required|unique:product_categories,slug,' . $this->categoryId,
            'description' => 'nullable|string',
            'parentId' => 'nullable|exists:product_categories,id',
        ];
    }

    /* -------------------------
        Modal handlers
    --------------------------*/



    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function deleteConfirmed()
    {
        ProductCategory::findOrFail($this->deleteId)->delete();
        $this->reset('deleteId');
        $this->dispatch('toast-show', [
            'message' => 'Category deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
        $this->dispatch('close-delete-modal');
        $this->resetPage();
    }


    public function openCreateModal()
    {
        $this->resetForm();
        $this->dispatch('open-modal');
    }

    public function openEditModal(int $id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->title;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->status = (bool)($category->status ?? true);
        $this->parentId = $category->parent_id;
        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal');
        $this->resetForm();
    }
        // Fetch paginated categories for the view
    public function getCategories()
    {
        return ProductCategory::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    /* -------------------------
        CRUD
    --------------------------*/

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }
    public function save()
    {
        $this->validate();

        ProductCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'parent_id' => $this->parentId ?: null,
                'title' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'status' => $this->status,
            ]
        );

        $this->dispatch('toast-show', [
            'message' => 'Category saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->closeModal();
    }

    public function delete(int $id)
    {
        ProductCategory::findOrFail($id)->delete();
        $this->dispatch('toast-show', [
            'message' => 'Category deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
        $this->dispatch('close-delete-modal');
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'slug', 'description', 'status', 'parentId']);
        $this->status = true;
        $this->parentId = null;
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.category.category-list', [
            'categories' => $this->getCategories(),
            'parentCategories' => ProductCategory::whereNull('parent_id')->get(),
            'categoryId' => $this->categoryId,
        ]);
    }
}
