<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'PeopleDesk HRMS')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --primary: #5146e5;
            --sidebar: #171a2e;
            --background: #f5f6fb;
            --text: #252940;
            --muted: #81869c;
            --border: #eceef5;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 245px;
            background: var(--sidebar);
            padding: 27px 16px;
            color: #fff;
            z-index: 1000;
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            margin: 0 10px 36px;
            letter-spacing: -1px;
        }

        .brand span {
            color: #a7a0ff;
        }

        .nav-label {
            color: #858aa5;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.3px;
            margin: 24px 12px 10px;
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            margin: 4px 0;
            color: #b9bdd0;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: .2s;
        }

        .side-link i {
            font-size: 17px;
        }

        .side-link:hover,
        .side-link.active {
            color: #fff;
            background: var(--primary);
        }

        .main {
            margin-left: 245px;
            padding: 28px 32px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 27px;
            gap: 15px;
        }

        .page-title {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.8px;
            margin: 0;
        }

        .page-subtitle {
            color: var(--muted);
            margin-top: 7px;
            font-size: 12px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #e6e3ff;
            color: var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(25,30,70,.025);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            border-radius: 9px;
            font-weight: 600;
            padding: 10px 15px;
        }

        .btn-primary:hover {
            background: #4035ca;
            border-color: #4035ca;
        }

        .btn-light {
            border: 1px solid var(--border);
            background: #fff;
            border-radius: 9px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            font-size: 12px;
            padding: 11px 12px;
            border-radius: 9px;
            border-color: #e4e6ef;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(81,70,229,.1);
        }

        .table thead th {
            background: #fafaff;
            color: var(--muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
            white-space: nowrap;
            padding: 14px 12px;
            border-bottom: 1px solid var(--border);
        }

        .table tbody td {
            font-size: 12px;
            padding: 15px 12px;
            vertical-align: middle;
            border-color: #f0f1f7;
        }

        .table tbody tr:hover {
            background: #fafaff;
        }

        .employee-name {
            font-weight: 700;
            color: var(--text);
        }

        .employee-code {
            color: var(--muted);
            font-size: 10px;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            border-radius: 7px;
            padding: 6px 9px;
            font-size: 10px;
            font-weight: 700;
        }

        .status-active {
            color: #15945c;
            background: #e5f9ef;
        }

        .status-inactive {
            color: #6b7280;
            background: #f0f1f5;
        }

        .status-resigned,
        .status-terminated {
            color: #c24141;
            background: #ffebeb;
        }

        .search-box {
            max-width: 330px;
        }

        .alert {
            border-radius: 10px;
            font-size: 12px;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--primary);
            font-size: 12px;
        }

        .active > .page-link,
        .page-link.active {
            background: var(--primary);
            border-color: var(--primary);
        }

        .detail-label {
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 7px;
        }

        .detail-value {
            font-size: 13px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 65px;
                padding: 20px 8px;
            }

            .brand {
                font-size: 13px;
                margin: 0 2px 30px;
                overflow: hidden;
            }

            .brand span,
            .side-link span,
            .nav-label {
                display: none;
            }

            .side-link {
                justify-content: center;
                padding: 13px 4px;
            }

            .side-link i {
                font-size: 19px;
            }

            .main {
                margin-left: 65px;
                padding: 20px 13px;
            }

            .page-title {
                font-size: 21px;
            }

            .panel {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<aside class="sidebar">

    <div class="brand">People<span>Desk.</span></div>

    <div class="nav-label">WORKSPACE</div>

    <a href="{{ url('/dashboard') }}" class="side-link">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('employees.index') }}"
       class="side-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i>
        <span>Employees</span>
    </a>
    <a href="{{ route('departments.index') }}"
	   class="side-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">

		<i class="bi bi-building"></i>
		<span>Departments</span>

	</a>
	<a href="{{ route('designations.index') }}"
	   class="side-link {{ request()->routeIs('designations.*') ? 'active' : '' }}">

		<i class="bi bi-person-badge"></i>
		<span>Designations</span>

	</a>
	<a href="{{ route('attendance.index') }}"
	   class="side-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">

		<i class="bi bi-calendar-check"></i>
		<span>Attendance</span>

	</a>
	<a href="{{ route('leave-requests.index') }}"
	   class="side-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
		<i class="bi bi-calendar2-week"></i>
		<span>Leave Management</span>
	</a>
	<a href="{{ route('leave-balances.index') }}"
	   class="side-link {{ request()->routeIs('leave-balances.*') ? 'active' : '' }}">
		<i class="bi bi-wallet2"></i>
		<span>Leave Balance</span>
	</a>
	

    <div class="nav-label">MANAGEMENT</div>
    

    <a href="#" class="side-link">
        <i class="bi bi-calendar-check"></i>
        <span>Attendance</span>
    </a>

    <a href="#" class="side-link">
        <i class="bi bi-calendar2-week"></i>
        <span>Leave Management</span>
    </a>

    <a href="#" class="side-link">
        <i class="bi bi-wallet2"></i>
        <span>Payroll</span>
    </a>

</aside>

<main class="main">

    <header class="topbar">
        <div>
            <h1 class="page-title">@yield('heading', 'PeopleDesk')</h1>
            <div class="page-subtitle">
                Manage your people, all in one place.
            </div>
        </div>

        <div class="profile">
            <div class="avatar">A</div>
            <div>
                <div class="fw-bold">Admin User</div>
                <div class="text-secondary" style="font-size:11px">
                    HR Administrator
                </div>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

    <footer class="text-center text-secondary py-4"
            style="font-size:10px">
        © {{ date('Y') }} PeopleDesk HRMS
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
