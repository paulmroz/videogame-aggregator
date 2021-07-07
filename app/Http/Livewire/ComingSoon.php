<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Str;

class ComingSoon extends Component
{

    public $comingSoon = [];

    public function loadComingSoon()
    {

        $current = Carbon::now()->timestamp;

        $comingSoonUnformatted =  Http::withHeaders([
            'Client-ID' => 'twkxehw9ugkm42sjcotibf3pd46ibd',
            'Authorization' => 'Bearer of1o9jwb9l6f18690unj3te4s36xb9'
        ])
            ->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, summary;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$current});
                sort first_release_date asc;
                limit 4;","text/plain")
            ->post('https://api.igdb.com/v4/games')
            ->json();

        $this->comingSoon = $this->formatForView($comingSoonUnformatted);
    }
    public function render()
    {
        return view('livewire.coming-soon');
    }

    private function formatForView($games){
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb','cover_small',$game['cover']['url']),
                'releaseDate' => Carbon::parse($game['first_release_date'])->format('d M Y'),
            ]);
        })->toArray();
    }
}
