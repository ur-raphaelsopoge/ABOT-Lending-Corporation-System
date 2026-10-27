@extends('admin.layouts.app')

@section('title', 'Receipt Log')

@section('content')
    <section class="page-head">
        <div>
            <h1>Receipt Log</h1>
            <p>Every payment the cooperative has received.</p>
        </div>
    </section>

    <section class="stats stats-compact">
        <article class="stat">
            <p class="stat-label">Total receipts</p>
            <p class="stat-value tone-purple">₱{{ number_format((float) $totalReceipts, 2) }}</p>
        </article>
        <article class="stat">
            <p class="stat-label">Receipts issued</p>
            <p class="stat-value tone-blue">{{ number_format($receiptCount) }}</p>
        </article>
    </section>

    <section class="panel">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Receipt no.</th>
                        <th>Member</th>
                        <th>Method</th>
                        <th class="num">Amount</th>
                        <th>Received</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr>
                            <td><strong>{{ $receipt->receipt_number }}</strong></td>
                            <td>{{ $receipt->member->name }}</td>
                            <td><span class="badge badge-method">{{ ucwords(str_replace('_', ' ', $receipt->payment_method)) }}</span></td>
                            <td class="num">₱{{ number_format((float) $receipt->amount, 2) }}</td>
                            <td>{{ $receipt->received_at->format('M d, Y g:i A') }}</td>
                            <td>{{ $receipt->notes ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">No receipts recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pager', ['items' => $receipts])
    </section>
@endsection
