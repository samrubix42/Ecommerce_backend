<?php

namespace App\Livewire\Admin\Product;

use Livewire\Component;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class ManageAttributeValue extends Component
{
    use WithPagination;

    public $name;
    public $type = 'string';
    public $attributeId = null;

    public $valueInputs = [];
    public $search = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:string,number,boolean,date',
        ];
    }

    public function save()
    {
        $this->validate();

        Attribute::updateOrCreate(
            ['id' => $this->attributeId],
            [
                'name' => $this->name,
                'type' => $this->type,
            ]
        );

        $this->reset(['name', 'type', 'attributeId']);
    }

    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        $this->attributeId = $attribute->id;
        $this->name = $attribute->name;
        $this->type = $attribute->type;
    }

    public function delete($id)
    {
        Attribute::findOrFail($id)->delete();
    }

    public function addValue($attributeId)
    {
        if (!empty($this->valueInputs[$attributeId])) {

            AttributeValue::create([
                'attribute_id' => $attributeId,
                'value' => $this->valueInputs[$attributeId]
            ]);

            $this->valueInputs[$attributeId] = '';
        }
    }

    public function deleteValue($id)
    {
        AttributeValue::findOrFail($id)->delete();
    }
    #[Layout('layouts.admin')]
    public function render()
    {

        $attributeList = Attribute::with('values')
            ->where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);
        return view('livewire.admin.product.manage-attribute-value', compact('attributeList'));
    }
}
