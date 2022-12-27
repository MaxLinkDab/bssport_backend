<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\ProductResource;
use App\Models\Administrator;
use App\Models\Color;
use App\Models\Media;
use App\Models\Product;
use App\Models\SizeAndPrice;
use App\Models\User;
use Database\Factories\ProductFactory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use LDAP\Result;
use SplFileInfo;

class ProductController extends Controller
{

    public function index()
    {
        return ProductResource::collection(Product::all());
    }



    public function store(Request $request)
    {
        // return view('status/error');
        // dd($request['photo'] != null);



        try {
            if ($request['color'] == null && $request['photo'] == null && $request['price'] == null)
                return view('status/error');
            // if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')) {
            if (Product::where('vendor_code', $request['vendor_code'])->exists()) {
                $status = 'Артикул должен быть уникальным';
                return view('status/error', compact('status'));
                return response([
                    'error' => 'Артикул должен быть уникальный'
                ], 400);
            }

            $created_product = Product::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'vendor_code' => $request['vendor_code'],
                'sale' => $request['sale'],
                'material' => $request['material'],
                'gender' => $request['gender'],
            ]);



            for ($i = 0; $i < count($request['photo']); $i++) {
                if (!is_null($request['photo'][$i])) {
                    $filename = $created_product['id'] . '_' . $i . '.jpg';
                    $request->file('photo')[$i]->move(public_path("/storage/image/"), $filename);
                    $photo = url('/storage/image/' . $filename);

                    Media::create([
                        'product_id' => $created_product['id'],
                        'media'      => $photo,
                        'type_media' => 'image',
                    ]);
                }
            }



            //парсим рост
            $listGrowth = [];
            if ($request['size'] != null) {
                if (preg_match("/-/", $request['size'][0])) {
                    for ($i = 0; $i < count($request['size']); $i++) {
                        $middlewareListGrowth = [];
                        list($minGrowth, $maxGrowth) = explode('-', $request['size'][$i]);
                        for ($j = $minGrowth; $j <= $maxGrowth; $j += 5) {
                            SizeAndPrice::create([
                                'product_id' => $created_product['id'],
                                'size' => $j,
                                'price' => $request['price'][$i],
                            ]);
                            array_push($middlewareListGrowth, $j);
                        }
                        $listGrowth[$i] = array_map(null, $middlewareListGrowth);
                        if ($i > 0 && end($listGrowth[$i - 1]) == $listGrowth[$i][0]) {
                            return response([
                                'error' => 'Неверные возрастные размеры',
                            ], 500);
                        }
                    }
                } else {
                    for ($i = 0; $i < count($request['size']); $i++) {
                        $middlewareListGrowth = [];
                        array_push($middlewareListGrowth, $request['size'][$i]);
                        $listGrowth[$i] = array_map(null, $middlewareListGrowth);
                        SizeAndPrice::create([
                            'product_id' => $created_product['id'],
                            'size' => $request['size'][$i],
                            'price' => $request['price'][$i],
                        ]);
                        if ($i > 0 && end($listGrowth[$i - 1]) == $listGrowth[$i][0]) {
                            return response([
                                'error' => 'Неверные возрастные размеры',
                            ], 500);
                        }
                    }
                }
            }


            if ($request['color'] != null) {
                foreach ($request['color'] as $color) {
                    Color::create([
                        'product_id' => $created_product['id'],
                        'color' => $color,
                    ]);
                }
            }

