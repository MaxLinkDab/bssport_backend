<?php

namespace App\Http\Controllers\Api;

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

        $attrs = $request->validate([
            'name' => 'required|string',
            'vendor_code' => 'required',
            'price' => 'required',
            // 'growth' => 'required',
        ]);

        try {
            // if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')) {
            if (Product::where('vendor_code', $attrs['vendor_code'])->exists())
                return response([
                    'error' => 'Артикул должен быть уникальный'
                ], 400);

            $created_product = Product::create([
                'name' => $attrs['name'],
                'description' => $request['description'],
                'vendor_code' => $attrs['vendor_code'],
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
            if ($request['growth'] != null) {
                if (preg_match("/-/", $request['growth'][0])) {
                    for ($i = 0; $i < count($request['growth']); $i++) {
                        $middlewareListGrowth = [];
                        list($minGrowth, $maxGrowth) = explode('-', $request['growth'][$i]);
                        for ($j = $minGrowth; $j <= $maxGrowth; $j += 5) {
                            SizeAndPrice::create([
                                'product_id' => $created_product['id'],
                                'size' => $j,
                                'price' => $attrs['price'][$i],
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
                    for ($i = 0; $i < count($request['growth']); $i++) {
                        $middlewareListGrowth = [];
                        array_push($middlewareListGrowth, $request['growth'][$i]);
                        $listGrowth[$i] = array_map(null, $middlewareListGrowth);
                        SizeAndPrice::create([
                            'product_id' => $created_product['id'],
                            'size' => $request['growth'][$i],
                            'price' => $attrs['price'][$i],
                        ]);
                        if ($i > 0 && end($listGrowth[$i - 1]) == $listGrowth[$i][0]) {
                            return response([
                                'error' => 'Неверные возрастные размеры',
                            ], 500);
                        }
                    }
                }
            }
            foreach ($request['color'] as $color) {
                Color::create([
                    'product_id'=>$created_product['id'],
                    'color'=>$color,
                ]);
            }

            Product::where('id', $created_product['id'])->update([
                'color'=>$created_product['id'],
                'price_and_size' => $created_product['id'],
                'photo' => $created_product['id'] /* 'http://' . $request->ip() . ':8000/storage/image/' . $filename */, //ри выгрузке не забыть поменять на просто $photo

            ]);

            // if()
            return response(['succes' => 'Товар добавлен'], 200);
            return new ProductResource(Product::find($created_product->id));
            /*             } else return response(['message' => 'недостаточно прав']); */
        } catch (\Throwable $th) {
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


    public function update($idProduct, ProductStoreRequest $request)
    {
        if (Product::find($idProduct) == null || Product::find($idProduct) == '') response(['такого id нету']);
        else if (
            User::where('id', Auth::user()->id)->value('email') !=
            Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')
        ) {
            return response(['message' => 'у вас недостаточно прав на редактирование постов']);
        } else if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email') && Product::find($idProduct) != null) {

            $attrs = $request;/* ->validate([
            'name' => 'string',
            'description' => 'string',
            'vendor_code' => 'string',
            'price_and_size' => 'string',
            'color' => 'string',
            'material' => 'string',
            'gender' => 'string',
            'kid' => 'boolean',
        ]); */
            // return response(['id_product'=>Product::find($idProduct)]);
            function getTable(String $table, $idProduct)
            {
                $product = Product::find($idProduct);
                return $product->where('id', $idProduct)->value($table);
            }

            $updateProduct = [
                'id'             => $idProduct,
                'name'           => $attrs['name'] ?? getTable('name', $idProduct),
                'description'    => $attrs['description'] ?? getTable('description', $idProduct),
                'vendor_code'    => $attrs['vendor_code'] ?? getTable('vendor_code', $idProduct),
                'price_and_size' => $idProduct ?? getTable('price_and_size', $idProduct),
                'sale'           => $idProduct ?? getTable('sale', $idProduct),
                'color'          => $attrs['color'] ?? getTable('color', $idProduct),
                'material'       => $attrs['material'] ?? getTable('material', $idProduct),
                'gender'         => $attrs['gender'] ?? getTable('gender', $idProduct),
            ];


            SizeAndPrice::where('product_id', $idProduct)->delete();
            list($minGrowth, $maxGrowth) = explode('-', $attrs['growth']);
            for ($i = $minGrowth; $i <= $maxGrowth; $i += 5) {
                SizeAndPrice::where('product_id', $idProduct)->updateOrCreate([
                    'product_id' => $idProduct,
                    'size' => $i,
                    'price' => $attrs['price'],
                ]);
            }

            list($minGrowthKid, $maxGrowthKid) = explode('-', $attrs['growth_kid']);
            for ($i = $minGrowthKid; $i <= $maxGrowthKid; $i += 5) {
                SizeAndPrice::where('product_id', $idProduct)->updateOrCreate([
                    'product_id' => $idProduct,
                    'size' => $i,
                    'price' => $attrs['price_kid'],
                ]);
            }

            Product::find($idProduct)->update($updateProduct);
            /*             return response([
                'message'=> Product::find($idProduct)->update($updateProduct)
            ]); */
            return new ProductResource(Product::find($idProduct));
        } else return response(['message' => 'произошла ошибка']);
    }


    public function destroy(Request $request, Product $product)
    {
        $attrs = $request->validate([
            'delete' => 'required'
        ]);
        if (User::where('id', Auth::user()->id)->value('email') == Administrator::all()->where('email', User::where('id', Auth::user()->id)->value('email'))->value('email')) {

            $medialist = Media::where('product_id', $attrs['delete'])->get('media');

            for ($i = 0; $i < count($medialist); $i++) {
                $nameFile = str_replace('http://127.0.0.1:8000/storage/image/', '', $medialist[$i]['media']);
                $pathToProject = new SplFileInfo('');
                $pathToPhoto = $pathToProject->getRealPath() . '/storage/image/' . $nameFile;
                unlink($pathToPhoto);
            }

            SizeAndPrice::where('product_id', $attrs['delete'])->delete();
            Media::where('product_id', $attrs['delete'])->delete();
            Product::where('id', $attrs['delete'])->delete();

            return response(['delete' => $attrs['delete']]);
        } else response(['message' => 'недостаточно прав']);
    }

    public function search($name)
    {
        return Product::where("name", "like", "%" . $name . "%")->get();
    }
}
