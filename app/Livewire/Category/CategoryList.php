<?php

namespace App\Livewire\Category;

use App\Models\ProductCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CategoryList extends Component
{
    use WithPagination, WithFileUploads;

    #[Url]
    public string $search = '';

    public ?int $categoryId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public bool $status = true;

    public bool $isSubcategory = false; 
    public ?int $parentId = null;

    public string $meta_title = '';
    public string $meta_keywords = '';
    public string $meta_description = '';

    public ?int $deleteId = null;
    public $image = null;
    public $existingImage = null;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'slug' => 'required|unique:product_categories,slug,' . $this->categoryId,
            'description' => 'nullable|string',
            'parentId' => 'nullable|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
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
        $category = ProductCategory::findOrFail($id);

        $this->categoryId   = $category->id;
        $this->name         = $category->title;
        $this->slug         = $category->slug;
        $this->description  = $category->description ?? '';
        $this->status       = (bool) ($category->status ?? true);
        $this->parentId     = $category->parent_id;

       
        $this->existingImage = $category->image;

    
        $this->meta_title = $category->meta_title ?? '';
        $this->meta_keywords = $category->meta_keywords ?? '';
        $this->meta_description = $category->meta_description ?? '';

      
        $this->isSubcategory = $category->parent_id ? true : false;

        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    /* =========================
       Livewire Watchers
    ==========================*/

    public function updatedIsSubcategory($value)
    {
   
        if (!$value) {
            $this->parentId = null;
        }
    }
    public function handleCategorySort($id, $position)
    {
        $categories = ProductCategory::orderBy('sort_order')->get();

        $movedItem = $categories->firstWhere('id', $id);

        if (!$movedItem) return;

        $categories = $categories->reject(fn($item) => $item->id == $id)
            ->values();

        $categories->splice($position, 0, [$movedItem]);

        foreach ($categories as $index => $category) {
            $category->update(['sort_order' => $index]);
        }

        $this->dispatch('toast-show', [
            'message' => 'Category Reordered',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function updatedName()
    {
        $this->slug = Str::slug($this->name);
    }



    public function save()
    {
        $this->validate();

        $existing = $this->categoryId ? ProductCategory::find($this->categoryId) : null;

        $imagePath = $existing->image ?? null;
        if ($this->image) {
            // delete old image if exists
            if ($existing && $existing->image) {
                Storage::disk('public')->delete($existing->image);
            }

            $imagePath = $this->image->store('categories', 'public');
        }

        ProductCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'parent_id'      => $this->isSubcategory ? $this->parentId : null,
                'title'          => $this->name,
                'slug'           => $this->slug,
                'description'    => $this->description,
                'status'         => $this->status,
                'image'          => $imagePath,
                'meta_title'     => $this->meta_title,
                'meta_keywords'  => $this->meta_keywords,
                'meta_description' => $this->meta_description,
            ]
        );

        $this->dispatch('toast-show', [
            'message' => 'Category saved successfully!',
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

    public function delete(int $id)
    {
        ProductCategory::findOrFail($id)->delete();

        $this->dispatch('toast-show', [
            'message' => 'Category deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'categoryId',
            'name',
            'slug',
            'description',
            'status',
            'parentId',
            'isSubcategory',
            'image',
            'existingImage',
            'meta_title',
            'meta_keywords',
            'meta_description'
        ]);

        $this->status = true;
        $this->isSubcategory = false;
        $this->parentId = null;
    }



    public function getCategories()
    {
        return ProductCategory::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('sort_order')
            ->get(); 
    }
    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.category.category-list', [
            'categories' => $this->getCategories(),
            'parentCategories' => ProductCategory::whereNull('parent_id')->get(),
        ]);
    }
}
