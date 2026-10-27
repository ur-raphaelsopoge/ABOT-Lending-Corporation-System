@extends('admin.layouts.app')

@section('title', 'Capital Shares')

@section('content')
    <section class="page-head">
        <div>
            <h1>Capital Shares</h1>
            <p>Contributions made by members to the cooperative.</p>
        </div>
    </section>

    <section class="stats stats-compact">
        <article class="stat">
            <p class="stat-label">Total capital shares</p>
            <p class="stat-value tone-green">₱{{ number_format((float) $totalShares, 2) }}</p>
        </article>
        <article class="stat">
            <p class="stat-label">Contributions recorded</p>
            <p class="stat-value tone-blue">{{ number_format($contributionCount) }}</p>
        </article>
    </section>

    <section class="panel">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Member</th>
                        <th>Borrower ID</th>
                        <th class="num">Amount</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($shares as $share)
                        <tr>
                            <td>{{ $share->share_date->format('M d, Y') }}</td>
                            <td><strong>{{ $share->member->name }}</strong></td>
                            <td>{{ $share->member->borrower_id }}</td>
                            <td class="num">₱{{ number_format((float) $share->amount, 2) }}</td>
                            <td>{{ $share->notes ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">No capital shares recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pager', ['items' => $shares])
    </section>
@endsection
