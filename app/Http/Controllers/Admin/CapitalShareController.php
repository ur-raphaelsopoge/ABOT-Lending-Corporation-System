<?php

namespace App\Http\Controllers\Admin;

use App\Models\CapitalShare;
use Illuminate\Contracts\View\View;

class CapitalShareController
{
    public function index(): View
    {
        return view('admin.capital-shares.index', [
            'shares' => CapitalShare::with('member')
                ->latest('share_date')
                ->latest('id')
                ->simplePaginate(10),
            'totalShares' => CapitalShare::sum('amount'),
            'contributionCount' => CapitalShare::count(),
        ]);
    }
}
