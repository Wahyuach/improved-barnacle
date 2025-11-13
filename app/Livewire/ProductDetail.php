<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ProductDetail extends Component
{
    use WithPagination;

    public Product $product;

    #[Computed]
    public function stockMovements()
    {
        return $this->product->stockMovements()->paginate(10);
    }

    public function render()
    {
        return view('livewire.product-detail');
    }
}
