<?php

namespace App\Livewire\Admin\Stock;

use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StockList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    // Adjustment Modal State
    public ?int $selectedInventoryId = null;
    public $adjustmentQuantity = 0;
    public string $adjustmentType = 'stock_in'; // stock_in, stock_out, adjustment, sale, return, reserved, released
    public string $adjustmentNote = '';
    public ?string $reference_type = null;
    public ?int $reference_id = null;

    // SKU Search for Reference
    public string $skuSearch = '';
    public $suggestedVariants = [];

    // History Modal State
    public ?int $selectedHistoryInventoryId = null;

    protected $rules = [
        'adjustmentQuantity' => 'required|integer|min:1',
        'adjustmentType' => 'required|in:stock_in,stock_out,adjustment,sale,return,reserved,released',
        'adjustmentNote' => 'nullable|string|max:255',
        'reference_type' => 'nullable|string',
        'reference_id' => 'nullable|integer',
    ];

    /* =========================
       Modal Handlers
    ==========================*/

    public function openAdjustmentModal(int $inventoryId)
    {
        $this->resetAdjustmentForm();
        $this->selectedInventoryId = $inventoryId;
        $this->dispatch('open-adjustment-modal');
    }

    public function openHistoryModal(int $inventoryId)
    {
        $this->selectedHistoryInventoryId = $inventoryId;
        $this->dispatch('open-history-modal');
    }

    public function resetAdjustmentForm()
    {
        $this->reset(['selectedInventoryId', 'adjustmentQuantity', 'adjustmentNote', 'reference_type', 'reference_id', 'skuSearch', 'suggestedVariants']);
        $this->adjustmentType = 'stock_in';
    }

    public function updatedSkuSearch($value)
    {
        if (strlen($value) < 2) {
            $this->suggestedVariants = [];
            return;
        }

        $this->suggestedVariants = ProductVariant::where('sku', 'like', '%' . $value . '%')
            ->with('product')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function selectReferenceVariant($variantId, $sku)
    {
        $this->reference_id = $variantId;
        $this->skuSearch = $sku;
        $this->suggestedVariants = [];
    }

    public function applyAdjustment()
    {
        $this->validate();

        $inventory = Inventory::findOrFail($this->selectedInventoryId);

        if (!$inventory->track_inventory) {
            $this->dispatch('toast-show', [
                'type' => 'warning',
                'position' => 'top-right',
                'html' => '
                    <div class="p-4 flex items-start gap-3">
                        <div class="text-orange-400 mt-0.5">
                            <i class="ri-error-warning-line text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[13px] font-bold text-gray-800">Inventory Tracking Disabled</p>
                            <p class="text-xs text-gray-500 mt-1 leading-normal">
                                Please enable <b>Track Inventory</b> in product settings to allow stock adjustments.
                            </p>
                        </div>
                    </div>
                '
            ]);
            $this->dispatch('close-adjustment-modal');
            return;
        }

        $before = $inventory->quantity;
        $beforeReserved = $inventory->reserved_quantity;
        $qtyChange = (int) $this->adjustmentQuantity;

        DB::transaction(function () use ($inventory, $before, $beforeReserved, $qtyChange) {
            $after = $before;
            $afterReserved = $beforeReserved;

            if (in_array($this->adjustmentType, ['stock_out', 'sale'])) {
                $after = $before - $qtyChange;
            } elseif (in_array($this->adjustmentType, ['stock_in', 'return', 'adjustment'])) {
                $after = $before + $qtyChange;
            } elseif ($this->adjustmentType === 'reserved') {
                $afterReserved = $beforeReserved + $qtyChange;
            } elseif ($this->adjustmentType === 'released') {
                $afterReserved = max(0, $beforeReserved - $qtyChange);
            }

            // Update Inventory
            $inventory->update([
                'quantity' => $after,
                'reserved_quantity' => $afterReserved
            ]);

            // Sync with ProductVariant legacy stock column
            $inventory->variant->update([
                'stock' => $after
            ]);

            // Create Log
            InventoryLog::create([
                'inventory_id' => $inventory->id,
                'type' => $this->adjustmentType,
                'quantity' => abs($qtyChange),
                'before_quantity' => in_array($this->adjustmentType, ['reserved', 'released']) ? $beforeReserved : $before,
                'after_quantity' => in_array($this->adjustmentType, ['reserved', 'released']) ? $afterReserved : $after,
                'reference_type' => $this->reference_type,
                'reference_id' => $this->reference_id,
                'note' => $this->adjustmentNote,
            ]);
        });

        $this->dispatch('toast-show', [
            'message' => 'Stock adjusted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-adjustment-modal');
        $this->resetAdjustmentForm();
    }

    /* =========================
       Data Fetching
    ==========================*/

    public function getStockItems()
    {
        // We query variants and their inventories
        return ProductVariant::query()
            ->with(['product', 'inventory', 'variantAttributes.attribute', 'variantAttributes.value'])
            ->leftJoin('products', 'product_variants.product_id', '=', 'products.id')
            ->select('product_variants.*')
            ->where(function ($q) {
                $q->where('products.name', 'like', '%' . $this->search . '%')
                    ->orWhere('product_variants.sku', 'like', '%' . $this->search . '%');
            })
            ->latest('product_variants.created_at')
            ->paginate(10);
    }

    public function getSelectedInventoryHistory()
    {
        if (!$this->selectedHistoryInventoryId) return collect();

        return InventoryLog::where('inventory_id', $this->selectedHistoryInventoryId)
            ->latest()
            ->get();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        // Ensure every variant has an inventory record (Lazy initialization if missing)
        $variants = $this->getStockItems();

        foreach ($variants as $variant) {
            if (!$variant->inventory) {
                $variant->inventory()->create([
                    'quantity' => 0,
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => 5,
                    'track_inventory' => true
                ]);
                $variant->load('inventory');
            }
        }

        return view('livewire.admin.stock.stock-list', [
            'variants' => $variants,
            'historyLogs' => $this->getSelectedInventoryHistory(),
            'selectedInventory' => $this->selectedHistoryInventoryId ? Inventory::with('variant.product')->find($this->selectedHistoryInventoryId) : null
        ]);
    }
}
