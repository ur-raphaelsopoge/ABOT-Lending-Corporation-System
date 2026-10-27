<?php

namespace App\Http\Controllers\Admin;

use App\Models\AuditLedger;
use App\Models\LoanApplication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoanController
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        if (! in_array($status, LoanApplication::STATUSES, true)) {
            $status = null;
        }

        $applications = LoanApplication::with('user')
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->simplePaginate(10)
            ->withQueryString();

        return view('admin.loans.index', [
            'applications' => $applications,
            'status' => $status,
            'statuses' => LoanApplication::STATUSES,
            'totalCount' => LoanApplication::count(),
            'pendingCount' => LoanApplication::where('status', 'pending')->count(),
            'approvedCount' => LoanApplication::where('status', 'approved')->count(),
            'approvedAmount' => LoanApplication::where('status', 'approved')->sum('amount'),
        ]);
    }

    public function update(Request $request, LoanApplication $application): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'admin_remarks' => ['nullable', 'string', 'max:255'],
        ]);

        if ($application->status !== 'pending') {
            return back()->with('error', 'Application ' . $application->application_number . ' has already been reviewed.');
        }

        $adminId = Auth::guard('admin')->id();

        $application->update([
            'status' => $data['status'],
            'admin_remarks' => $data['admin_remarks'] ?? null,
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
        ]);

        AuditLedger::record(
            $application->user_id,
            'loan_' . $data['status'],
            $application->application_number . ' was ' . $data['status'] . '.',
            $adminId
        );

        return back()->with('status', 'Application ' . $application->application_number . ' was ' . $data['status'] . '.');
    }
}
