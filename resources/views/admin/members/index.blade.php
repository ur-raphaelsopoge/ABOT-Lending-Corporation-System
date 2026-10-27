@extends('admin.layouts.app')

@section('title', 'Member List')

@section('content')
    <section class="page-head">
        <div>
            <h1>Member List</h1>
            <p>{{ number_format($totalMembers) }} registered {{ \Illuminate\Support\Str::plural('borrower', $totalMembers) }} and their standing.</p>
        </div>
        <form method="GET" action="{{ route('admin.members.index') }}" class="search">
            <input type="search" name="q" value="{{ $search }}" placeholder="Search name, borrower ID, email, or phone" aria-label="Search members">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </section>

    <section class="panel">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Borrower ID</th>
                        <th>Phone</th>
                        <th>Employment</th>
                        <th>Preferred loan</th>
                        <th class="num">Capital shares</th>
                        <th class="num">Approved loans</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <td>
                                <div class="cell-person">
                                    <span class="avatar">{{ mb_strtoupper(mb_substr($member->name, 0, 1)) }}</span>
                                    <div>
                                        <strong>{{ $member->name }}</strong>
                                        <span class="muted">{{ $member->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td><strong>{{ $member->borrower_id }}</strong></td>
                            <td>{{ $member->phone_number }}</td>
                            <td>
                                {{ $member->employmentLabel() }}
                                <span class="muted">₱{{ number_format((float) $member->monthly_income, 2) }} / month</span>
                            </td>
                            <td><span class="badge badge-action">{{ $member->loanTypeLabel() }}</span></td>
                            <td class="num">₱{{ number_format((float) $member->shares_total, 2) }}</td>
                            <td class="num">{{ $member->approved_loans_count }}</td>
                            <td>{{ $member->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty">No members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pager', ['items' => $members])
    </section>
@endsection
