<x-layout>
    <link rel="stylesheet" href="{{ asset('css/forms/anketa.css') }}">
    <div class="form_body">
        <div class="form_container">
            <a href="{{ url()->previous() }}" style="color:black; text-decoration:underline;">Home</a>

            <form action="{{ route('themes.store-poll', $theme) }}" method="POST" id="poll-form">
                @csrf
                <div class="form-group" style="margin-bottom: 20px; margin-top:30px">
                    <label style="font-size: 17px">Anketa pocinje na temu:  </label>
                    <p style="font-weight: bold; font-size: 45px">{{ $theme->title }}</p>
                    <div style="width:100%; background-color:grey;height:0.5px; margin-top:30px; margin-bottom:30px"></div>
                </div>


                <div class="form-group">
                    <p style="font-weight: bold; font-size: 20px">Pitanje</p>
                    <label style="color: Black; font-style:italic; padding-bottom: 10px; font-weight:100">Ovde mozete postaviti pitanje</label>
                    <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                </div>


                <div class="form-group" id="options-container" style="margin-top: 20px">

                    <label style="font-weight: bold; font-size: 20px">Odgovori</label><br>

                    <label style="color: black;  padding-bottom: 10px; font-weight:100">Ostavite odgovor</label>

                    <input type="text" class="form-control" name="options[]" required><br>
                    <input type="text" class="form-control" name="options[]" required><br>
                </div>
                <button type="button" class="add">Dodaj odgovor</button>

                <div style="width:100%; background-color:grey;height:0.5px; margin-top:40px; margin-bottom:50px"></div>

                <button type="submit" class="create">KREIRAJ ANKETU</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.add').addEventListener('click', function() {
                const optionsContainer = document.getElementById('options-container');
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control';
                input.name = 'options[]';
                input.required = true;
                optionsContainer.appendChild(input);
                optionsContainer.appendChild(document.createElement('br'));
            });
        });
    </script>
</x-layout>
