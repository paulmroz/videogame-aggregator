<div class="relative" x-data = "{ isVisible:true}" @click.away="isVisible = false">
    <input
        wire:model.debounce.300ms="search"
        type="text" class="bg-gray-800 text-sm rounded-full focus:outline-none pl-8 focus:shadow-outline px-3 w-64 py-1"
           placeholder="Szukaj..."
           x-ref="search"
           @keydown.window="
                if(event.keyCode == 191){
                    event.preventDefault();
                    $refs.search.focus();
                }
           "
           @focus="isVisible=true"
           @keydown.escape.window="isVisible=false"
           @keydown="isVisible=true"
           @keydown.shift.tab="isVisible=false"
    >
    <div class="absolute top-0 flex items-center h-full ml-2">
        <svg class="fill-current text-gray-400 w-4" viewBox="0 0 24 24"><path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 01-1.42 1.4l-5.38-5.38a8 8 0 111.41-1.41zM10 16a6 6 0 100-12 6 6 0 000 12z"/></svg>
    </div>

    <div wire:loading class="spinner top-0 right-0 mr-4 mt-4 text-xs" style="position: absolute"></div>

    @if(strlen($search)>=2)
    <div class="absolute z-50 bg-gray-800 text-xs rounded w-64 mt-2" x-show.transition.opacity.duration.1000="isVisible">
        @if(count($searchResults) > 0)
        <ul>
            <li class="border-b border-gray-700">
                @foreach($searchResults as $game)
                    <a href="{{route('games.show', $game['slug'])}}" class="block hover:bg-gray-700 px-3 py-3 flex items-center transition ease-in-out duration-150"
                    @if($loop->last)   
                        @keydown.tab="isVisible=false"
                    @endif
                    >
                        @if(isset($game['cover']))
                            <img src="{{Str::replaceFirst('thumb','cover_small',$game['cover']['url'])}}" alt="cover" class="w-10">
                        @else
                            <img src="https://vglist.co/assets/no-cover-60a919fca497ced5f84133c0ce073e17475c47f4a4cb122f2150299dc549d223.png" alt="cover" class="w-10">
                        @endif
                        <span class="ml-4">{{$game['name']}}</span>
                    </a>
                @endforeach
            </li>
        </ul>
        @else
            <div class="px-3 py-3">No results for "{{$search}}"</div>
        @endif
    </div>
    @endif

</div>
