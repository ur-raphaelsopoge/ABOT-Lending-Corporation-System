<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MemberController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $pattern = '%' . addcslashes($search, '%_\\') . '%';

        $members = User::query()
            ->when($search !== '', function ($query) use ($pattern) {
                $query->where(function ($query) use ($pattern) {
                    $query->where('name', 'ilike', $pattern)
                        ->orWhere('borrower_id', 'ilike', $pattern)
                        ->orWhere('phone_number', 'ilike', $pattern)
                        ->orWhere('email', 'ilike', $pattern);
                });
            })
            ->withSum('capitalShares as shares_total', 'amount')
            ->withCount(['loanApplications as approved_loans_count' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->orderBy('name')
            ->simplePaginate(10)
            ->withQueryString();

        return view('admin.members.index', [
            'members' => $members,
            'search' => $search,
            'totalMembers' => User::count(),
        ]);
    }
}
