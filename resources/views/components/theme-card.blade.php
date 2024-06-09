@props(['theme'])

@php
    use Illuminate\Support\Str;
@endphp

<link rel="stylesheet" href="{{ asset('css/theme-card.css') }}">
<div class="theme_Item">

    <div class="theme_User">
        @if ($theme->user)
        <div style="text-align: center">
            @if ($theme->user->picture != "null")
                <!-- <img src="{{ asset('storage/' . $theme->user->picture) }}" alt="{{ $theme->user->name }}" style="width:70px; height:70px; border-radius:10px;"> -->
            @else
                <i class="fa-solid fa-user"></i>
            @endif
            <div style=" padding-bottom: 5px;font-size: 20px;">
           </div>
        </div>
        @endif
    </div>
    <!-- {{ $theme->user->name }} -->
    <div class="vertical-line"></div>

    <div class="themes_Content">
        <a href="themes/{{$theme['id']}}" class="themes_Title">{{$theme->title}}</a>

        <span class="themes_Text"><span style="font-style: italic">Opis: </span> {{ Str::limit($theme->description, 50) }}</span>

        <div class="follow">
        <a href="/themes/addComment?themeTitle={{ $theme->title }}&themeId={{ $theme->id }}" class="theme_button_comment" style="font-size: 10px;">
    <i  style="color: green; margin-right: 5px;"></i>Odgovor na pitanje
</a>


            @auth
            @if (auth()->check() && auth()->user()->role === 'korisnik')
                @php
                    $followedThemes = auth()->user()->followedThemes;
                    if ($followedThemes) {
                        $followedThemesIds = $followedThemes->pluck('id')->toArray();
                        $isFollowing = in_array($theme->id, $followedThemesIds);
                    } else {
                        $isFollowing = false;
                    }
                @endphp

                @if ($isFollowing)
                    <form method="POST" action="{{ route('themes.unfollow', $theme) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-minus-circle" style="padding-right: 5px"></i>Otprati</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('themes.follow', $theme) }}">
                        @csrf
                        <button type="submit" class="follow_button" style="margin-left: 100px; margin-top: -50px;"> <i class="fas fa-plus-circle" style="padding-right: 5px; color: blue"></i>Zapratiiii</button>
                    </form>
                @endif
            @endif

            @if(auth()->user()->role === 'moderator' && auth()->user()->id === $theme->user_id)
            <a href="{{ route('followed-themes.followers', ['themeId' => $theme->id]) }}" class="followers-button" style="font-size: 14px;">
    <i  style="color: black; padding-right: 5px;"></i>Osobe koje prate
</a>


            @endif
            @endauth
        </div>
    </div>

    <div class="themes_Image">
        <div class="date">
            @if($theme->created_at)
                <h3 class="theme_createDate" style="margin-top: -30px;"></h3>
            @else
                <h3 class="theme_createDate">Datum nije dostupan</h3>
            @endif
        </div>

        <!-- <div class="image">
            <img src="{{ asset('storage/' . $theme->image) }}" alt="none">
        </div> -->
    </div>
</div>
