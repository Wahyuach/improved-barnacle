<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Show the inventory dashboard.
     */
    public function dashboard()
    {
        return view('inventory-dashboard');
    }

    /**
     * Show product detail page.
     */
    public function showProduct(Product $product)
    {
        return view('product-detail', compact('product'));
    }

    /**
     * Delete a product.
     */
    public function deleteProduct(Product $product)
    {
        $product->update(['is_active' => false]);
        
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
