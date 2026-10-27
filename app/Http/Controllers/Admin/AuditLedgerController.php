<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLedger;
use Illuminate\Contracts\View\View;

class AuditLedgerController
{
    public function index(): View
    {
        return view('admin.audit-ledger.index', [
            'entries' => AuditLedger::with(['user', 'admin'])
                ->latest('created_at')
                ->latest('id')
                ->simplePaginate(15),
            'entryCount' => AuditLedger::count(),
        ]);
    }
}
