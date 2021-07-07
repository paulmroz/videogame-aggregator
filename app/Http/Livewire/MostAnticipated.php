<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Str;

class MostAnticipated extends Component
{

    public $mostAnticipated = [];

    public function loadMostAnticipated()
    {
        $current = Carbon::now()->timestamp;
        $afterFourMonths= Carbon::now()->addMonth(4)->timestamp;

        $mostAnticipatedsUnformatted = Http::withHeaders([
            'Client-ID' => 'twkxehw9ugkm42sjcotibf3pd46ibd',
            'Authorization' => 'Bearer of1o9jwb9l6f18690unj3te4s36xb9'
        ])
            ->withBody("fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug, summary;
                where platforms = (48,49,130,6)
                & (first_release_date >= {$current}
                & first_release_date < {$afterFourMonths});
                sort total_rating_count desc;
                limit 4;","text/plain")
            ->post('https://api.igdb.com/v4/games')
            ->json();

        $this->mostAnticipated = $this->formatForView($mostAnticipatedsUnformatted);
    }

    public function render()
    {
        return view('livewire.most-anticipated');
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
