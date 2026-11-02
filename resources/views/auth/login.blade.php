<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HRMS Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        }

        .login-title {
            color: #394174;
            font-weight: 700;
        }

        .btn-login {
            background: #394174;
            color: #fff;
            padding: 12px;
        }

        .btn-login:hover {
            background: #29315f;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="login-card">

    <h3 class="login-title text-center mb-2">HRMS Portal</h3>

    <p class="text-muted text-center mb-4">
        Sign in to your account
    </p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email Address</label>

            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>

            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
        </div>

        <div class="form-check mb-4">
            <input
                type="checkbox"
                name="remember"
                value="1"
                class="form-check-input"
                id="remember"
            >

            <label class="form-check-label" for="remember">
                Remember me
            </label>
        </div>

        <button type="submit" class="btn btn-login w-100">
            Login
        </button>
    </form>

</div>

</body>
</html>
