<?php

namespace Tests\Feature;

use App\Models\Product;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFormTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\PreserveGlobalState(false)]
    public function test_add_mode_initializes_with_no_disabled_attribute()
    {
        // Mount the AddProductForm component
        Livewire::test(\App\Livewire\AddProductForm::class)
            // Should render add-mode header
            ->assertSee('Simpan Produk')
            // When adding, the SKU input should NOT be disabled in the rendered markup
            ->assertDontSee('disabled');
    }

    #[\PHPUnit\Framework\Attributes\RunInSeparateProcess]
    #[\PHPUnit\Framework\Attributes\PreserveGlobalState(false)]
    public function test_edit_mode_initializes_with_isEditing_true_and_renders_disabled_sku()
    {
        // Create a Product instance
        $product = new Product([
            'name' => 'Sample Product',
            'sku' => 'SKU-EDIT-001',
            'category_id' => 1,
            'price' => 1000,
            'quantity' => 10,
            'min_quantity' => 1,
            'max_quantity' => 100,
            'unit' => 'pcs',
            'description' => 'desc',
            'supplier' => 'supplier',
        ]);

        Livewire::test(\App\Livewire\EditProductForm::class, ['product' => $product])
            ->assertSet('sku', 'SKU-EDIT-001')
            // When editing, the SKU input should be rendered disabled
            ->assertSee('disabled');
    }
}
