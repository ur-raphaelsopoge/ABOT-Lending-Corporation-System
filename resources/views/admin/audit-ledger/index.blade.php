@extends('admin.layouts.app')

@section('title', 'Audit Ledger')

@section('content')
    <section class="page-head">
        <div>
            <h1>Audit Ledger</h1>
            <p>{{ number_format($entryCount) }} recorded {{ \Illuminate\Support\Str::plural('action', $entryCount) }} across the system.</p>
        </div>
    </section>

    <section class="panel">
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Performed by</th>
                        <th>Action</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entries as $entry)
                        <tr>
                            <td>{{ $entry->created_at->format('M d, Y g:i A') }}</td>
                            <td>
                                @if ($entry->admin)
                                    {{ $entry->admin->name }}
                                    <span class="muted">Administrator</span>
                                @elseif ($entry->user)
                                    {{ $entry->user->name }}
                                    <span class="muted">{{ $entry->user->borrower_id }}</span>
                                @else
                                    <span class="muted">Removed account</span>
                                @endif
                            </td>
                            <td><span class="badge badge-action">{{ ucwords(str_replace('_', ' ', $entry->action)) }}</span></td>
                            <td>{{ $entry->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="empty">No activity has been recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('partials.pager', ['items' => $entries])
    </section>
@endsection
