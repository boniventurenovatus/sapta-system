<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">

```
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Welcome to SAPTA</title>
```

</head>

<body style="
    margin:0;
    padding:0;
    background:#f4f6f8;
    font-family:Arial,Helvetica,sans-serif;
    color:#172033;
">

<div style="
    max-width:620px;
    margin:40px auto;
    background:#ffffff;
    border-radius:12px;
    overflow:hidden;
    border:1px solid #e5e7eb;
">

```
<div style="
    padding:25px;
    background:#1f6f43;
    color:#ffffff;
    text-align:center;
">

    <h1 style="
        margin:0;
        font-size:25px;
    ">
        SAPTA System
    </h1>

    <p style="
        margin:8px 0 0;
        font-size:14px;
    ">
        From farming, to something more.
    </p>

</div>


<div style="padding:35px 30px;">

    <h2 style="
        margin-top:0;
        font-size:22px;
    ">
        Welcome, {{ $employee->first_name }}!
    </h2>


    <p style="
        font-size:14px;
        line-height:1.7;
    ">
        Your SAPTA employee account has been created successfully.
    </p>


    <p style="
        font-size:14px;
        line-height:1.7;
    ">
        You can now use the following credentials to access the SAPTA
        Management System.
    </p>


    <div style="
        margin:25px 0;
        padding:20px;
        background:#f8fafc;
        border:1px solid #e2e8f0;
        border-radius:10px;
    ">

        <p style="
            margin:0 0 12px;
            font-size:14px;
        ">
            <strong>Email:</strong>
            {{ $employee->email }}
        </p>


        <p style="
            margin:0 0 12px;
            font-size:14px;
        ">
            <strong>Username:</strong>
            {{ $user->username }}
        </p>


        <p style="
            margin:0;
            font-size:14px;
        ">
            <strong>Temporary Password:</strong>
            {{ $temporaryPassword }}
        </p>

    </div>


    <div style="
        text-align:center;
        margin:30px 0;
    ">

        <a
            href="{{ $loginUrl }}"
            style="
                display:inline-block;
                padding:13px 25px;
                background:#2563eb;
                color:#ffffff;
                text-decoration:none;
                border-radius:8px;
                font-weight:bold;
                font-size:14px;
            "
        >
            Login to SAPTA
        </a>

    </div>


    <div style="
        margin-top:25px;
        padding:15px;
        background:#fff7ed;
        border:1px solid #fed7aa;
        border-radius:8px;
        color:#9a3412;
        font-size:13px;
        line-height:1.6;
    ">

        <strong>Important:</strong>

        <br>

        This is your temporary password.

        Please change your password after your first login.

    </div>


    <p style="
        margin-top:25px;
        font-size:13px;
        line-height:1.6;
        color:#64748b;
    ">
        If you did not expect this account, please contact your
        SAPTA administrator.
    </p>


    <p style="
        margin-top:25px;
        font-size:14px;
    ">
        Regards,<br>
        <strong>SAPTA System</strong>
    </p>

</div>


<div style="
    padding:18px 30px;
    background:#f8fafc;
    border-top:1px solid #e5e7eb;
    text-align:center;
    color:#64748b;
    font-size:11px;
">

    © {{ date('Y') }} SAPTA Management System.
    All rights reserved.

</div>
```

</div>

</body>
</html>
