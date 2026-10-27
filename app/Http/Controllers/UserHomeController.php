<?php

namespace App\Http\Controllers;

use App\Models\AuditLedger;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserHomeController
{
    public function index(): View
    {
        $user = Auth::guard('web')->user();

        return view('user.home', [
            'user' => $user,
            'applications' => $user->loanApplications()->latest('id')->get(),
            'loanTypes' => User::LOAN_TYPES,
            'terms' => LoanApplication::TERMS,
            'minAmount' => LoanApplication::MIN_AMOUNT,
            'maxAmount' => LoanApplication::MAX_AMOUNT,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'amount' => preg_replace('/[^\d.]/', '', (string) $request->input('amount')),
        ]);

        $data = $request->validate([
            'loan_type' => ['required', Rule::in(array_keys(User::LOAN_TYPES))],
            'amount' => [
                'required',
                'numeric',
                'min:' . LoanApplication::MIN_AMOUNT,
                'max:' . LoanApplication::MAX_AMOUNT,
            ],
            'term_months' => ['required', 'integer', Rule::in(LoanApplication::TERMS)],
            'purpose' => ['required', 'string', 'min:10', 'max:255'],
        ], [
            'loan_type.required' => 'Choose a loan type.',
            'loan_type.in' => 'Choose a valid loan type.',
            'amount.required' => 'Enter the amount you want to borrow.',
            'amount.numeric' => 'Enter the amount as a number.',
            'amount.min' => 'The minimum loan amount is ₱' . number_format(LoanApplication::MIN_AMOUNT) . '.',
            'amount.max' => 'The maximum loan amount is ₱' . number_format(LoanApplication::MAX_AMOUNT) . '.',
            'term_months.required' => 'Choose a payment term.',
            'term_months.in' => 'Choose a valid payment term.',
            'purpose.required' => 'Tell us what the loan is for.',
            'purpose.min' => 'Describe the purpose in at least 10 characters.',
        ]);

        $user = Auth::guard('web')->user();

        $application = $user->loanApplications()->create($data);
        $application->refresh();

        AuditLedger::record(
            $user->id,
            'loan_application',
            $application->application_number . ' submitted for ₱' . number_format((float) $application->amount, 2) . '.'
        );

        return redirect()
            ->route('home')
            ->with('status', 'Your application ' . $application->application_number . ' has been submitted and is now pending review.');
    }
}
