@if($useBase64 ?? false)
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/sapta-logo.png'))) }}" alt="SAPTA" class="logo">
@else
    <img src="{{ asset('images/sapta-logo.png') }}" alt="SAPTA" class="logo">
@endif
