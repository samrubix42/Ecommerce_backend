<div class="max-w-6xl mx-auto px-6 py-8 space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-semibold text-slate-900">
            Attribute Management
        </h1>
    </div>

    <!-- Create / Edit Card -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <input type="text"
                   wire:model="name"
                   placeholder="Attribute name (e.g. Color)"
                   class="rounded-md border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/30">

            <select wire:model="type"
                    class="rounded-md border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/30">
                <option value="string">String</option>
                <option value="number">Number</option>
                <option value="boolean">Boolean</option>
                <option value="date">Date</option>
            </select>

            <button wire:click="save"
                    class="bg-blue-600 text-white rounded-md px-4 py-2 text-sm hover:bg-blue-700 transition">
                {{ $attributeId ? 'Update' : 'Create' }}
            </button>

        </div>
    </div>

    <!-- Search -->
    <div>
        <input type="text"
               wire:model.live="search"
               placeholder="Search attributes..."
               class="w-full max-w-xs rounded-md border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/30">
    </div>

    <!-- Table -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

        <table class="min-w-full text-sm">

            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-5 py-3 text-left">Attribute</th>
                    <th class="px-5 py-3 text-left">Type</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @foreach($attributeList as $attribute)

                <tr class="hover:bg-slate-50">

                    <td class="px-5 py-4">
                        <div class="font-medium text-slate-900">
                            {{ $attribute->name }}
                        </div>

                        <!-- Values -->
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($attribute->values as $value)
                                <span class="bg-slate-100 text-slate-700 text-xs px-2 py-1 rounded-md flex items-center gap-1">
                                    {{ $value->value }}
                                    <button wire:click="deleteValue({{ $value->id }})"
                                            class="text-red-500 hover:text-red-600">
                                        ×
                                    </button>
                                </span>
                            @endforeach
                        </div>

                        <!-- Add Value -->
                        <div class="flex gap-2 mt-3">
                            <input type="text"
                                   wire:model="valueInputs.{{ $attribute->id }}"
                                   placeholder="Add value"
                                   class="text-xs rounded-md border border-slate-300 px-2 py-1">

                            <button wire:click="addValue({{ $attribute->id }})"
                                    class="text-xs bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700">
                                Add
                            </button>
                        </div>
                    </td>

                    <td class="px-5 py-4 text-slate-600">
                        {{ ucfirst($attribute->type) }}
                    </td>

                    <td class="px-5 py-4 text-right space-x-3">
                        <button wire:click="edit({{ $attribute->id }})"
                                class="text-blue-600 text-sm hover:underline">
                            Edit
                        </button>

                        <button wire:click="delete({{ $attribute->id }})"
                                class="text-red-600 text-sm hover:underline">
                            Delete
                        </button>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        <div class="p-4">
            {{ $attributeList->links() }}
        </div>

    </div>

</div>