<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
class WondeController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new \Wonde\Client(config('wondeConstants.wonde.bearer_token'));
    }

    public function index()
    {
        // Get school data
        $schools = $this->client->schools->all();
        return view('wonde.index', [
            'schools' => $schools
        ]);
    }
}
