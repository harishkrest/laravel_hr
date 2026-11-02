```blade
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
            --primary: #394174;
            --accent: #e9b354;
            --sidebar: #171a2e;
            --background: #f5f6fb;
            --text: #252940;
            --muted: #81869c;
            --border: #eceef5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 245px;
            background: var(--sidebar);
            padding: 25px 15px;
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
        }

        .brand {
            font-size: 23px;
            font-weight: 800;
            margin: 0 10px 32px;
            letter-spacing: -1px;
            white-space: nowrap;
        }

        .brand span {
            color: var(--accent);
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
            border-radius: 9px;
            font-weight: 500;
            transition: .2s;
        }

        .side-link i {
            font-size: 17px;
            min-width: 20px;
        }

        .side-link:hover,
        .side-link.active {
            color: #fff;
            background: var(--primary);
        }

        /* Main content */
        .main {
            margin-left: 245px;
            padding: 25px 30px;
            min-height: 100vh;
        }

        /* Header */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 27px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .page-title {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.8px;
            margin: 0;
            color: var(--primary);
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
            flex-shrink: 0;
            border-radius: 12px;
            background: #e9eaf4;
            color: var(--primary);
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800;
            font-size: 16px;
        }

        .profile-name {
            font-weight: 700;
            font-size: 12px;
        }

        .profile-role {
            color: var(--muted);
            font-size: 11px;
            margin-top: 3px;
        }

        /* Cards and common elements */
        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(25, 30, 70, .025);
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #2d345f;
            border-color: #2d345f;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .form-control,
        .form-select {
            font-size: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            border-color: #e4e6ef;
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
            padding: 13px 12px;
            vertical-align: middle;
            border-color: #f0f1f7;
        }

        .alert {
            border-radius: 10px;
            font-size: 12px;
        }

        footer {
            color: var(--muted);
            font-size: 10px;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 65px;
                padding: 20px 7px;
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
                padding: 18px 12px;
            }

            .topbar {
                align-items: flex-start;
            }

            .page-title {
                font-size: 19px;
            }

            .profile {
                gap: 6px;
            }

            .avatar {
                width: 32px;
                height: 32px;
            }

            .profile-name {
                max-width: 100px;
                overflow-wrap: anywhere;
            }

            .panel {
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@auth

    @php
        $isAdmin = auth()->user()->isAdmin();
        $currentUser = auth()->user();

        $employee = $isAdmin
            ? null
            : $currentUser->employee;

        $displayName = $employee
            ? trim($employee->first_name . ' ' . $employee->last_name)
            : $currentUser->name;

        $displayName = $displayName ?: $currentUser->email;

        $initial = strtoupper(substr($displayName, 0, 1));
    @endphp

    {{-- Sidebar --}}
    <aside class="sidebar">

        <div class="brand">
            People<span>Desk.</span>
        </div>

        @if($isAdmin)

            {{-- ADMIN SIDEBAR --}}

            <div class="nav-label">WORKSPACE</div>

            <a href="{{ url('/dashboard') }}"
               class="side-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-label">PEOPLE MANAGEMENT</div>

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

            <div class="nav-label">ATTENDANCE & LEAVES</div>

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

        @else

            {{-- EMPLOYEE SIDEBAR --}}

            <div class="nav-label">MY WORKSPACE</div>

            <a href="{{ route('employee.dashboard') }}"
               class="side-link {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>My Dashboard</span>
            </a>

            <a href="{{ route('employee.profile') }}"
               class="side-link {{ request()->routeIs('employee.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span>My Profile</span>
            </a>

            <div class="nav-label">TIME & ATTENDANCE</div>

            <a href="{{ route('employee.attendance') }}"
               class="side-link {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>My Attendance</span>
            </a>

            <div class="nav-label">LEAVE MANAGEMENT</div>

            <a href="{{ route('employee.leaves') }}"
               class="side-link {{ request()->routeIs('employee.leaves') || request()->routeIs('employee.leaves.cancel') ? 'active' : '' }}">
                <i class="bi bi-calendar2-week"></i>
                <span>My Leaves</span>
            </a>

            <a href="{{ route('employee.leaves.apply') }}"
               class="side-link {{ request()->routeIs('employee.leaves.apply') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus"></i>
                <span>Apply for Leave</span>
            </a>

            <div class="nav-label">MY DOCUMENTS</div>

            <a href="{{ route('employee.documents') }}"
               class="side-link {{ request()->routeIs('employee.documents*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>My Documents</span>
            </a>

        @endif

    </aside>

    {{-- Main page --}}
    <main class="main">

        {{-- Common Header --}}
        <header class="topbar">

            <div>
                <h1 class="page-title">
                    @yield('heading', $isAdmin ? 'Admin Dashboard' : 'Employee Portal')
                </h1>

                <div class="page-subtitle">
                    @yield('subtitle', $isAdmin
                        ? 'Manage your people, all in one place.'
                        : 'Your work, attendance and employee information.')
                </div>
            </div>

            <div class="profile">

                <div class="avatar">
                    {{ $initial }}
                </div>

                <div>
                    <div class="profile-name">
                        {{ $displayName }}
                    </div>

                    <div class="profile-role">
                        {{ $isAdmin ? 'HR Administrator' : 'Employee' }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="ms-2">
                    @csrf

                    <button class="btn btn-sm btn-light border"
                            type="submit"
                            title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">Logout</span>
                    </button>
                </form>

            </div>

        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
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

        {{-- Page Content --}}
        @yield('content')

        <footer class="text-center py-4">
            © {{ date('Y') }} PeopleDesk HRMS
        </footer>

    </main>

@else

    {{-- Guest pages, such as login --}}
    @yield('content')

@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
```
