<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>SAPTA | Reset Password</title>

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

    .reset-card {
        width: 100%;
        max-width: 1280px;
        min-height: 700px;

        display: grid;
        grid-template-columns: 50% 50%;

        background: #ffffff;

        border-radius: 30px;
        overflow: hidden;

        box-shadow:
            0 25px 70px rgba(0, 0, 0, .12);
    }

    /* ==================================================
       LEFT SIDE
    ================================================== */

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

        background: rgba(255, 255, 255, .80);

        filter: blur(12px);

        opacity: .8;
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

        filter:
            drop-shadow(
                0 6px 7px rgba(0, 0, 0, .20)
            );
    }

    .brand-text {
        color: white;

        font-family: Georgia, serif;

        font-size: 34px;

        font-weight: 700;

        letter-spacing: .5px;
    }

    .left-content {
        position: relative;
        z-index: 5;

        max-width: 530px;

        margin-top: auto;
        margin-bottom: 45px;
    }

    .left-title {
        font-family:
            Georgia,
            "Times New Roman",
            serif;

        color: #050505;

        font-size: 55px;

        line-height: 1.05;

        font-weight: 700;

        letter-spacing: -1.5px;

        margin-bottom: 24px;
    }

    .left-description {
        color: rgba(255, 255, 255, .95);

        font-size: 20px;

        line-height: 1.6;
    }

    /* ==================================================
       CLOUDS
    ================================================== */

    .clouds {
        position: absolute;

        left: 60px;
        bottom: 45px;

        display: flex;

        gap: 35px;

        opacity: .85;
    }

    .cloud {
        width: 45px;
        height: 13px;

        position: relative;

        background: white;

        border-radius: 30px;
    }

    .cloud::before {
        content: "";

        position: absolute;

        width: 24px;
        height: 24px;

        left: 8px;
        bottom: 4px;

        background: white;

        border-radius: 50%;
    }

    .cloud::after {
        content: "";

        position: absolute;

        width: 18px;
        height: 18px;

        left: 26px;
        bottom: 4px;

        background: white;

        border-radius: 50%;
    }

    /* ==================================================
       RIGHT SIDE
    ================================================== */

    .right-side {
        background: #ffffff;

        padding: 70px 75px;

        display: flex;

        align-items: center;
    }

    .form-container {
        width: 100%;

        max-width: 500px;

        margin: auto;
    }

    .eyebrow {
        color: #4e7f22;

        font-size: 15px;

        font-weight: 700;

        letter-spacing: 2px;

        margin-bottom: 20px;
    }

    .welcome {
        font-family:
            Georgia,
            "Times New Roman",
            serif;

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

        margin-bottom: 38px;
    }

    /* ==================================================
       ALERTS
    ================================================== */

    .alert {
        padding: 14px 16px;

        border-radius: 10px;

        margin-bottom: 22px;

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

    /* ==================================================
       FORM
    ================================================== */

    .field {
        margin-bottom: 24px;
    }

    .label {
        display: block;

        font-size: 16px;

        font-weight: 700;

        color: #4f5a4c;

        margin-bottom: 9px;
    }

    .input-wrapper {
        position: relative;
    }

    .input {
        width: 100%;

        height: 62px;

        border: 1.5px solid #d7ddd7;

        border-radius: 10px;

        padding: 0 65px 0 18px;

        background: #ffffff;

        font-size: 17px;

        color: #172015;

        outline: none;

        transition: .2s;
    }

    .input:focus {
        border-color: #159ed2;

        box-shadow:
            0 0 0 4px rgba(21, 158, 210, .10);
    }

    .input::placeholder {
        color: #9ba29a;
    }

    /* ==================================================
       SHOW PASSWORD
    ================================================== */

    .password-toggle {
        position: absolute;

        top: 8px;
        right: 8px;

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

    .password-toggle:hover {
        background: #f4fafc;

        color: #159ed2;
    }

    .password-toggle svg {
        width: 22px;
        height: 22px;

        fill: none;

        stroke: currentColor;

        stroke-width: 2;

        stroke-linecap: round;

        stroke-linejoin: round;
    }

    /* ==================================================
       PASSWORD REQUIREMENTS
    ================================================== */

    .requirements {
        margin-top: -8px;

        margin-bottom: 25px;

        padding: 14px 16px;

        background: #f8faf7;

        border: 1px solid #e1e6df;

        border-radius: 10px;
    }

    .requirements-title {
        color: #596456;

        font-size: 13px;

        font-weight: 700;

        margin-bottom: 8px;
    }

    .requirements-list {
        list-style: none;

        display: grid;

        gap: 5px;
    }

    .requirements-list li {
        color: #7b8478;

        font-size: 12px;
    }

    .requirements-list li::before {
        content: "•";

        margin-right: 7px;

        color: #159ed2;
    }

    /* ==================================================
       BUTTON
    ================================================== */

    .reset-button {
        width: 100%;

        height: 62px;

        border: none;

        border-radius: 10px;

        background: #159ed2;

        color: #ffffff;

        font-size: 18px;

        font-weight: 700;

        cursor: pointer;

        transition: .2s;
    }

    .reset-button:hover {
        background: #087faf;

        transform: translateY(-1px);

        box-shadow:
            0 8px 20px rgba(21, 158, 210, .20);
    }

    .reset-button:active {
        transform: translateY(0);
    }

    .reset-button:disabled {
        opacity: .65;

        cursor: not-allowed;
    }

    /* ==================================================
       BACK TO LOGIN
    ================================================== */

    .back-login {
        display: block;

        margin-top: 24px;

        color: #008bc5;

        text-decoration: none;

        text-align: center;

        font-size: 15px;

        font-weight: 700;
    }

    .back-login:hover {
        text-decoration: underline;
    }

    /* ==================================================
       FOOTER
    ================================================== */

    .footer {
        text-align: center;

        color: #929990;

        font-size: 13px;

        margin-top: 28px;
    }

    /* ==================================================
       TABLET
    ================================================== */

    @media (max-width: 1000px) {

        body {
            padding: 18px;
        }

        .page {
            min-height:
                calc(100vh - 36px);
        }

        .reset-card {
            min-height: 650px;
        }

        .left-side {
            padding: 45px 40px;
        }

        .right-side {
            padding: 45px 40px;
        }

        .left-title {
            font-size: 43px;
        }

        .welcome {
            font-size: 43px;
        }

        .subtitle {
            font-size: 16px;
        }
    }

    /* ==================================================
       MOBILE
    ================================================== */

    @media (max-width: 760px) {

        body {
            padding: 0;
        }

        .page {
            min-height: 100vh;
        }

        .reset-card {
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
            padding: 40px 25px;
        }

        .eyebrow {
            font-size: 12px;

            margin-bottom: 14px;
        }

        .welcome {
            font-size: 38px;
        }

        .subtitle {
            font-size: 15px;

            margin-bottom: 28px;
        }

        .input {
            height: 55px;

            font-size: 16px;
        }

        .password-toggle {
            width: 42px;
            height: 41px;

            top: 7px;
        }

        .reset-button {
            height: 56px;

            font-size: 17px;
        }

    }

</style>
```

</head>

<body>

<div class="page">

```
<div class="reset-card">

    <!-- ==================================================
         LEFT SIDE
    ================================================== -->

    <div class="left-side">

        <div class="sun"></div>

        

        <div class="left-content">

            <h2 class="left-title">
                From farming,<br>
                to something more.
            </h2>

            <p class="left-description">
                Manage your agriculture operations with a
                system built for the whole season, start to
                harvest.
            </p>

        </div>

        <div class="clouds">

            <div class="cloud"></div>
            <div class="cloud"></div>
            <div class="cloud"></div>
            <div class="cloud"></div>

        </div>

    </div>


    <!-- ==================================================
         RIGHT SIDE
    ================================================== -->

    <div class="right-side">

        <div class="form-container">

            <div class="eyebrow">
                SAPTA ACCOUNT SECURITY
            </div>

            <h1 class="welcome">
                Reset password
            </h1>

            <p class="subtitle">
                Create a new secure password for your
                SAPTA account.
            </p>


            <!-- ==================================================
                 ERRORS
            ================================================== -->

            @if ($errors->any())

                <div class="alert alert-error">

                    {{ $errors->first() }}

                </div>

            @endif


            <!-- ==================================================
                 RESET FORM
            ================================================== -->

            <form
                method="POST"
                action="{{ route('password.update') }}"
                id="resetPasswordForm"
            >

                @csrf


                <!-- TOKEN -->

                <input
                    type="hidden"
                    name="token"
                    value="{{ $token }}"
                >


                <!-- EMAIL -->

                <div class="field">

                    <label
                        for="email"
                        class="label"
                    >
                        Email Address
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        class="input"
                        autocomplete="email"
                        required
                        readonly
                    >

                </div>


                <!-- NEW PASSWORD -->

                <div class="field">

                    <label
                        for="password"
                        class="label"
                    >
                        New Password
                    </label>

                    <div class="input-wrapper">

                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="input"
                            placeholder="Enter your new password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="password"
                            aria-label="Show password"
                        >

                            <svg
                                class="eye-open"
                                viewBox="0 0 24 24"
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

                            <svg
                                class="eye-closed"
                                viewBox="0 0 24 24"
                                style="display:none;"
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


                <!-- CONFIRM PASSWORD -->

                <div class="field">

                    <label
                        for="password_confirmation"
                        class="label"
                    >
                        Confirm New Password
                    </label>

                    <div class="input-wrapper">

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="input"
                            placeholder="Confirm your new password"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-target="password_confirmation"
                            aria-label="Show password"
                        >

                            <svg
                                class="eye-open"
                                viewBox="0 0 24 24"
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

                            <svg
                                class="eye-closed"
                                viewBox="0 0 24 24"
                                style="display:none;"
                            >

                                <path d="M3 3l18 18" />

                                <path
                                    d="M10.6 10.6a2 2 0 0 0 2.8 2.8"
                                />

                                <path
                                    d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 7 10 7"
                                />

                                <path
                                    d="M6.6 6.6C3.6 8.5 2 12 2 12s3.5 7 10 7"
                                />

                            </svg>

                        </button>

                    </div>

                </div>


                <!-- PASSWORD REQUIREMENTS -->

                <div class="requirements">

                    <div class="requirements-title">
                        Password requirements
                    </div>

                    <ul class="requirements-list">

                        <li>
                            At least 8 characters
                        </li>

                        <li>
                            Use a strong password
                        </li>

                        <li>
                            Both password fields must match
                        </li>

                    </ul>

                </div>


                <!-- RESET BUTTON -->

                <button
                    type="submit"
                    class="reset-button"
                    id="resetButton"
                >

                    <span id="resetText">
                        Reset Password
                    </span>

                </button>

            </form>


            <!-- BACK TO LOGIN -->

            <a
                href="{{ route('login') }}"
                class="back-login"
            >
                ← Back to Login
            </a>


            <!-- FOOTER -->

            <div class="footer">
                © {{ date('Y') }} SAPTA System.
                All rights reserved.
            </div>

        </div>

    </div>

</div>
```

</div>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        /*
        |--------------------------------------------------------------------------
        | SHOW / HIDE PASSWORD
        |--------------------------------------------------------------------------
        */

        const toggles =
            document.querySelectorAll(
                '.password-toggle'
            );

        toggles.forEach(function (toggle) {

            toggle.addEventListener(
                'click',
                function () {

                    const targetId =
                        toggle.getAttribute(
                            'data-target'
                        );

                    const input =
                        document.getElementById(
                            targetId
                        );

                    const eyeOpen =
                        toggle.querySelector(
                            '.eye-open'
                        );

                    const eyeClosed =
                        toggle.querySelector(
                            '.eye-closed'
                        );


                    if (
                        input.type === 'password'
                    ) {

                        input.type = 'text';

                        eyeOpen.style.display =
                            'none';

                        eyeClosed.style.display =
                            'block';

                        toggle.setAttribute(
                            'aria-label',
                            'Hide password'
                        );

                    } else {

                        input.type = 'password';

                        eyeOpen.style.display =
                            'block';

                        eyeClosed.style.display =
                            'none';

                        toggle.setAttribute(
                            'aria-label',
                            'Show password'
                        );

                    }

                }
            );

        });


        /*
        |--------------------------------------------------------------------------
        | RESET FORM
        |--------------------------------------------------------------------------
        */

        const form =
            document.getElementById(
                'resetPasswordForm'
            );

        const button =
            document.getElementById(
                'resetButton'
            );

        const text =
            document.getElementById(
                'resetText'
            );


        if (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    const password =
                        document.getElementById(
                            'password'
                        );

                    const confirmation =
                        document.getElementById(
                            'password_confirmation'
                        );


                    /*
                    |----------------------------------------------------------
                    | Check matching passwords
                    |----------------------------------------------------------
                    */

                    if (
                        password.value !==
                        confirmation.value
                    ) {

                        event.preventDefault();

                        alert(
                            'The passwords do not match.'
                        );

                        return;
                    }


                    /*
                    |----------------------------------------------------------
                    | Disable button
                    |----------------------------------------------------------
                    */

                    button.disabled = true;

                    text.textContent =
                        'Resetting password...';

                }
            );

        }

    }
);

</script>

</body>

</html>
