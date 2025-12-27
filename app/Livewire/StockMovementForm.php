<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class StockMovementForm extends Component
{
    public $product = null;
    public $showModal = false;

    #[Validate('required|in:in,out,adjustment,return')]
    public $type = 'in';

    #[Validate('required|integer|min:1')]
    public $quantity = '';

    #[Validate('nullable|string|max:100')]
    public $reference = '';

    #[Validate('nullable|string|max:500')]
    public $notes = '';

    #[Validate('nullable|string|max:100')]
    public $supplier = '';

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function save()
    {
        $this->validate();

        $oldQuantity = $this->product->quantity;
        $newQuantity = match ($this->type) {
            'in', 'return' => $oldQuantity + $this->quantity,
            'out', 'adjustment' => max(0, $oldQuantity - $this->quantity),
        };

        $this->product->update(['quantity' => $newQuantity]);

        // Get current authenticated user ID
        $currentUser = \Illuminate\Support\Facades\Auth::user();
        $userId = $currentUser ? $currentUser->id : 1;

        StockMovement::create([
            'product_id' => $this->product->id,
            'user_id' => $userId,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'reference' => $this->reference,
            'notes' => $this->notes,
            'supplier' => $this->supplier,
        ]);

        $this->reset(['quantity', 'reference', 'notes', 'type', 'supplier']);
        $this->type = 'in';
        $this->showModal = false;

        $this->dispatch('stock-updated');
        session()->flash('message', 'Stok berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.stock-movement-form');
    }
}
