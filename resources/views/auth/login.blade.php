<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        .form-check-input:checked {
            background-color: #999999;
            border-color: #999999;
        }

        .password-toggle {
            cursor: pointer;
        }

        .form-control {
            background-color: rgba(176, 196, 222, 1);
        }

        .form-control:focus {
            background-color: rgba(176, 196, 222, 0.5);
        }

        .form-control::placeholder {
            color: #f5f5f5;
            opacity: 1;
        }

        .hero {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 50%;
            object-fit: cover;
            z-index: -1;
            border-radius: 0px 0px 30px 30px;
        }
    </style>
</head>

<body class="bg-light">
    <img src="https://www.republikorp.com/dummies/hero.png" class="hero img-fluid" alt="hero-image">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5">
            <div class="card border-0 shadow-sm bg-dark">
                <div class="card-body p-4 p-md-5">
                    <img src="{{ asset('asset/logo-republikkorp.png') }}" alt="Logo RepublikKorp" class="mb-3"
                        style="width: 250px;">
                    <p class="text-light mb-4">Silakan login untuk melanjutkan.</p>

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label text-light">Email*</label>
                            <input id="email" type="email" name="email"
                                class="form-control text-light border-dark @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="Masukan email" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-light">Password*</label>
                            <div class="input-group">
                                <input id="password" type="password" name="password"
                                    class="form-control border-dark text-light @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password" required>
                                <button class="btn border-dark password-toggle" type="button" id="togglePassword"
                                    aria-label="Tampilkan password" aria-pressed="false"
                                    style="background-color: rgba(176, 196, 222, 0.7)">
                                    <span id="passwordToggleIcon" aria-hidden="true"></span>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-light" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-dark text-white fw-bold border-light border-2 btn w-100 "
                            style="border-radius: 0px 20px 0px 20px">LOG IN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordToggleIcon = document.getElementById('passwordToggleIcon');
        const eyeIcon =
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8a13.133 13.133 0 0 1-1.66 2.043C11.879 11.332 10.12 12.5 8 12.5s-3.879-1.168-5.168-2.457A13.133 13.133 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z"/></svg>';
        const eyeSlashIcon =
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M13.359 11.238C14.39 10.374 15.168 9.26 16 8c-3-5.5-8-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.938 5.938 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457.62.62 1.174 1.288 1.66 2.043-.487.755-1.04 1.423-1.66 2.043-.303.303-.627.586-.969.846l.82.849zM11.297 9.176a3.5 3.5 0 0 0-4.473-4.473l.793.793a2.5 2.5 0 0 1 2.884 2.884l.796.796z"/><path d="M3.35 5.47A13.134 13.134 0 0 0 1.173 8a13.133 13.133 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5a5.937 5.937 0 0 0 2.329-.47l.77.77A7.03 7.03 0 0 1 8 13.5S3 13.5 0 8c.938-1.72 1.997-2.88 3.35-3.53z"/><path d="M2.646 1.646a.5.5 0 1 1 .708-.708l11 11a.5.5 0 0 1-.708.708l-11-11z"/></svg>';

        if (passwordToggleIcon) {
            passwordToggleIcon.innerHTML = eyeIcon;
        }

        togglePasswordBtn?.addEventListener('click', function() {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            passwordToggleIcon.innerHTML = isHidden ? eyeSlashIcon : eyeIcon;
            togglePasswordBtn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            togglePasswordBtn.setAttribute('aria-pressed', String(isHidden));
        });
    </script>
</body>

</html>
