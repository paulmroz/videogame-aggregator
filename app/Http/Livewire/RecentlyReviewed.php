<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Str;

class RecentlyReviewed extends Component
{


    public $recentlyReviewed = [];

    public function loadRecentlyReviewed(){
        $before = Carbon::now()->subMonth(2)->timestamp;
        $current = Carbon::now()->timestamp;

        $recentlyReviewedUnformatted = Http::withHeaders([
            'Client-ID' => 'twkxehw9ugkm42sjcotibf3pd46ibd',
            'Authorization' => 'Bearer of1o9jwb9l6f18690unj3te4s36xb9'
        ])
            ->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, summary;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$before}
                & first_release_date < {$current}
                & total_rating_count > 5);
                sort total_rating_count desc;
                limit 3;","text/plain")
            ->post('https://api.igdb.com/v4/games')
            ->json();

            $this->recentlyReviewed = $this->formatForView($recentlyReviewedUnformatted);


            collect($this->recentlyReviewed)->filter(function ($game){
                return $game['rating'];
            })->each(function($game){
                $this->emit('reviewGameWithRatingAdded', [
                    'slug' => 'reviews_'.$game['slug'],
                    'rating' => $game['rating'] / 100
                ]);
            });
    }

    public function render()
    {   

        return view('livewire.recently-reviewed');
    }

    public function formatForView($games){
        return collect($games)->map(function ($game){
            return collect($game)->merge([
                'coverImageUrl' => Str::replaceFirst('thumb','cover_big',$game['cover']['url']),
                'rating' => isset($game['rating']) ? round($game['rating']): null,
                'platforms' => collect($game['platforms'])->pluck('abbreviation')->implode(', '),
            ]);
        })->toArray();
    }
}
