<x-layout>
<style>
        .back-link {
            text-decoration: none;
            color: black;
            font-size: 38px;
            display: flex;
            align-items: center;
            animation: move 2s infinite;
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
    <link rel="stylesheet" href="{{ asset('css/tables/manage.css') }}">
    <div class="manage_container" style="height: 70vh">

    <a href="{{ url()->previous() }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Home
    </a>

        <h1 class="heading">Pratioci teme "{{ $theme->title }}"</h1>
        <div class="line"></div>

        @if ($followers->isEmpty())
            <p style="margin-left: 570px; font-size:20px;">Nema  pratioca</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Korisnik</th>
                        <th>Email</th>
                        <th>Broj telefona</th>
                        <th>Datum rođenja</th>
                        <th>JMBG</th>
                        <th>Grad</th>
                        <th>Država</th>
                        <th>Izbriši</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($followers as $follower)
                        <tr>
                            <td>{{ $follower->name }}</td>
                            <td>{{ $follower->email }}</td>
                            <td>{{ $follower->phone_number }}</td>
                            <td>{{ $follower->birth_date }}</td>
                            <td>{{ $follower->personal_number }}</td>
                            <td>{{ $follower->place_of_birth }}</td>
                            <td>{{ $follower->country }}</td>
                            <td>
                                <form action="{{ route('followed-themes.delete-follower', ['themeId' => $theme->id, 'followerId' => $follower->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Izbriši</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-layout>
