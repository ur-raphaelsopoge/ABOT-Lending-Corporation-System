insert into public.users (
    name, date_of_birth, phone_number, email, address,
    id_type, id_number, employment_status, monthly_income,
    emergency_contact_name, emergency_contact_relationship, emergency_contact_phone,
    loan_type, password
)
select
    v.name, v.date_of_birth::date, v.phone_number, v.email, v.address,
    v.id_type, v.id_number, v.employment_status, v.monthly_income,
    v.emergency_contact_name, v.emergency_contact_relationship, v.emergency_contact_phone,
    v.loan_type,
    replace(extensions.crypt(encode(extensions.gen_random_bytes(12), 'hex'), extensions.gen_salt('bf', 10)), '$2a$', '$2y$')
from (values
    ('Maria Santos', '1992-03-14', '09171234567', 'maria.santos@example.com', '12 Mabini Street, Quezon City',
        'philsys', 'PSN-1001-2002', 'employed', 28000.00, 'Ramon Santos', 'Father', '09181234567', 'personal'),
    ('Juan Dela Cruz', '1988-07-02', '09281234567', 'juan.delacruz@example.com', '45 Rizal Avenue, Manila',
        'drivers_license', 'N01-23-456789', 'business_owner', 45000.00, 'Liza Dela Cruz', 'Spouse', '09391234567', 'business'),
    ('Ana Reyes', '1995-11-23', '09451234567', 'ana.reyes@example.com', '8 Bonifacio Road, Makati City',
        'umid', 'UMID-3300-4455', 'self_employed', 22000.00, 'Carla Reyes', 'Sister', '09561234567', 'emergency'),
    ('Carlo Mendoza', '1990-01-30', '09061234567', 'carlo.mendoza@example.com', '77 Luna Street, Pasig City',
        'sss', 'SSS-3412-9087', 'employed', 35000.00, 'Mina Mendoza', 'Mother', '09171230000', 'personal')
) as v (
    name, date_of_birth, phone_number, email, address,
    id_type, id_number, employment_status, monthly_income,
    emergency_contact_name, emergency_contact_relationship, emergency_contact_phone, loan_type
)
on conflict do nothing;

insert into public.capital_shares (member_id, amount, share_date, notes)
select u.id, v.amount, v.share_date, v.notes
from (values
    ('maria.santos@example.com', 1000.00, current_date - 45, 'Initial capital share'),
    ('juan.delacruz@example.com', 1500.00, current_date - 40, 'Initial capital share'),
    ('ana.reyes@example.com', 500.00, current_date - 20, 'Initial capital share'),
    ('maria.santos@example.com', 500.00, current_date - 10, 'Additional contribution'),
    ('carlo.mendoza@example.com', 2000.00, current_date - 3, 'Initial capital share')
) as v (email, amount, share_date, notes)
join public.users u on u.email = v.email;

insert into public.receipt_logs (member_id, receipt_number, amount, payment_method, received_at, notes)
select u.id, v.receipt_number, v.amount, v.payment_method, v.received_at, v.notes
from (values
    ('maria.santos@example.com', 'OR-0001', 1000.00, 'cash', now() - interval '45 days', 'Capital share payment'),
    ('juan.delacruz@example.com', 'OR-0002', 1500.00, 'gcash', now() - interval '40 days', 'Capital share payment'),
    ('maria.santos@example.com', 'OR-0003', 850.00, 'cash', now() - interval '12 days', 'Loan payment'),
    ('ana.reyes@example.com', 'OR-0004', 500.00, 'bank_transfer', now() - interval '6 days', 'Capital share payment'),
    ('carlo.mendoza@example.com', 'OR-0005', 2000.00, 'cash', now() - interval '3 days', 'Capital share payment')
) as v (email, receipt_number, amount, payment_method, received_at, notes)
join public.users u on u.email = v.email
on conflict (receipt_number) do nothing;

insert into public.loan_applications (user_id, loan_type, amount, term_months, purpose, status, admin_remarks, reviewed_by, reviewed_at)
select u.id, v.loan_type, v.amount, v.term_months, v.purpose, v.status, v.admin_remarks,
    case when v.status = 'pending' then null else (select id from public.admins order by id limit 1) end,
    case when v.status = 'pending' then null else now() - interval '2 days' end
from (values
    ('maria.santos@example.com', 'personal', 15000.00, 6, 'Tuition fees for my younger sibling', 'approved', 'Approved. Documents verified.'),
    ('juan.delacruz@example.com', 'business', 40000.00, 12, 'Additional stock for my sari-sari store', 'approved', null),
    ('ana.reyes@example.com', 'emergency', 8000.00, 3, 'Hospital bills for a family member', 'pending', null),
    ('carlo.mendoza@example.com', 'personal', 50000.00, 24, 'Home repairs after the recent typhoon', 'rejected', 'Requested amount exceeds current capacity.')
) as v (email, loan_type, amount, term_months, purpose, status, admin_remarks)
join public.users u on u.email = v.email;
