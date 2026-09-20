<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>SAPTA | Forgot Password</title>

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

    /* ==================================================
       PAGE
    ================================================== */

    .page {
        width: 100%;
        min-height: calc(100vh - 60px);

        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* ==================================================
       MAIN CARD
    ================================================== */

    .recovery-card {
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

    /* ==================================================
       SUN
    ================================================== */

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

    /* ==================================================
       BRAND
    ================================================== */

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

    /* ==================================================
       LEFT CONTENT
    ================================================== */

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

    /* ==================================================
       HEADING
    ================================================== */

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
       ALERT
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
       RESET LINK BOX
    ================================================== */

    .reset-link-box {
        margin-bottom: 25px;

        padding: 20px;

        border-radius: 12px;

        background: #f0faf1;

        border: 1px solid #c6e4c9;
    }

    .reset-link-title {
        color: #26702d;

        font-size: 14px;

        font-weight: 700;

        margin-bottom: 8px;
    }

    .reset-link-description {
        color: #667066;

        font-size: 13px;

        line-height: 1.5;

        margin-bottom: 15px;
    }

    .reset-link {
        display: block;

        width: 100%;

        padding: 13px 16px;

        border-radius: 9px;

        background: #159ed2;

        color: white;

        text-align: center;

        font-size: 14px;

        font-weight: 700;

        text-decoration: none;

        transition: .2s;
    }

    .reset-link:hover {
        background: #087faf;

        transform: translateY(-1px);
    }

    /* ==================================================
       FORM
    ================================================== */

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

        background: white;

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
       BUTTON
    ================================================== */

    .reset-button {
        width: 100%;

        height: 62px;

        border: none;

        border-radius: 10px;

        background: #159ed2;

        color: white;

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

        .recovery-card {
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

        .recovery-card {
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

        .reset-button {
            height: 56px;

            font-size: 17px;
        }

        .reset-link {
            font-size: 13px;

            padding: 12px;
        }
    }

</style>
```

</head>

<body>

<div class="page">

```
<div class="recovery-card">

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
                SAPTA ACCOUNT RECOVERY
            </div>

            <h1 class="welcome">
                Forgot password?
            </h1>

            <p class="subtitle">
                Enter your email address and we will
                generate a secure link to reset your
                SAPTA password.
            </p>


            <!-- ==================================================
                 SUCCESS MESSAGE
            ================================================== -->

            @if (session('status'))

                <div class="alert alert-success">
                    {{ session('status') }}
                </div>

            @endif


            <!-- ==================================================
                 RESET LINK
            ================================================== -->

            @if (session('reset_url'))

                <div class="reset-link-box">

                    <div class="reset-link-title">
                        Password reset link ready
                    </div>

                    <p class="reset-link-description">
                        Your development reset link has been
                        generated successfully. Click the button
                        below to continue.
                    </p>

                    <a
                        href="{{ session('reset_url') }}"
                        class="reset-link"
                    >
                        Open Password Reset
                    </a>

                </div>

            @endif


            <!-- ==================================================
                 ERRORS
            ================================================== -->

            @if ($errors->any())

                <div class="alert alert-error">

                    {{ $errors->first() }}

                </div>

            @endif


            <!-- ==================================================
                 FORGOT PASSWORD FORM
            ================================================== -->

            <form
                method="POST"
                action="{{ route('password.email') }}"
                id="forgotPasswordForm"
            >

                @csrf


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
                        value="{{ old('email') }}"
                        class="input"
                        placeholder="name@example.com"
                        autocomplete="email"
                        required
                        autofocus
                    >

                </div>


                <button
                    type="submit"
                    class="reset-button"
                    id="resetButton"
                >

                    <span id="resetText">
                        Send Reset Link
                    </span>

                </button>

            </form>


            <!-- ==================================================
                 BACK TO LOGIN
            ================================================== -->

            <a
                href="{{ route('login') }}"
                class="back-login"
            >
                ← Back to Login
            </a>


            <!-- ==================================================
                 FOOTER
            ================================================== -->

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

        const form =
            document.getElementById(
                'forgotPasswordForm'
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
                function () {

                    button.disabled = true;

                    text.textContent =
                        'Generating link...';

                }
            );

        }

    }
);

</script>

</body>

</html>
