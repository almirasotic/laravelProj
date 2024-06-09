<x-layout>
    <link rel="stylesheet" href="{{ asset('css/forms/form.css') }}">
    <div class="form_body">
        <div class="form_container">

            <a href="{{ url()->previous() }}" style="color:black; text-decoration:underline">Home</a>
            <p>Kreiraj novu temu</p>

            <div style="width: 80%; height:0.5px; background-color:grey;margin-bottom: 50px;"></div>

            <form method="POST" action="/themes" enctype="multipart/form-data">
                @csrf
                <label for="title">Naziv</label>
                <input type="text" id="title" name="title" value="{{old('title')}}">

                @error('title')
                <p>{{$message}}</p>
                @enderror
                <br>

                <label for="description">Opis</label>
                <textarea id="description" name="description" rows="6" required placeholder="">{{old('description')}}</textarea>

                @error('description')
                <p>{{$message}}</p>
                @enderror
                <br>

                <label for="image" class="custom-file-upload">
                    <i class="fas fa-cloud-upload-alt"></i> Izaberite sliku
                </label>
                <input type="file" id="image" name="image">

                <img id="preview-image" src="#" alt="Slika" style="max-width: 300px; max-height: 300px; margin-top: 10px; display: none;">

                <script>
                    const pictureInput = document.getElementById('image');
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

                @error('image')
                <p class="errorMessage">{{$message}}</p>
                @enderror
                <br>

                <div style="width: 100%; height:0.5px; background-color:grey;margin-top: 20px;margin-bottom: 50px;"></div>

                <button type="submit">NASTAVI</button>
            </form>
        </div>
    </div>
</x-layout>
