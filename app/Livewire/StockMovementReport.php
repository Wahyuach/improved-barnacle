<?php

namespace App\Livewire;

use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class StockMovementReport extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $perPage = 15;

    protected $queryString = ['search', 'type'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function exportPdf()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reference', 'like', '%' . $this->search . '%')
                ->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('pdf.stock-movement-report', [
            'movements' => $movements,
            'search' => $this->search,
            'type' => $this->type,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'laporan-pergerakan-stok-' . date('Y-m-d') . '.pdf');
    }

    public function render()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('product', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                })
                ->orWhere('reference', 'like', '%' . $this->search . '%')
                ->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('stock-movement-report', [
            'movements' => $movements,
        ]);
    }
}

