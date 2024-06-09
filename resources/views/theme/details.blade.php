
<link rel="stylesheet" href="{{ asset('css/forms/details-poll.css') }}">
<x-layout>
    <div class="anketa-details-container">


        <div class="anketa-details">

            @if ($isModerator)
                <form action="{{ route('poll.delete', $poll) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="close_poll">Zatvori anketu</button>
                </form>
            @endif

            <br>
            <label>ANKETA</label>
            <p>{{ $poll->question }}</p>
            <form action="{{ route('poll.response.store') }}" method="POST">


                @csrf
                <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                <input type="hidden" name="theme_id" value="{{ $theme->id }}">

                <ul>
                    @foreach (json_decode($poll->options) as $option)
                        <li>
                            <input type="radio" id="option{{ $loop->index }}" name="response" value="{{ $option }}">
                            <span for="option{{ $loop->index }}" style="font-size: 18px; color:black">{{ $option }}</span>
                        </li>
                    @endforeach
                </ul>

                <div style="width:100%; height:0.5px; margin-top:40px; margin-bottom:50px;"></div>

                <button type="submit" class="submit">NASTAVI</button>
            </form>

        </div>

        <div class="statistics">
            <span style="font-size: 20px; font-weight: bold; display:flex; ">Statistika</span>
            <ul>
                @foreach ($responsePercentages as $option => $percentage)
                    <li>
                        <span style="font-size: 18px; color:black">{{ $option }} - {{ round($percentage, 2) }}%</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-layout>
