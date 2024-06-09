<link rel="stylesheet" href="{{ asset('css/tables/manage.css') }}">
<x-layout>

    @unless ($users->isEmpty())
        <div class="manage_container">
            <h1 class="heading">Upravljanje korisnicima</h1>
            <label style="padding-bottom: 10px; font-style:italic"></label>

            <div class="line"></div>

            <table>
                <thead>
                    <tr>
                        <th>Korisnik</th>
                        <th>Uloga</th>
                        <th>Email</th>
                        <th>Broj telefona</th>
                        <th>Datum rodjenja</th>
                        <th>JMBG</th>
                        <th>Grad</th>
                        <th>Gržava</th>
                        <th class="actions">Opcije</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="users_rows">
                        <td class="user-name" style="text-align: center; vertical-align: middle;">
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <img src="{{ asset('storage/' . $user->picture) }}" alt="{{ $user->name }}" style="width: 35px; height: 35px; border-radius: 50%; display: block; margin: 0 auto;">
                                <span style="display: block;">{{ $user->name }}</span>
                            </div>
                        </td><td class="user-role">{{ $user->role }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->birth_date }}</td>
                        <td>{{ $user->personal_number }}</td>
                        <td>{{ $user->place_of_birth }}</td>
                        <td>{{ $user->country }}</td>
                        <td class="actions">
                            <form style="display: inline;" method="POST" action="/users/{{ $user->id }}" id="delete-form-{{ $user->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger " style="width: 90px" onclick="confirmDelete({{ $user->id }})"><i class="fa-solid fa-trash" style="padding-right: 5px"></i>Izbriši</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div>
                <p>Nema korisnika</p>
            </div>

        @if ($themes->isNotEmpty())
            <div class="manage_container">
                <h1 class="heading">Neodobrene teme</h1>
            </div>
        @else
            <div>
                <p>Nema neodobrenih tema</p>
            </div>
        @endif
    @endunless

</x-layout>


<script>
    function confirmDelete(userId) {
        if (confirm('Da ii ste siguri da zelite da izbrisite korisnika?')) {
            document.getElementById('delete-form-' + userId).submit();
        }
    }
</script>
