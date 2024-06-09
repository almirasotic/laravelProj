<link rel="stylesheet" href="{{ asset('css/partials/navbar.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Elsie&family=Space+Grotesk:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<nav class="navbar">
    <div class="container">
    <a href="/" class="logo" style="display: flex; align-items: center; text-decoration: none; color: black;">
    <img src="/image/logo11.jpg" width="50px" height="50px" style="margin-right: 5px; max-width: none; border-radius: 50px;">
    Programming
</a>
        <ul class="nav-links">
            <div class="padding">
                @guest
                    <a href="/" style="padding-top:10px; padding-right: 20px; color: white; text-decoration:none">Home page</a>
                @endguest
                @auth
                    <li><a href="/"> Home page</a></li>
                    @if(auth()->user()->role == 'korisnik')
                        <li><a href="{{ route('followed-themes.index') }}">Teme</a></li>
                    @endif
                    @if(auth()->user()->role == 'moderator')
                        <li><a href="/themes"> Teme za pracenje</a></li>
                        <li><a href="/themes/manage"> Podesavanja za teme</a></li>
                    @endif
                    @if (auth()->user()->role == 'admin')
                        <li><a href="/users/manage"> Korisnici</a></li>
                        <li><a href="/users/requests"> Zahtevi za teme</a></li>
                    @endif
                    <li id="userDropdown" class="dropdown">
                        <a href="#" role="button" id="userDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center;">
                            @if (auth()->user()->picture != "null")
                                <img src="{{ asset('storage/' . auth()->user()->picture) }}" alt="{{ auth()->user()->name }}" style="width:30px;height:30px;border-radius:80px; margin-right:5px">
                            @else
                                <i class="fa-solid fa-user" style="margin-right:5px"></i>
                            @endif
                            <span style="color: white; font-weight: bold;">{{ auth()->user()->name }}</span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdownMenuLink">
                            @auth
                                @if (auth()->user()->role === 'korisnik')
                                    <form action="/apply-for-moderator" method="POST" class="mod">
                                        @csrf
                                        <button><i class="fas fa-user" style="padding-right: 5px;"></i>Prijavi se za moderatora</button>
                                    </form>
                                @endif
                            @endauth
                            <a class="dropdown-item" style="color: black" href="/users/{{auth()->user()->id}}/resetPassword">
                                <i class="fas fa-key" style="padding-right: 5px"></i>Promeni šifru</a>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt" style="padding-right: 5px"></i><span class="logout">Odjavi se</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </div>
            @else
                <div class="account">
                <ul class="account">
    <li><a href="/login">Prijavite se</a></li>
</ul>

                </div>
            @endauth
        </ul>
        <form action="" method="GET" style="margin-left: auto;"> 
            <div class="search-container">
                <input type="text" placeholder="Pretraga" name="search">
                <button type="submit"><i class="fa-solid fa-magnifying-glass white-icon"></i></button>
            </div>
        </form>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var userDropdown = document.getElementById('userDropdown');
        userDropdown.addEventListener('click', function () {
            userDropdown.classList.toggle('show');
        });

        window.addEventListener('click', function (e) {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('show');
            }
        });
    });
</script>
