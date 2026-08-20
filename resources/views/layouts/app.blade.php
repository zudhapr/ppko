<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Smart Fertigation')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
        }

        .navbar {
            background: #111827;
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 18px;
        }

        .navbar a:hover {
            opacity: .8;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card,
        .section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px;
            margin-bottom: 25px;
        }

        .card small {
            color: #6b7280;
        }

        .card h2 {
            margin: 8px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        th {
            background: #f9fafb;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            margin-bottom: 10px;
        }

        textarea {
            resize: vertical;
        }

        button,
        .btn {
            border: none;
            border-radius: 7px;
            padding: 9px 13px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 12px;
        }

        .active {
            background: #dcfce7;
            color: #166534;
        }

        .inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .menu-card {
            display: block;
            text-decoration: none;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 18px;
            border-radius: 10px;
            color: #111827;
        }

        .menu-card:hover {
            background: #f3f4f6;
        }

        .menu-card strong {
            display: block;
            margin-bottom: 5px;
        }

.schedule-group {
    margin-bottom: 28px;
}

.hst-title {
    background: #111827;
    color: white;
    padding: 12px 15px;
    border-radius: 8px 8px 0 0;
    font-size: 16px;
    font-weight: bold;
}

.schedule-table-wrapper {
    overflow-x: auto;
    border: 1px solid #e5e7eb;
    border-top: none;
    border-radius: 0 0 8px 8px;
}

.schedule-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.schedule-table th {
    background: #f9fafb;
    color: #374151;
    font-size: 13px;
    text-transform: uppercase;
}

.schedule-table th,
.schedule-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e5e7eb;
    vertical-align: middle;
}

.schedule-table tbody tr:last-child td {
    border-bottom: none;
}

.schedule-table tbody tr:hover {
    background: #f9fafb;
}

.schedule-time {
    font-weight: bold;
    font-size: 16px;
}

.schedule-valve small {
    display: block;
    margin-top: 3px;
    color: #6b7280;
}

.today-label {
    display: inline-block;
    margin-left: 8px;
    padding: 3px 8px;
    border-radius: 20px;
    background: #dcfce7;
    color: #166534;
    font-size: 11px;
}

.page-header {
    margin-bottom: 22px;
}

.page-header h1 {
    margin-bottom: 5px;
}

.page-header p {
    margin-top: 0;
    color: #6b7280;
}

.form-grid label {
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
    font-weight: 600;
}

.actions form {
    margin: 0;
}

@media (max-width: 768px) {
    .container {
        padding: 0 12px;
    }

    .navbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .navbar a {
        margin-left: 0;
        margin-right: 12px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }
}

.valve-demo-grid {
    display: grid;
    grid-template-columns:
        repeat(auto-fit, minmax(300px, 1fr));
    gap: 18px;
}

.valve-demo-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    background: #f9fafb;
}

.valve-demo-card h3 {
    margin-top: 0;
    margin-bottom: 5px;
}

.valve-demo-card p {
    margin-top: 0;
    color: #6b7280;
}

        @yield('styles')
    </style>
</head>

<body>

<nav class="navbar">

    <h2>
        Smart Fertigation
    </h2>

    <div>
        <a href="{{ url('/') }}">
            Dashboard
        </a>

        <a href="{{ route('jadwal.index') }}">
            Jadwal
        </a>

        <a href="{{ route('demo.index') }}">
            Demo
        </a>

        <a href="{{ route('profiles.index') }}">
            Profil
        </a>

        <a href="{{ route('valves.index') }}">
            Valve
        </a>
    </div>

</nav>


<div class="container">

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')

</div>

@yield('scripts')

</body>
</html>