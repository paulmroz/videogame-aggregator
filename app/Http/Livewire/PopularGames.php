<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Str;

class PopularGames extends Component
{   

    public $popularGames = [];

    public function loadPopularGames(){

        $before = Carbon::now()->subMonth(2)->timestamp;
        $after = Carbon::now()->addMonth(2)->timestamp;

        $popularGamesUnformatted  = Cache::remember('popular-games', 7, function () use ($before, $after) {
            
            return Http::withHeaders([
                'Client-ID' => 'twkxehw9ugkm42sjcotibf3pd46ibd',
                'Authorization' => 'Bearer of1o9jwb9l6f18690unj3te4s36xb9'
            ])
                ->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$after}
                    & total_rating_count > 5);
                    sort total_rating_count desc;
                    limit 12;","text/plain")
                ->post('https://api.igdb.com/v4/games')
                ->json();
        });

        

        $this->popularGames = $this->formatForView($popularGamesUnformatted);

        collect($this->popularGames)->filter(function ($game){
            return $game['rating'];
        })->each(function($game){
            $this->emit('gameWithRatingAdded', [
                'slug' => $game['slug'],
                'rating' => $game['rating'] / 100
            ]);
        });
    }

    public function render()
    {
        return view('livewire.popular-games');
    }


    private function formatForView($games){
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb','cover_big',$game['cover']['url']),
                'rating' => isset($game['rating']) ? round($game['rating']): null,
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', '),
            ]);
        })->toArray();
    }
}
