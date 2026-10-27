<?php

namespace App\Http\Controllers\Admin;

use App\Models\CapitalShare;
use App\Models\LoanApplication;
use App\Models\ReceiptLog;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'totalMembers' => User::count(),
            'totalShares' => CapitalShare::sum('amount'),
            'totalReceipts' => ReceiptLog::sum('amount'),
            'activeLoans' => LoanApplication::where('status', 'approved')->count(),
            'pendingApplications' => LoanApplication::where('status', 'pending')->count(),
            'recentMembers' => User::latest()->limit(5)->get(),
            'recentReceipts' => ReceiptLog::with('member')->latest('received_at')->limit(5)->get(),
            'recentApplications' => LoanApplication::with('user')->latest('id')->limit(5)->get(),
        ]);
    }
}
