<x-layout>
    <link rel="stylesheet" href="{{ asset('css/forms/form.css') }}">
    <div class="form_body">
        <div class="form_container">
            <a href="{{ url()->previous() }}" style="color:black; text-decoration:underline">Home</a>
            <p>Izmeni temu</p>

            <div style="width: 80%; height:0.5px; background-color:grey;margin-bottom: 50px;"></div>

            <form method="POST" action="/themes/{{$theme->id}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label for="title">Naslov</label>
                <input type="text" id="title" name="title" value="{{$theme->title}}">

                @error('title')
                <p class="errorMessage">{{$message}}</p>
                @enderror
                <br>

                <label for="description">Opis</label><br>
                <textarea id="description" name="description" rows="5">{{$theme->description}}</textarea>

                @error('description')
                <p class="errorMessage">{{$message}}</p>
                @enderror
                <br>

                <label for="image" class="custom-file-upload">
                    <i class="fas fa-cloud-upload-alt"></i> Izaberite novu sliku
                </label>
                <input type="file" id="image" name="image" onchange="previewImage(this)">

                <img id="preview-image" src="{{ asset('storage/' . $theme->image) }}" alt="Slika" style="max-width: 300px; max-height: 300px; margin-top: 10px; display: block;">

                <script>
                    function previewImage(input) {
                        const file = input.files[0];
                        const preview = document.getElementById('preview-image');
                        const reader = new FileReader();

                        reader.onload = function() {
                            preview.src = reader.result;
                        }

                        if (file) {
                            reader.readAsDataURL(file);
                        }
                    }
                </script>

                @error('image')
                <p class="errorMessage">{{$message}}</p>
                @enderror
                <br>

                <div style="width: 100%; height:0.5px; background-color:grey;margin-top: 20px;margin-bottom: 50px;"></div>

                <button type="submit">SAČUVAJ</button>
            </form>
        </div>
    </div>
</x-layout>
