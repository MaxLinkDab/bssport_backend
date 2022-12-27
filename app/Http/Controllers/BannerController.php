<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Banner;
use App\Models\Color;
use App\Models\Media;
use App\Models\Product;
use App\Models\SizeAndPrice;
use Illuminate\Http\Request;
use SplFileInfo;

class BannerController extends Controller
{
    public function index()
    {
        $banners = [];
        $data=[];

        foreach (Banner::all() as $banner) {
            $product = Product::find($banner->product_id);
                $medias = [];
                $colors = [];
                foreach(Media::where('product_id', $product->id)->get() as $media)
                {
                   $medias[]=$media->media;
                $product['media'] = $medias;
                }
                foreach(SizeAndPrice::where('product_id',$product->price_and_size)->get() as $sizeAndPrices){
                    $sizeAndPrice[$sizeAndPrices->size] =  $sizeAndPrices->price;
                    $product['size_and_price']=$sizeAndPrice;
                }
                
                foreach(Color::where('product_id', $product->color)->get() as $color){
                    $colors[]=$color->color;
                    $product['color']=$colors;

                }
                
                $products = [
                    'id'=>$product->id,
                    'vendor_code'=>$product->vendor_code,
                    'name'=>$product->name,
                    'description'=>$product->description,
                    'photo'=>$product['media'],
                    'size_price'=>$product['size_and_price'],
                    'sale'=>$product->sale,
                    'color'=>$product->color,
                    'material'=>$product->material,
                    'gender'=>$product->gender,

            ];
            
            $data[] = [
                'id' => $banner->id,
                'photo' => $banner->photo,
                'title' => $banner->title,
                'product_id' => $products,
            ];
            unset($colors);
            unset($medias);

        }
        return view('/banners/view_banners', compact('data'))->with('data', $data);

    }
    public function store(Request $request){

        $banner = Banner::create([
            'photo' => null,
            'title' => $request['title'],
            'product_id' => $request['product_id'],
            ]);
            $photo='';
            $bannerId = $banner['id'];
        if (!is_null($request['photo'])) {
            $filename =  'banner'. '_' .$bannerId. '.jpg';
            $request->file('photo')->move(public_path("/storage/image/"), $filename);
            $photo = url('/storage/image/' . $filename);
            }
        Banner::find($bannerId)->update([
            'photo'=> $photo
        ]);

        return view('banners/upload_banner');

    }
    public function destroy($idBanner){

        $banner = Banner::find($idBanner);

        $nameFile = str_replace('http://127.0.0.1:8000/storage/image/', '', $banner['photo']);
        $pathToProject = new SplFileInfo('');
        $pathToPhoto = $pathToProject->getRealPath() . '/storage/image/' . $nameFile;
        unlink($pathToPhoto);

        $banner->delete();
        return redirect('/banners');
    }
}
