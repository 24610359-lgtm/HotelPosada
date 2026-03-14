<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    public function index()
    {
        return view('youtube');
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $pageToken = $request->pageToken;

        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $q,
            'maxResults' => 8,
            'type' => 'video',
            'key' => env('YOUTUBE_API_KEY', env('YOUTUBE_KEY')),
            'pageToken' => $pageToken
        ]);

        return $response->json();
    }
}
