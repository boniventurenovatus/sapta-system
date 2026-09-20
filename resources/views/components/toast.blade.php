@php
    $toastSuccess = session('success');
    $toastError = session('error');
    $toastStatus = session('status');

    $toastMessage = $toastSuccess ?? $toastError ?? $toastStatus;

    $toastType = $toastError
        ? 'error'
        : 'success';
@endphp

@if ($toastMessage)

    <div
        id="saptaToast"
        class="sapta-toast sapta-toast-{{ $toastType }}"
        role="alert"
        aria-live="polite"
    >

        <div class="sapta-toast-icon">

            @if ($toastType === 'error')

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="M12 8v5"></path>
                    <path d="M12 16h.01"></path>
                </svg>

            @else

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"></circle>
                    <path d="m8 12 2.5 2.5L16 9"></path>
                </svg>

            @endif

        </div>


        <div class="sapta-toast-content">

            <div class="sapta-toast-title">
                {{ $toastType === 'error' ? 'Action Failed' : 'Success' }}
            </div>

            <div class="sapta-toast-message">
                {{ $toastMessage }}
            </div>

        </div>


        <button
            type="button"
            class="sapta-toast-close"
            onclick="closeSaptaToast()"
            aria-label="Close notification"
        >
            <span></span>
            <span></span>
        </button>


        <div class="sapta-toast-progress"></div>

    </div>


    <style>

        .sapta-toast {

            position: fixed;

            top: 28px;

            right: 28px;

            z-index: 99999;

            width: min(460px, calc(100vw - 40px));

            min-height: 92px;

            display: flex;

            align-items: center;

            gap: 16px;

            padding: 18px 20px;

            background: #ffffff;

            border: 1px solid #e4e8e3;

            border-radius: 16px;

            box-shadow:
                0 18px 50px rgba(0, 0, 0, .14),
                0 4px 12px rgba(0, 0, 0, .06);

            overflow: hidden;

            animation:
                saptaToastIn .35s ease forwards;
        }


        .sapta-toast-success {

            border-left: 5px solid #2f8f46;
        }


        .sapta-toast-error {

            border-left: 5px solid #c83b3b;
        }


        .sapta-toast-icon {

            width: 46px;

            height: 46px;

            min-width: 46px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;
        }


        .sapta-toast-success .sapta-toast-icon {

            background: #eaf7ed;

            color: #2f8f46;
        }


        .sapta-toast-error .sapta-toast-icon {

            background: #fff0f0;

            color: #c83b3b;
        }


        .sapta-toast-icon svg {

            width: 25px;

            height: 25px;

            fill: none;

            stroke: currentColor;

            stroke-width: 2;

            stroke-linecap: round;

            stroke-linejoin: round;
        }


        .sapta-toast-content {

            flex: 1;

            min-width: 0;
        }


        .sapta-toast-title {

            margin-bottom: 4px;

            font-size: 16px;

            font-weight: 800;

            color: #1d261c;
        }


        .sapta-toast-message {

            font-size: 14px;

            line-height: 1.5;

            color: #687166;

            word-break: break-word;
        }


        .sapta-toast-close {

            width: 32px;

            height: 32px;

            min-width: 32px;

            border: 0;

            background: transparent;

            cursor: pointer;

            border-radius: 8px;

            position: relative;

            display: flex;

            align-items: center;

            justify-content: center;

            transition:
                background .2s ease;
        }


        .sapta-toast-close:hover {

            background: #f3f5f2;
        }


        .sapta-toast-close span {

            position: absolute;

            width: 16px;

            height: 2px;

            background: #6f776d;

            border-radius: 2px;
        }


        .sapta-toast-close span:first-child {

            transform: rotate(45deg);
        }


        .sapta-toast-close span:last-child {

            transform: rotate(-45deg);
        }


        .sapta-toast-progress {

            position: absolute;

            left: 0;

            bottom: 0;

            height: 3px;

            width: 100%;

            transform-origin: left;

            animation:
                saptaToastProgress 5s linear forwards;
        }


        .sapta-toast-success .sapta-toast-progress {

            background: #2f8f46;
        }


        .sapta-toast-error .sapta-toast-progress {

            background: #c83b3b;
        }


        @keyframes saptaToastIn {

            from {

                opacity: 0;

                transform:
                    translateX(35px)
                    translateY(-8px);
            }

            to {

                opacity: 1;

                transform:
                    translateX(0)
                    translateY(0);
            }
        }


        @keyframes saptaToastOut {

            from {

                opacity: 1;

                transform:
                    translateX(0);
            }

            to {

                opacity: 0;

                transform:
                    translateX(35px);
            }
        }


        @keyframes saptaToastProgress {

            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }


        .sapta-toast.sapta-toast-closing {

            animation:
                saptaToastOut .3s ease forwards;
        }


        @media (max-width: 600px) {

            .sapta-toast {

                top: 16px;

                right: 16px;

                width:
                    calc(100vw - 32px);

                min-height: 86px;

                padding:
                    15px 16px;

                border-radius: 14px;

                gap: 12px;
            }


            .sapta-toast-icon {

                width: 40px;

                height: 40px;

                min-width: 40px;
            }


            .sapta-toast-icon svg {

                width: 22px;

                height: 22px;
            }


            .sapta-toast-title {

                font-size: 15px;
            }


            .sapta-toast-message {

                font-size: 13px;
            }

        }

    </style>


    <script>

        function closeSaptaToast() {

            const toast =
                document.getElementById('saptaToast');

            if (!toast) {
                return;
            }

            toast.classList.add(
                'sapta-toast-closing'
            );

            setTimeout(function () {

                toast.remove();

            }, 300);
        }


        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const toast =
                    document.getElementById('saptaToast');

                if (!toast) {
                    return;
                }

                setTimeout(function () {

                    closeSaptaToast();

                }, 5000);

            }
        );

    </script>

@endif

