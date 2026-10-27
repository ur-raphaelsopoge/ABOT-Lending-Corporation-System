@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="page-head">
        <div>
            <h1>Welcome, {{ auth('admin')->user()->name }}</h1>
            <p>Here is the current state of your records.</p>
        </div>
    </section>

    <section class="stats">
        <article class="stat">
            <p class="stat-label">Total Members</p>
            <p class="stat-value tone-blue">{{ number_format($totalMembers) }}</p>
        </article>
        <article class="stat">
            <p class="stat-label">Total Capital Shares</p>
            <p class="stat-value tone-green">₱{{ number_format((float) $totalShares, 2) }}</p>
        </article>
        <article class="stat">
            <p class="stat-label">Total Receipts</p>
            <p class="stat-value tone-purple">₱{{ number_format((float) $totalReceipts, 2) }}</p>
        </article>
        <article class="stat">
            <p class="stat-label">Active Loans</p>
            <p class="stat-value tone-amber">{{ number_format($activeLoans) }}</p>
        </article>
        <article class="stat">
            <p class="stat-label">Pending Applications</p>
            <p class="stat-value tone-red">{{ number_format($pendingApplications) }}</p>
        </article>
    </section>

    <section class="panel panel-spaced">
        <div class="panel-head">
            <h3>Latest loan applications</h3>
            <a class="text-link" href="{{ route('admin.loans.index') }}">Open Loan Portfolio</a>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Application</th>
                        <th>Borrower</th>
                        <th>Loan type</th>
                        <th class="num">Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentApplications as $application)
                        <tr>
                            <td>
                                <strong>{{ $application->application_number }}</strong>
                                <span class="muted">{{ $application->created_at->format('M d, Y') }}</span>
                            </td>
                            <td>
                                {{ $application->user->name }}
                                <span class="muted">{{ $application->user->borrower_id }}</span>
                            </td>
                            <td>{{ $application->loanTypeLabel() }}</td>
                            <td class="num">₱{{ number_format((float) $application->amount, 2) }}</td>
                            <td><span class="badge badge-{{ $application->status }}">{{ ucfirst($application->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">No loan applications have been submitted yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="split">
        <article class="panel">
            <div class="panel-head">
                <h3>Recent registrations</h3>
                <a class="text-link" href="{{ route('admin.members.index') }}">View all</a>
            </div>
            <ul class="people">
                @forelse ($recentMembers as $member)
                    <li>
                        <span class="avatar">{{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}</span>
                        <div class="people-meta">
                            <strong>{{ $member->name }}</strong>
                            <span>{{ $member->borrower_id }}</span>
                        </div>
                        <time class="people-time" datetime="{{ $member->created_at->toIso8601String() }}">{{ $member->created_at->diffForHumans() }}</time>
                    </li>
                @empty
                    <li class="empty">No borrowers have registered yet.</li>
                @endforelse
            </ul>
        </article>

        <article class="panel">
            <div class="panel-head">
                <h3>Recent receipt activity</h3>
                <a class="text-link" href="{{ route('admin.receipt-logs.index') }}">View all</a>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Receipt</th>
                            <th>Member</th>
                            <th class="num">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentReceipts as $receipt)
                            <tr>
                                <td>
                                    <strong>{{ $receipt->receipt_number }}</strong>
                                    <span class="muted">{{ $receipt->received_at->format('M d, Y') }}</span>
                                </td>
                                <td>{{ $receipt->member->name }}</td>
                                <td class="num">₱{{ number_format((float) $receipt->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">No receipts recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
@endsection
