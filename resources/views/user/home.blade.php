<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My account | ABOT Lending Corporation System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="topbar-brand">
            <img src="{{ asset('pictures/ccdi.png') }}" alt="College seal">
            <span>ABOT Lending Corporation System</span>
        </div>
        <div class="topbar-user">
            <div class="user-meta">
                <strong>{{ $user->name }}</strong>
                <span>{{ $user->borrower_id }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Log out</button>
            </form>
        </div>
    </header>

    <main class="page">
        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

        <section class="page-heading">
            <h1>Welcome, {{ strtok($user->name, ' ') }}</h1>
            <p>Review your borrower profile, apply for a loan, and follow your applications.</p>
        </section>

        <section class="grid">
            <article class="card">
                <div class="card-head">
                    <h2>Your profile</h2>
                    <span class="id-badge">{{ $user->borrower_id }}</span>
                </div>

                <dl class="details">
                    <div>
                        <dt>Full name</dt>
                        <dd>{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt>Date of birth</dt>
                        <dd>{{ $user->date_of_birth->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt>Phone number</dt>
                        <dd>{{ $user->phone_number }}</dd>
                    </div>
                    <div>
                        <dt>Email address</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div class="wide">
                        <dt>Residential address</dt>
                        <dd>{{ $user->address }}</dd>
                    </div>
                    <div>
                        <dt>Valid ID</dt>
                        <dd>{{ $user->idTypeLabel() }}<span class="muted">{{ $user->maskedIdNumber() }}</span></dd>
                    </div>
                    <div>
                        <dt>Employment</dt>
                        <dd>{{ $user->employmentLabel() }}<span class="muted">₱{{ number_format((float) $user->monthly_income, 2) }} per month</span></dd>
                    </div>
                    <div class="wide">
                        <dt>Emergency contact</dt>
                        <dd>{{ $user->emergency_contact_name }} ({{ $user->emergency_contact_relationship }})<span class="muted">{{ $user->emergency_contact_phone }}</span></dd>
                    </div>
                    <div class="wide">
                        <dt>Preferred loan type</dt>
                        <dd>{{ $user->loanTypeLabel() }}</dd>
                    </div>
                </dl>
            </article>

            <article class="card">
                <div class="card-head">
                    <h2>Apply for a loan</h2>
                </div>
                <p class="card-note">You can borrow up to ₱{{ number_format($maxAmount) }} per application.</p>

                <form method="POST" action="{{ route('loan-applications.store') }}" novalidate>
                    @csrf

                    <fieldset class="field">
                        <legend>Loan type</legend>
                        <div class="choice-list">
                            @foreach ($loanTypes as $value => $type)
                                <label class="choice">
                                    <input type="radio" name="loan_type" value="{{ $value }}" {{ old('loan_type', $user->loan_type) === $value ? 'checked' : '' }}>
                                    <span class="choice-body">
                                        <strong>{{ $type['label'] }}</strong>
                                        <small>{{ $type['description'] }}</small>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('loan_type')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div class="form-grid">
                        <div class="field">
                            <label for="amount">Loan amount</label>
                            <div class="input-prefix">
                                <span aria-hidden="true">₱</span>
                                <input type="text" id="amount" name="amount" value="{{ old('amount') }}" placeholder="10,000" inputmode="decimal" autocomplete="off" class="{{ $errors->has('amount') ? 'is-invalid' : '' }}">
                            </div>
                            <p class="hint">₱{{ number_format($minAmount) }} to ₱{{ number_format($maxAmount) }}</p>
                            @error('amount')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="term_months">Payment term</label>
                            <select id="term_months" name="term_months" class="{{ $errors->has('term_months') ? 'is-invalid' : '' }}">
                                @foreach ($terms as $term)
                                    <option value="{{ $term }}" {{ (int) old('term_months', 6) === $term ? 'selected' : '' }}>{{ $term }} {{ $term === 1 ? 'month' : 'months' }}</option>
                                @endforeach
                            </select>
                            @error('term_months')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="purpose">Purpose of the loan</label>
                        <textarea id="purpose" name="purpose" rows="3" placeholder="Briefly tell us what you will use the loan for" class="{{ $errors->has('purpose') ? 'is-invalid' : '' }}">{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Submit application</button>
                </form>
            </article>
        </section>

        <section class="card">
            <div class="card-head">
                <h2>My loan applications</h2>
            </div>

            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Application</th>
                            <th>Loan type</th>
                            <th class="num">Amount</th>
                            <th>Term</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $application)
                            <tr>
                                <td><strong>{{ $application->application_number }}</strong></td>
                                <td>{{ $application->loanTypeLabel() }}</td>
                                <td class="num">₱{{ number_format((float) $application->amount, 2) }}</td>
                                <td>{{ $application->term_months }} mo.</td>
                                <td><span class="badge badge-{{ $application->status }}">{{ ucfirst($application->status) }}</span></td>
                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                <td>{{ $application->admin_remarks ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="empty">You have not submitted any loan applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
