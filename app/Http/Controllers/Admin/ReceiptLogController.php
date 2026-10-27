<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReceiptLog;
use Illuminate\Contracts\View\View;

class ReceiptLogController
{
    public function index(): View
    {
        return view('admin.receipt-logs.index', [
            'receipts' => ReceiptLog::with('member')
                ->latest('received_at')
                ->latest('id')
                ->simplePaginate(10),
            'totalReceipts' => ReceiptLog::sum('amount'),
            'receiptCount' => ReceiptLog::count(),
        ]);
    }
}
