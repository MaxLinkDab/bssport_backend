<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
// use Illuminate\Filesystem\FilesystemManager as Storage;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function prouctsImage($photo, $path = 'product'){
        if(!$photo){
            return null;
        }

        $filename = time().'.jpg';

        Storage::disk($path)->put($filename, base64_decode($photo));

        return URL::to('/').'/storage/'.$path.'/'.$filename;
    }
}
