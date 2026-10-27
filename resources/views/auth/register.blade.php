@extends('layouts.auth')

@section('title', 'Create account | ABOT Lending Corporation System')
@section('brand_eyebrow', 'Borrower registration')
@section('brand_title', 'Join ABOT Lending Corporation')
@section('brand_lead', 'Create your borrower account to apply for a loan and follow every application from one secure place.')
@section('eyebrow', 'Get started')
@section('heading', 'Create your account')
@section('subtitle', 'Tell us about yourself so we can evaluate your loan applications faster.')

@section('content')
    @if ($errors->any())
        <div class="alert alert-error" role="alert">Please fix the highlighted fields and try again.</div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" novalidate>
        @csrf

        <h3 class="section-title">Personal information</h3>

        <div class="field">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Juan Dela Cruz" autocomplete="name" autofocus class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="date_of_birth">Date of birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ now()->subYears(18)->toDateString() }}" autocomplete="bday" class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}">
                @error('date_of_birth')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="phone_number">Phone number</label>
                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="0917 123 4567" inputmode="tel" autocomplete="tel" class="{{ $errors->has('phone_number') ? 'is-invalid' : '' }}">
                @error('phone_number')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@email.com" autocomplete="email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="address">Residential address</label>
            <textarea id="address" name="address" rows="3" placeholder="House no., street, barangay, city, province" autocomplete="street-address" class="{{ $errors->has('address') ? 'is-invalid' : '' }}">{{ old('address') }}</textarea>
            @error('address')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <h3 class="section-title">Identification and employment</h3>

        <div class="form-grid">
            <div class="field">
                <label for="id_type">Valid ID type</label>
                <select id="id_type" name="id_type" class="{{ $errors->has('id_type') ? 'is-invalid' : '' }}">
                    <option value="">Select ID type</option>
                    @foreach ($idTypes as $value => $label)
                        <option value="{{ $value }}" {{ old('id_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('id_type')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="id_number">ID number</label>
                <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="As shown on your ID" autocomplete="off" class="{{ $errors->has('id_number') ? 'is-invalid' : '' }}">
                @error('id_number')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="employment_status">Employment status</label>
                <select id="employment_status" name="employment_status" class="{{ $errors->has('employment_status') ? 'is-invalid' : '' }}">
                    <option value="">Select status</option>
                    @foreach ($employmentStatuses as $value => $label)
                        <option value="{{ $value }}" {{ old('employment_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('employment_status')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="monthly_income">Monthly income</label>
                <div class="input-prefix">
                    <span aria-hidden="true">₱</span>
                    <input type="text" id="monthly_income" name="monthly_income" value="{{ old('monthly_income') }}" placeholder="0.00" inputmode="decimal" autocomplete="off" class="{{ $errors->has('monthly_income') ? 'is-invalid' : '' }}">
                </div>
                @error('monthly_income')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <h3 class="section-title">Emergency contact</h3>

        <div class="form-grid">
            <div class="field">
                <label for="emergency_contact_name">Contact name</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="Full name" autocomplete="off" class="{{ $errors->has('emergency_contact_name') ? 'is-invalid' : '' }}">
                @error('emergency_contact_name')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="emergency_contact_relationship">Relationship</label>
                <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship') }}" placeholder="Spouse, parent, sibling" autocomplete="off" class="{{ $errors->has('emergency_contact_relationship') ? 'is-invalid' : '' }}">
                @error('emergency_contact_relationship')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="field">
            <label for="emergency_contact_phone">Contact phone number</label>
            <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="0917 123 4567" inputmode="tel" autocomplete="off" class="{{ $errors->has('emergency_contact_phone') ? 'is-invalid' : '' }}">
            @error('emergency_contact_phone')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <h3 class="section-title">Loan type (Uri ng Loan)</h3>

        <fieldset class="field">
            <legend class="visually-hidden">Loan type</legend>
            <div class="choice-list">
                @foreach ($loanTypes as $value => $type)
                    <label class="choice">
                        <input type="radio" name="loan_type" value="{{ $value }}" {{ old('loan_type') === $value ? 'checked' : '' }}>
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

        <h3 class="section-title">Account security</h3>

        <div class="form-grid">
            <div class="field">
                <label for="password">Password</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" placeholder="Create a password" autocomplete="new-password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                    <button type="button" class="toggle-password" data-toggle-password="password" aria-label="Show password" aria-pressed="false">
                        @include('partials.eye-icon')
                    </button>
                </div>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <div class="password-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" autocomplete="new-password">
                    <button type="button" class="toggle-password" data-toggle-password="password_confirmation" aria-label="Show password" aria-pressed="false">
                        @include('partials.eye-icon')
                    </button>
                </div>
            </div>
        </div>

        <p class="hint">Use at least 8 characters with letters and numbers. Your Borrower ID is assigned automatically after you register.</p>

        <button type="submit" class="btn btn-primary">Create account</button>
    </form>

    <p class="switch-auth">Already registered? <a class="text-link" href="{{ route('login') }}">Sign in</a></p>
@endsection