            Product::where('id', $created_product['id'])->update([
                'color' => $created_product['id'],
                'price_and_size' => $created_product['id'],
                'photo' => $created_product['id'] /* 'http://' . $request->ip() . ':8000/storage/image/' . $filename */, //ри выгрузке не забыть поменять на просто $photo

            ]);
            $status = 'Добавлено';
            $data = [];
            // return redirect('/upload');
            return view('/product/upload_product', compact('status'))->with('status', $status);
        } catch (\Throwable $th) {
            $status = 'Не введены обязательные данные';
            return view('/product/upload_product', compact('status'));

            return view('status/error', compact('status'));

            return redirect('/upload');
            return response([
                'error' => 'На сервере произошла ошибка, попробуйте повторить запрос попозже',
                'error_log' => $th
            ], 500);
        }
    }



    public function show(Product $product)
    {
        return new ProductResource($product);
    }


    public function update($product, Request $request)
    {
        // dd($request->request);
        if (Product::find($product) == null || Product::find($product) == '') response(['такого id нету']);
        else if (
            User::where('id', Auth::user()->id)->value('email') !=
            Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')
        ) {
            return response(['message' => 'у вас недостаточно прав на редактирование постов']);
        } else if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email') && Product::find($product) != null) {

            $attrs = $request;
            function getTable(String $table, $product)
            {
                $product = Product::find($product);
                return $product->where('id', $product)->value($table);
            }

            $updateProduct = [
                'id'             => $product,
                'name'           => $attrs['name'],
                'description'    => $attrs['description'],
                'vendor_code'    => $attrs['vendor_code'],
                'price_and_size' => $product,
                'sale'           => $attrs['sale'],
                'color'          => $product,
                'material'       => $attrs['material'],
                'gender'         => $attrs['gender'],
            ];

            SizeAndPrice::where('product_id', $product)->delete();
            $listGrowth = [];
            if ($request['size'] != null) {
                if (preg_match("/-/", $request['size'][0])) {
                    for ($i = 0; $i < count($request['size']); $i++) {
                        $middlewareListGrowth = [];
                        list($minGrowth, $maxGrowth) = explode('-', $request['size'][$i]);
                        for ($j = $minGrowth; $j <= $maxGrowth; $j += 5) {
                            SizeAndPrice::where('product_id', $product)->updateOrCreate([
                                'product_id' => $product,
                                'size' => $j,
                                'price' => $request['price'][$i],
                            ]);
                            array_push($middlewareListGrowth, $j);
                        }
                        $listGrowth[$i] = array_map(null, $middlewareListGrowth);
                        if ($i > 0 && end($listGrowth[$i - 1]) == $listGrowth[$i][0]) {
                            return response([
                                'error' => 'Неверные возрастные размеры',
                            ], 500);
                        }
                    }
                } else {
                    for ($i = 0; $i < count($request['size']); $i++) {
                        $middlewareListGrowth = [];
                        array_push($middlewareListGrowth, $request['size'][$i]);
                        $listGrowth[$i] = array_map(null, $middlewareListGrowth);
                        SizeAndPrice::where('product_id', $product)->updateOrCreate([
                            'product_id' => $product,
                            'size' => $request['size'][$i],
                            'price' => $request['price'][$i],
                        ]);
                        if ($i > 0 && end($listGrowth[$i - 1]) == $listGrowth[$i][0]) {
                            return response([
                                'error' => 'Неверные возрастные размеры',
                            ], 500);
                        }
                    }
                }
            }
            Color::where('product_id', $product)->delete();
            if ($request['color'] != null) {
                foreach ($request['color'] as $color) {
                    Color::where('product_id', $product)->updateOrCreate([
                        'product_id' => $product,
                        'color' => $color,
                    ]);
                }
            }

            $medialist = Media::where('product_id', $product)->get('media');

            for ($i = 0; $i < count($medialist); $i++) {
                $nameFile = str_replace('http://127.0.0.1:8000/storage/image/', '', $medialist[$i]['media']);
                $pathToProject = new SplFileInfo('');
                $pathToPhoto = $pathToProject->getRealPath() . '/storage/image/' . $nameFile;
                unlink($pathToPhoto);
            }
            Media::where('product_id', $product)->delete();

            if ($request['photo'] != null)
                for ($i = 0; $i < count($request['photo']); $i++) {
                    if (!is_null($request['photo'][$i])) {
                        $filename = $product . '_' . $i . '.jpg';
                        $request->file('photo')[$i]->move(public_path("/storage/image/"), $filename);
                        $photo = url('/storage/image/' . $filename);

                        Media::create([
                            'product_id' => $product,
                            'media'      => $photo,
                            'type_media' => 'image',
                        ]);
                    }
                }


            Product::find($product)->update($updateProduct);
            /*             return response([
                'message'=> Product::find($product)->update($updateProduct)
            ]); */
            return redirect('/upload');
            // return new ProductResource(Product::find($product));
        } else return response(['message' => 'произошла ошибка']);
    }


    public function destroy(Request $request, $product)
    {
        // if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')) {

        $medialist = Media::where('product_id', $product)->get('media');

        for ($i = 0; $i < count($medialist); $i++) {
            $nameFile = str_replace('http://127.0.0.1:8000/storage/image/', '', $medialist[$i]['media']);
            $pathToProject = new SplFileInfo('');
            $pathToPhoto = $pathToProject->getRealPath() . '/storage/image/' . $nameFile;
            unlink($pathToPhoto);
        }

        SizeAndPrice::where('product_id', $product)->delete();
        Media::where('product_id', $product)->delete();
        Color::where('product_id', $product)->delete();
        Product::where('id', $product)->delete();

        $status = 'Успешно';
        return redirect('/upload');
        // } else response(['message' => 'недостаточно прав']);
    }

    public function search($name)
    {
        return Product::where("name", "like", "%" . $name . "%")->get();
    }
}
