@props(['item'])
<div class="news-feed-item">
    <p>{{ $item->content }}</p>
    <p style="display: none" data-timestamp="{{ $item->created_at->valueOf() }}"></p>
    <p class="time-passed" style="font-style: italic; font-size: 12px; text-align: right;"></p>

    @auth
    @if (auth()->user()->role === 'admin')
        <form action="/delete-news-feed/{{$item->id}}" method="POST">
            @csrf
            @method('DELETE')
            <button class="delete_newsFeed" type="submit"><i class="fa-solid fa-trash"></i> Izbriši</button>
        </form>
    @endif
    @endauth
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const timePassedElements = document.querySelectorAll('.time-passed');

        function updateTimePassed() {
            timePassedElements.forEach(element => {
                const timestampText = element.previousElementSibling.getAttribute('data-timestamp');
                const parsedTimestamp = new Date(parseInt(timestampText, 10));
                const currentTime = new Date();
                const timeDifference = currentTime - parsedTimestamp;
                const minutesPassed = Math.floor(timeDifference / 60000);

                let timePassed;
                if (minutesPassed < 1) {
                    timePassed = 'upravo';
                } else if (minutesPassed < 60) {
                    timePassed = `pre ${minutesPassed} minut${minutesPassed !== 1 ? 'a' : ''}`;
                } else if (minutesPassed < 1440) {
                    const hoursPassed = Math.floor(minutesPassed / 60);
                    timePassed = `pre ${hoursPassed} sat${hoursPassed !== 1 ? 'a' : ''}`;
                } else {
                    const daysPassed = Math.floor(minutesPassed / 1440);
                    timePassed = `pre ${daysPassed} dan${daysPassed !== 1 ? 'a' : ''}`;
                }

                element.textContent = timePassed;
            });
        }

        updateTimePassed();

        setInterval(updateTimePassed, 60000);
    });
</script>
