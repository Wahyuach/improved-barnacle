<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pergerakan Stok</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f3f4f6;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-in {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-out {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-adjustment {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-return {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .text-green {
            color: #059669;
            font-weight: bold;
        }
        .text-red {
            color: #dc2626;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>📊 Laporan Pergerakan Stok</h1>
    <div class="header-info">
        <p>
            <strong>Tanggal Cetak:</strong> {{ date('d M Y H:i') }}<br>
            @if($search)
                <strong>Filter Pencarian:</strong> {{ $search }}<br>
            @endif
            @if($type)
                <strong>Jenis Pergerakan:</strong> 
                @if($type === 'in') Masuk
                @elseif($type === 'out') Keluar
                @elseif($type === 'adjustment') Penyesuaian
                @elseif($type === 'return') Return
                @endif
                <br>
            @endif
            <strong>Total Data:</strong> {{ $movements->count() }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 20%;">Produk</th>
                <th style="width: 12%;">Jenis</th>
                <th style="width: 8%;">Jumlah</th>
                <th style="width: 12%;">Referensi</th>
                <th style="width: 24%;">Catatan</th>
                <th style="width: 12%;">User</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong>{{ $movement->product->name }}</strong><br>
                        <small style="color: #666;">{{ $movement->product->sku }}</small>
                    </td>
                    <td>
                        <span class="badge badge-{{ $movement->type }}">
                            @if($movement->type === 'in') 📥 Masuk
                            @elseif($movement->type === 'out') 📤 Keluar
                            @elseif($movement->type === 'adjustment') ⚙️ Penyesuaian
                            @else ↩️ Return
                            @endif
                        </span>
                    </td>
                    <td class="@if($movement->type === 'in' || $movement->type === 'return') text-green @else text-red @endif">
                        @if($movement->type === 'in' || $movement->type === 'return')+@else-@endif{{ $movement->quantity }}
                    </td>
                    <td>{{ $movement->reference ?? '-' }}</td>
                    <td>{{ $movement->notes ?? '-' }}</td>
                    <td>{{ $movement->user->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Tidak ada data pergerakan stok
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Kantin Kenanga - Laporan Pergerakan Stok</p>
    </div>
</body>
</html>
