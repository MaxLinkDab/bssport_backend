<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Administrator;
use App\Models\User;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SplFileInfo;
use Symfony\Component\Console\Input\Input;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return BannerResource::collection(Banner::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')) {
                if ($request->file('photo') == null && $request['title'] == null && $request['product_id'] == null)
                    return response([
                        'error' => 'хотя бы одно поле должно быть заполнено',
                        'var' => $request->file('photo'),
                        'var1' => $request['title'],
                        'var2s' => $request['product_id'],
                    ]);
                else {
                    $count_banner = Banner::all()->count();
                    while ($count_banner >= 10) {
                        Banner::first()->delete();
                        $count_banner--;
                    }

                    $created_banner = Banner::create();
                    if ($request['title'] != null) {
                        Banner::where('id', $created_banner['id'])->update([
                            'title' => $request['title']
                        ]);
                    }
                    if ($request['product_id'] != null) {
                        // if()
                        Banner::where('id', $created_banner['id'])->update([
                            'product_id' => $request['product_id']
                        ]);
                    }
                    if ($request->file('photo') != null) {
                        $filename = 'banner' . '_' . $created_banner['id'] . '.jpg';
                        $request->file('photo')->move(public_path("/storage/image/"), $filename);
                        $photo = url('/storage/image/' . $filename);
                        Banner::where('id', $created_banner['id'])->update([
                            'photo' => $photo,
                        ]);
                    }
                    return response([
                        'succes' => 'создано',
                        'result' => new BannerResource(Banner::find($created_banner['id'])),
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response([
                'error' => 'На сервере произошла ошибка, попробуйте позже',
                'error_log' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Banner $banner)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $attrs = $request->validate([
            'banner_id' => 'required'
        ]);
        try {
            if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')) {
                if (!Banner::where('id', $attrs['banner_id'])->exists()) {
                    return response([
                        'error' => 'Запись не найдена',
                    ], 404);
                } else {
                    if (Banner::where('id', $attrs['banner_id'])->value('photo') != '') {
                        $nameFile = str_replace('http://127.0.0.1:8000/storage/image/', '', Banner::where('id', $attrs['banner_id'])->value('photo'));
/*                         return response([
                            'error' => $nameFile,
                        ], 404); */
                        $pathToProject = new SplFileInfo('');
                        $pathToPhoto = $pathToProject->getRealPath() . '/storage/image/' . $nameFile;
                        unlink($pathToPhoto);
                    }
                    Banner::find($attrs['banner_id'])->delete();
                    return response([
                        'succes' => 'Успешно',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response([
                'error' => 'На сервере произошла ошибка, попробуйте позже',
                'error_log' => $th->getMessage()
            ], 500);
        }
    }
}
