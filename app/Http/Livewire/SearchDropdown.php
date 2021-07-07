<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class SearchDropdown extends Component
{

    public $search = '';

    public $searchResults = [];

    public function render()
    {
        if(strlen($this->search)>=2) {
        $this->searchResults = Http::withHeaders([
            'Client-ID' => 'twkxehw9ugkm42sjcotibf3pd46ibd',
            'Authorization' => 'Bearer of1o9jwb9l6f18690unj3te4s36xb9'
        ])
            ->withBody("
                    search \"{$this->search}\";
                    fields name, slug, cover.url;
                    limit 9;",
                "text/plain")
            ->post('https://api.igdb.com/v4/games')
            ->json();
         }
        //dump($this->searchResults);
        return view('livewire.search-dropdown');
    }
}
