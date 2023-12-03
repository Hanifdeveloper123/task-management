<?php

namespace App\Http\Controllers;

use App\Mail\Nontification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NontificationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        Mail::to('hanifprojek074@gmail.com')
        ->send(new Nontification());

        return 'email berhasil terkirim';
    }
}
