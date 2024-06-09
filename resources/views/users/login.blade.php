<x-layout>
<style>
        .back-link {
            text-decoration: none;
            color: black;
            font-size: 38px;
            display: flex;
            align-items: center;
            animation: move 2s infinite;
            margin-left: -400px;
        }
        @keyframes move {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(10px);
            }
        }
        

        .back-link i {
            margin-right: 8px;
            transition: transform 0.3s;
        }

        .back-link:hover {
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/forms/loginForm.css') }}">
    <div class="form_body">
        <div class="form_container">
        <a href="{{ url()->previous() }}" class="back-link" style="text-decoration: none; color: black;">
        <i class="fas fa-arrow-left" ></i> Home
    </a>

           
            
            <form  method="post" action="/users/authenticate">
                @csrf

                <label for="email">E-mail adresa</label>
                <input type="email" id="email" name="email" value="{{old('email')}}">
                @error('email')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="password">Lozinka</label>
                <input type="password" id="password" name="password" value="{{old('password')}}">
                @error('password')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <button type="submit">Prijava</button>

                <span class="line"></span>

                <p>U slucaju da se niste registrovali <a style="padding-left: 2%; color:red; text-decoration:none;" href="/register">Registrujte se</a></p>
            </form>
        </div>
       
</div>
</x-layout>
