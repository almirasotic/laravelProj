<x-layout>
<link rel="stylesheet" href="{{ asset('css/followed-themes.css') }}">

    <div class="containerr">

   

        <div class="themes_Body">
            @if ($followedThemes->isEmpty())
                <p style="text-align: center; font-size:medium; margin-left:550px;">Nema tema koje pratite.</p>
            @else
                    @foreach ($followedThemes as $theme)
                        <x-theme-card :theme="$theme" />
                    @endforeach

                {{ $followedThemes->links() }}
            @endif
        </div>

    </div>
</x-layout>
