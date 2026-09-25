<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SAPTA | Login</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4ef;
            color: #172015;
            padding: 30px;
        }

        .page {
            width: 100%;
            min-height: calc(100vh - 60px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 640px;
            min-height: 700px;
            display: grid;
            grid-template-columns: 100%;
            background: #ffffff;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.12);
        }

        /* =========================================================
           LEFT SIDE
        ========================================================= */

        .left-side {
            position: relative;
            overflow: hidden;
            padding: 55px 60px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;

            background:
                linear-gradient(
                    180deg,
                    #30b5e7 0%,
                    #0b99cf 43%,
                    #0879a8 58%,
                    #88532f 59%,
                    #71421f 76%,
                    #432513 100%
                );
        }

        .sun {
            position: absolute;
            width: 170px;
            height: 170px;
            top: 105px;
            right: -30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.8);
            filter: blur(12px);
            opacity: 0.8;
            pointer-events: none;
        }

        .brand {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .logo {
            width: 82px;
            height: 82px;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 6px 7px rgba(0, 0, 0, 0.20));
        }
        .logo-circle {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.20), 0 0 0 5px rgba(255, 255, 255, 0.35);
            flex-shrink: 0;
        }

        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }


        .brand-text {
            color: #ffffff;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 34px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .left-content {
            position: relative;
            z-index: 5;
            max-width: 530px;
            margin-top: auto;
            margin-bottom: 45px;
        }

        .left-title {
            font-family: Georgia, "Times New Roman", serif;
            color: #050505;
            font-size: 55px;
            line-height: 1.05;
            font-weight: 700;
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .left-description {
            color: rgba(255, 255, 255, 0.95);
            font-size: 20px;
            line-height: 1.6;
        }

        .clouds {
            position: absolute;
            left: 60px;
            bottom: 45px;
            display: flex;
            gap: 35px;
            opacity: 0.85;
        }

        .cloud {
            width: 45px;
            height: 13px;
            position: relative;
            background: #ffffff;
            border-radius: 30px;
        }

        .cloud::before {
            content: "";
            position: absolute;
            width: 24px;
            height: 24px;
            left: 8px;
            bottom: 4px;
            background: #ffffff;
            border-radius: 50%;
        }

        .cloud::after {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            left: 26px;
            bottom: 4px;
            background: #ffffff;
            border-radius: 50%;
        }

        /* =========================================================
           RIGHT SIDE
        ========================================================= */

        .right-side {
            background: #ffffff;
            padding: 80px 70px;
            display: flex;
            align-items: center;
        }

        .form-container {
            width: 100%;
            max-width: 580px;
            margin: auto;
        }
        /* =========================================================
           LOGO JUU YA FORM
        ========================================================= */
        .login-logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px;
            box-shadow: 0 20px 50px rgba(21, 158, 210, 0.30), 0 0 0 8px rgba(255, 255, 255, 0.7);
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }

        .login-brand-name {
            text-align: center;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 32px;
            font-weight: 700;
            color: #172015;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .login-brand-tagline {
            text-align: center;
            font-size: 13px;
            color: #7c887b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 32px;
        }


        .eyebrow {
            color: #4e7f22;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .welcome {
            font-family: Georgia, "Times New Roman", serif;
            font-size: 54px;
            line-height: 1.05;
            font-weight: 700;
            color: #172015;
            margin-bottom: 17px;
        }

        .subtitle {
            color: #7c887b;
            font-size: 19px;
            line-height: 1.5;
            margin-bottom: 48px;
        }

        /* =========================================================
           ALERTS
        ========================================================= */

        .alert {
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert-error {
            background: #fff3f3;
            border: 1px solid #f0c4c4;
            color: #a51d1d;
        }

        .alert-success {
            background: #f0faf1;
            border: 1px solid #c6e4c9;
            color: #26702d;
        }

        /* =========================================================
           FORM
        ========================================================= */

        .field {
            margin-bottom: 25px;
        }

        .label {
            display: block;
            font-size: 16px;
            font-weight: 700;
            color: #4f5a4c;
            margin-bottom: 9px;
        }

        .input {
            width: 100%;
            height: 62px;
            border: 1.5px solid #d7ddd7;
            border-radius: 10px;
            padding: 0 18px;
            background: #ffffff;
            font-size: 17px;
            color: #172015;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .input:hover {
            border-color: #c3cbc3;
        }

        .input:focus {
            border-color: #159ed2;
            box-shadow: 0 0 0 4px rgba(21, 158, 210, 0.10);
        }

        .input::placeholder {
            color: #9ba29a;
        }

        /* =========================================================
           PASSWORD
        ========================================================= */

        .password-box {
            position: relative;
        }

        .password-box .input {
            padding-right: 65px;
        }

        .show-password {
            position: absolute;
            right: 8px;
            top: 8px;
            width: 46px;
            height: 46px;
            border: 1px solid #d1d6d1;
            border-radius: 9px;
            background: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #303530;
        }

        .show-password:hover {
            background: #f4fafc;
            color: #159ed2;
            border-color: #a9cddd;
        }

        .show-password svg {
            width: 22px;
            height: 22px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* =========================================================
           OPTIONS
        ========================================================= */

        .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 4px;
            margin-bottom: 32px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #687166;
            font-size: 15px;
            cursor: pointer;
            user-select: none;
        }

        .remember input {
            width: 19px;
            height: 19px;
            accent-color: #159ed2;
            cursor: pointer;
        }

        .forgot {
            color: #008bc5;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
        }

        .forgot:hover {
            color: #006f9e;
            text-decoration: underline;
        }

        /* =========================================================
           LOGIN BUTTON
        ========================================================= */

        .login-button {
            width: 100%;
            height: 62px;
            border: none;
            border-radius: 10px;
            background: #159ed2;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition:
                background 0.2s ease,
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .login-button:hover {
            background: #087faf;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(21, 158, 210, 0.20);
        }

        .login-button:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .button-content {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .footer {
            text-align: center;
            color: #929990;
            font-size: 13px;
            margin-top: 28px;
        }

        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 1000px) {
            body {
                padding: 18px;
            }

            .page {
                min-height: calc(100vh - 36px);
            }

            .login-card {
                min-height: 650px;
            }

            .left-side,
            .right-side {
                padding: 80px 70px;
            }

            .left-title,
            .welcome {
                font-size: 43px;
            }

            .subtitle {
                font-size: 16px;
            }

            .input {
                height: 58px;
                font-size: 16px;
            }
        }

        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 760px) {
            body {
                padding: 0;
            }

            .page {
                min-height: 100vh;
            }

            .login-card {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                border-radius: 0;
                box-shadow: none;
            }

            .left-side {
                min-height: 350px;
                padding: 30px 25px;
            }

            .logo {
                width: 65px;
                height: 65px;
            }
        .logo-circle {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.20), 0 0 0 5px rgba(255, 255, 255, 0.35);
            flex-shrink: 0;
        }

        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }


            .brand-text {
                font-size: 27px;
            }

            .left-content {
                margin-bottom: 20px;
            }

            .left-title {
                font-size: 36px;
                margin-bottom: 15px;
            }

            .left-description {
                font-size: 15px;
                line-height: 1.5;
            }

            .clouds {
                display: none;
            }

            .right-side {
                padding: 80px 70px;
            }
        /* =========================================================
           LOGO JUU YA FORM
        ========================================================= */
        .login-logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }

        .login-logo {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 14px;
            box-shadow: 0 20px 50px rgba(21, 158, 210, 0.30), 0 0 0 8px rgba(255, 255, 255, 0.7);
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
            display: block;
        }

        .login-brand-name {
            text-align: center;
            font-family: Georgia, "Times New Roman", serif;
            font-size: 32px;
            font-weight: 700;
            color: #172015;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }

        .login-brand-tagline {
            text-align: center;
            font-size: 13px;
            color: #7c887b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 32px;
        }


            .eyebrow {
                font-size: 12px;
                margin-bottom: 14px;
                letter-spacing: 1.5px;
            }

            .welcome {
                font-size: 38px;
                margin-bottom: 13px;
            }

            .subtitle {
                font-size: 15px;
                margin-bottom: 32px;
            }

            .field {
                margin-bottom: 22px;
            }

            .label {
                font-size: 15px;
            }

            .input {
                height: 55px;
                font-size: 16px;
            }

            .login-button {
                height: 56px;
                font-size: 17px;
            }

            .options {
                margin-bottom: 25px;
            }

            .remember,
            .forgot {
                font-size: 13px;
            }

            .footer {
                margin-top: 25px;
                font-size: 12px;
            }
        }

        /* =========================================================
           SMALL MOBILE
        ========================================================= */

        @media (max-width: 390px) {
            .left-side {
                min-height: 330px;
            }

            .left-title {
                font-size: 32px;
            }

            .left-description {
                font-size: 14px;
            }

            .welcome {
                font-size: 34px;
            }

            .options {
                flex-wrap: wrap;
                align-items: flex-start;
            }

            .forgot {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="login-card">

        <!-- =====================================================
             LEFT SIDE
        ====================================================== -->

        <!-- =====================================================
             RIGHT SIDE
        ====================================================== -->

        <section class="right-side">

            <div class="form-container">

                <div class="login-logo-wrap">
                    <div class="login-logo">
                        <img src="{{ asset('images/sapta-logo.png') }}" alt="SAPTA Logo">
                    </div>
                </div><div class="eyebrow">
                    QUALITY AGRICULTURE WORK
                </div>

                <h1 class="welcome">
                    Welcome back
                </h1>

                <p class="subtitle">
                    Sign in to your SAPTA account to continue.
                </p>


                <!-- =================================================
                     SUCCESS MESSAGES
                ================================================== -->

                @if (session('status'))
                    <div
                        class="alert alert-success"
                        role="alert"
                    >
                        {{ session('status') }}
                    </div>
                @endif

                


                <!-- =================================================
                     VALIDATION ERRORS
                ================================================== -->

                @if ($errors->any())
                    <div
                        class="alert alert-error"
                        role="alert"
                    >
                        {{ $errors->first() }}
                    </div>
                @endif


                <!-- =================================================
                     LOGIN FORM
                ================================================== -->

                <form
                    method="POST"
                    action="{{ route('login.store') }}"
                    id="loginForm"
                >

                    @csrf


                    <!-- EMAIL -->

                    <div class="field">

                        <label
                            for="email"
                            class="label"
                        >Email or Username</label>

                        <input
                            id="email"
                            type="text"
                            name="email"
                            value="{{ old('email') }}"
                            class="input"
                            placeholder="Enter your email or username"
                            autocomplete="email"
                            inputmode="email"
                            required
                            autofocus
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="field">

                        <label
                            for="password"
                            class="label"
                        >
                            Password
                        </label>

                        <div class="password-box">

                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="input"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button
                                type="button"
                                class="show-password"
                                id="passwordToggle"
                                aria-label="Show password"
                                title="Show password"
                            >

                                <!-- OPEN EYE -->

                                <svg
                                    id="eyeOpen"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="3"
                                    />
                                </svg>


                                <!-- CLOSED EYE -->

                                <svg
                                    id="eyeClosed"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                    style="display: none;"
                                >

                                    <path d="M3 3l18 18" />

                                    <path
                                        d="M10.6 10.6a2 2 0 0 0 2.8 2.8"
                                    />

                                    <path
                                        d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8"
                                    />

                                    <path
                                        d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 7 10 7"
                                    />

                                </svg>

                            </button>

                        </div>

                    </div>


                    <!-- =================================================
                         OPTIONS
                    ================================================== -->

                    <div class="options">

                        <label
                            class="remember"
                            for="remember"
                        >

                            <input
                                id="remember"
                                type="checkbox"
                                name="remember"
                                value="1"
                            >

                            <span>
                                Remember me
                            </span>

                        </label>


                        @if (Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="forgot"
                            >
                                Forgot password?
                            </a>

                        @endif

                    </div>


                    <!-- =================================================
                         LOGIN BUTTON
                    ================================================== -->

                    <button
                        type="submit"
                        class="login-button"
                        id="loginButton"
                    >

                        <span class="button-content">

                            <span
                                class="spinner"
                                id="loginSpinner"
                                style="display: none;"
                                aria-hidden="true"
                            ></span>

                            <span id="loginText">
                                Sign In
                            </span>

                        </span>

                    </button>

                </form>


                <!-- =================================================
                     FOOTER
                ================================================== -->

                <div class="footer">
                    &copy; {{ date('Y') }} SAPTA System.
                    All rights reserved.
                </div>

            </div>

        </section>

    </div>

</div>


<!-- =============================================================
     JAVASCRIPT
============================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       PASSWORD VISIBILITY
    ========================================================= */

    const password = document.getElementById('password');
    const toggle = document.getElementById('passwordToggle');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (
        password &&
        toggle &&
        eyeOpen &&
        eyeClosed
    ) {

        toggle.addEventListener('click', function () {

            const isPassword =
                password.type === 'password';

            if (isPassword) {

                password.type = 'text';

                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';

                toggle.setAttribute(
                    'aria-label',
                    'Hide password'
                );

                toggle.setAttribute(
                    'title',
                    'Hide password'
                );

            } else {

                password.type = 'password';

                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';

                toggle.setAttribute(
                    'aria-label',
                    'Show password'
                );

                toggle.setAttribute(
                    'title',
                    'Show password'
                );
            }
        });
    }


    /* =========================================================
       LOGIN SUBMIT STATE
    ========================================================= */

    const form = document.getElementById('loginForm');
    const button = document.getElementById('loginButton');
    const text = document.getElementById('loginText');
    const spinner = document.getElementById('loginSpinner');

    if (
        form &&
        button &&
        text &&
        spinner
    ) {

        form.addEventListener('submit', function () {

            button.disabled = true;

            text.textContent = 'Signing in...';

            spinner.style.display = 'inline-block';

        });
    }

});
</script>

</body>
</html>
