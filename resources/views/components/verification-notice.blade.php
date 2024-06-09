<x-layout>
    <link rel="stylesheet" href="{{ asset('css/verification-notice.css') }}">
    <div class="verification_body">
        <div class="verification_container">
            <h1>Potvrdite svoju email adresu</h1>

            <div class="card-body">
                @if (session('resent'))
                    <div>
                        Na vašu email adresu poslat je novi link za verifikaciju.
                    </div>
                @endif
                <p>Pre nego što nastavite, proverite svoj email za link za verifikaciju.</p>
                <p>Ako niste primili email.</p>
            </div>

            <div class="verification_button">
                <form  method="GET" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit">Kliknite ovde da biste zatražili još jedan</button>
                </form>
            </div>
        </div>
    </div>

</x-layout>
