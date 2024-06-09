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
            margin-top: 50px;
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
    <link rel="stylesheet" href="{{ asset('css/forms/registerForm.css') }}">
    <div class="form_body">
        <div class="form_container">


        <a href="{{ url()->previous() }}" class="back-link" style="text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Home
    </a>
            <span style="font-size: 58px; padding-bottom:10px; color:black">
                Registruj se
            </span>
            <label style="color: black; font-size:20px">Napravi novi korisnički nalog</label>


            <form  method="post" action="/users" enctype="multipart/form-data">

                <span class="line" style="margin-bottom: 20px"></span>

                @csrf
                <label for="name">Korisničko ime</label>
                <input type="text" id="name" name="name" value="{{old('name')}}">

                @error('name')
                    <p style="color: red">{{$message}}</p>
                @enderror
                <br>

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

                <label for="password_confirmation">Potvrda lozinke</label>
                <input type="password" id="password_confirmation" name="password_confirmation" value="{{old('password_confirmation')}}">
                @error('password_confirmation')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="gender">Pol</label>
                <select id="gender" name="gender">
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Muško</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Žensko</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Drugo</option>
                </select>
                @error('gender')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="place_of_birth">Mesto rođenja</label>
                <input type="text" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}">
                @error('place_of_birth')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="country">Država rođenja</label>
                <input type="text" id="country" name="country" value="{{ old('country') }}">
                @error('country')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="birth_date">Datum rođenja</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                @error('birth_date')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="personal_number">JMBG</label>
                <input type="text" id="personal_number" name="personal_number" value="{{ old('personal_number') }}">
                @error('personal_number')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="phone_number">Broj telefona</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                @error('phone_number')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <label for="picture" class="custom-file-upload">
                    <i class="fas fa-cloud-upload-alt" ></i> Izaberite sliku
                </label>
                <input type="file" id="picture" name="picture">

                <img id="preview-image" src="#" alt="Slika" style="max-width: 200px; max-height: 200px; margin-top: 10px; display: none;">


                <script>
                    const pictureInput = document.getElementById('picture');
                    const previewImage = document.getElementById('preview-image');

                    pictureInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                previewImage.src = event.target.result;
                                previewImage.style.display = 'block';
                            }
                            reader.readAsDataURL(file);
                        }
                    });
                </script>


                @error('picture')
                <p style="color: red">{{$message}}</p>
                @enderror
                <br>

                <button type="submit">NASTAVI</button>


                <span class="line"></span>

                <p style="margin-bottom:15%">Imate već korisnički nalog?  <a href="/login" style="margin-left: 5px; color:red; text-decoration:none;">Prijavi se ovde</a></p>

            </form>
        </div>
    </div>

</x-layout>
