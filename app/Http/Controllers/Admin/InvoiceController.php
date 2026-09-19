<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('booking.kamar')
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalInvoice    = Invoice::count();
        $totalBulanIni   = Invoice::whereMonth('tg_transaksi', now()->month)->count();
        $totalNominal    = Invoice::sum('total_bayar');

        return view('admin.invoice', compact(
            'invoices', 'totalInvoice', 'totalBulanIni', 'totalNominal'
        ));
    }
}