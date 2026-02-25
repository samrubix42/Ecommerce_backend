<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Str;

class ManageAttributeValue extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $name;
    public $status = true;
    public $attributeId = null;

    public $search = '';

    // Value modal state
    public $valueAttributeId = null;
    public $valueAttributeName = null;
    public $valueInputs = [];
    public $newValue = '';
    public $editingValueId = null;
    public $editingValueText = '';
    public $currentValues = [];
    public $confirmValueId = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'boolean',
        ];
    }

    /* ================= ATTRIBUTE ================= */

    public function resetForm()
    {
        $this->reset(['name','status','attributeId']);
        $this->status = true;
    }

    public function openEdit($id)
    {
        $attribute = Attribute::findOrFail($id);

        $this->attributeId = $attribute->id;
        $this->name = $attribute->name;
        $this->status = $attribute->status;
    }

    public function save()
    {
        $this->validate();

        $slug = Str::slug($this->name);

        Attribute::updateOrCreate(
            ['id' => $this->attributeId],
            [
                'name' => $this->name,
                'slug' => $slug,
                'status' => $this->status,
            ]
        );

        $this->resetForm();
        $this->dispatch('close-attribute-modal');
        $this->currentValues = [];
        $this->dispatch('toast-show', [
            'message' => 'Attribute saved.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function deleteConfirmed($id)
    {
        Attribute::findOrFail($id)->delete();
        $this->dispatch('toast-show', [
            'message' => 'Attribute deleted.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    /* ================= VALUES ================= */

    public function openValueModal($id)
    {
        $attribute = Attribute::findOrFail($id);

        $this->valueAttributeId = $attribute->id;
        $this->valueAttributeName = $attribute->name;
        $this->currentValues = $attribute->values()->get()->toArray();
    }

    public function addNewValue()
    {
        if (!$this->newValue) return;

        $baseSlug = Str::slug($this->newValue);

        $slug = $this->uniqueValueSlug($this->valueAttributeId, $baseSlug);

        $this->createValueWithRetry([
            'attribute_id' => $this->valueAttributeId,
            'value' => $this->newValue,
            'slug' => $slug,
        ]);

        $this->newValue = '';
        // refresh list
        $this->currentValues = AttributeValue::where('attribute_id', $this->valueAttributeId)->get()->toArray();
        $this->dispatch('toast-show', [
            'message' => 'Value added.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function startEditValue($id)
    {
        $value = AttributeValue::findOrFail($id);
        $this->editingValueId = $id;
        $this->editingValueText = $value->value;
    }

    public function confirmValueDelete($id)
    {
        $this->confirmValueId = $id;
    }

    public function cancelConfirmValue()
    {
        $this->confirmValueId = null;
    }

    public function updateValue()
    {
        $baseSlug = Str::slug($this->editingValueText);
        $slug = $this->uniqueValueSlug($this->valueAttributeId, $baseSlug, $this->editingValueId);

        // attempt update with retry on unique constraint
        $attempt = 0;
        $max = 10;
        while ($attempt < $max) {
            try {
                AttributeValue::findOrFail($this->editingValueId)->update([
                    'value' => $this->editingValueText,
                    'slug' => $slug,
                ]);
                break;
            } catch (\Illuminate\Database\QueryException $e) {
                $attempt++;
                $slug = $baseSlug . '-' . $attempt;
            }
        }

        $this->editingValueId = null;
        $this->editingValueText = '';
        $this->currentValues = AttributeValue::where('attribute_id', $this->valueAttributeId)->get()->toArray();
        $this->dispatch('toast-show', [
            'message' => 'Value updated.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function deleteValue($id)
    {
        $val = AttributeValue::findOrFail($id);
        $attrId = $val->attribute_id;
        $val->delete();
        if ($this->valueAttributeId == $attrId) {
            $this->currentValues = AttributeValue::where('attribute_id', $this->valueAttributeId)->get()->toArray();
        }
        $this->dispatch('toast-show', [
            'message' => 'Value deleted.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    /**
     * Generate a unique slug for an attribute value within the same attribute.
     * If $excludeId is provided, that record will be ignored when checking uniqueness (useful for updates).
     */
    private function uniqueValueSlug(int $attributeId, string $baseSlug, int $excludeId = null): string
    {
        $slug = $baseSlug;
        $i = 1;

        while (AttributeValue::where('attribute_id', $attributeId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        return $slug;
    }

    public function addValue($attributeId)
    {
        $value = trim($this->valueInputs[$attributeId] ?? '');

        if (!$value) return;

        $baseSlug = Str::slug($value);
        $slug = $this->uniqueValueSlug($attributeId, $baseSlug);

        $this->createValueWithRetry([
            'attribute_id' => $attributeId,
            'value' => $value,
            'slug' => $slug,
        ]);

        $this->valueInputs[$attributeId] = '';

        // refresh current values if modal is open for this attribute
        if ($this->attributeId == $attributeId) {
            $this->currentValues = AttributeValue::where('attribute_id', $attributeId)->get()->toArray();
        }
        $this->dispatch('toast-show', [
            'message' => 'Value added.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    /**
     * Attempt to create an AttributeValue and retry with incremented slug on duplicate key.
     */
    private function createValueWithRetry(array $data)
    {
        $attempt = 0;
        $max = 10;
        $baseSlug = $data['slug'];

        while ($attempt < $max) {
            try {
                AttributeValue::create($data);
                return;
            } catch (\Illuminate\Database\QueryException $e) {
                // If duplicate key on slug, increment and retry
                $attempt++;
                $data['slug'] = $baseSlug . '-' . $attempt;
            }
        }

        // If still failing, throw last exception
        throw new \RuntimeException('Unable to create unique attribute value slug after retries.');
    }
    

    #[Layout('layouts.admin')]
    public function render()
    {
        $attributeList = Attribute::where('name', 'like', "%{$this->search}%")
            ->latest()
            ->paginate(10);

        return view('livewire.admin.product.manage-attribute-value', compact('attributeList'));
    }
}