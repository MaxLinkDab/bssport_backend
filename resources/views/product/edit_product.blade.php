@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    </script>

    <script type="text/javascript">
        var i = 0;
        $(document).ready(function(){

            $("#colorTable").append(
                '@foreach ($data['color'] as $color){{$loop->index}}<tr><td><div class="form-group"><label for="color">{{$loop->index}} Введите цвет продукта</label><input name="color[]" placeholder="Цвет продукта" id="color"class="form-control" value="{{$color}}"></div></td><td class="align-bottom">@if($loop->index==0)<button onclick="addLabelColor()" type="button" name="add" id="add" class="btn btn-success">Добавить</button>@else <button type="button" class="btn btn-danger remove_color">Удалить</button> @endif</td></tr>@endforeach'
                );
                $("#priceAndSizeTable").append(
                    '@foreach ($data['size'] as $size){{$loop->index}}<tr><td><div class="form-group"><label for="price">{{$loop->index}} Введите цену продукта</label><input name="price[]" placeholder="Цена продукта" id="price"class="form-control" value ="{{$data['price'][$loop->index]}}"></div></td><td><div class="form-group"><label for="size">Введите размеры продукта (пример:100-150)</label><input name="size[]" placeholder="Размер продукта" id="size" class="form-control" value="{{$size}}"> </div></td><td class="align-bottom">@if($loop->index==0)<button type="button" class="btn btn-success" onclick="addLabelPriceSize()">Добавить</button>@else <button type="button" class="btn btn-danger remove_size_and_price">Удалить</button>@endif</td></tr>@endforeach'
                );
            });
            function addLabelPriceSize() {
            ++i;
            $("#priceAndSizeTable").append(
                '<tr><td><div class="form-group"><label for="price">Введите цену продукта</label><input name="price[]" placeholder="Цена продукта" id="price"class="form-control"></div></td><td><div class="form-group"><label for="size">Введите размеры продукта (пример:100-150)</label><input name="size[]" placeholder="Размер продукта" id="size" class="form-control"> </div></td><td class="align-bottom"><button type="button" class="btn btn-danger remove_size_and_price">Удалить</button></td></tr>'
            );
        };

         function addLabelColor(){
            ++i;
            $("#colorTable").append(
                '<tr><td><div class="form-group"><label for="color">Введите цену продукта</label><input name="color[]" placeholder="Цена продукта" id="color"class="form-control"></div></td><td class="align-bottom"><button type="button" class="btn btn-danger remove_color">Удалить</button></td></tr>'
            );
        }

        $(document).on('click', '.remove_size_and_price', function() {
            $(this).parents('tr').remove();
        });
        $(document).on('click', '.remove_color', function() {
            $(this).parents('tr').remove();
        });
    </script>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Панель обновления продукта</div>
                        
                    <div class="card-body">
                        <div class="max-w-2xl mx-auto m-8"></div>
                        <form method="POST" action="/product/update/{{$data->id}}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-grop">
                                <div class="form-group">
                                    <label for="name">Введите название продукта</label>
                                    <input name="name" placeholder="Название продукта" id="name"
                                        class="form-control" value='{{$data->name}}'>
                                </div>
                                <div class="pt-4"> </div>

                                <div class="form-group">
                                    <label for="description">Введите описание продукта</label>
                                    <input name="description" placeholder="Описание продукта" id="description"
                                        class="form-control" value='{{$data->description}}'>
                                </div>
                                <div class="pt-4"> </div>

                                <div class="form-group">
                                    <label for="vendor_code">Введите артикул продукта</label>
                                    <input name="vendor_code" placeholder="Артикул продукта" id="vendor_code"
                                        class="form-control" value='{{$data->vendor_code}}'>
                                </div>
                                <div class="pt-4"> </div>
                                <table id="priceAndSizeTable">
{{--                                     <tr>
                                        <td>
                                            <div class="form-group">
                                                <label for="price">Введите цену продукта</label>
                                                <input name="price[]" placeholder="Цена продукта" id="price"
                                                    class="form-control">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <label for="size">Введите размеры продукта (пример:
                                                    100-150,XL,100)</label>
                                                <input name="size[]" placeholder="Размер продукта" id="size"
                                                    class="form-control">
                                            </div>
                                        </td>

                                        <td class="align-bottom">
                                            <button onclick="addLabelPriceSize()" type="button" name="add" id="add"
                                                class="btn btn-success ">Добавить поля</button>
                                        </td> --}}
                                </table>
                                </tr>
                                <div class="pt-4"> </div>

                                <div class="form-group">
                                    <label for="sale">Введите скидку для продукта</label>
                                    <input name="sale" placeholder="Размер продукта" id="sale" class="form-control" value='{{$data->sale}}'>
                                </div>
                                <div class="pt-4"> </div>

                                <table id="colorTable">
{{--                                     <tr>
                                        <td>
                                            


                                            <div class="form-group">
                                                <label for="color">Введите цвет продукта</label>
                                                <input name="color[]" placeholder="Цена продукта" id="color"
                                                class="form-control" value="{{$color->color}}">
                                            </div>
                                        </td>
                                            
                                        <td class="align-bottom">
                                            
                                            Добавить поля</button>
                                        </td>

 --}}
                                </table>


                                <div class="form-group">
                                    <label for="material">Введите материал продукта</label>
                                    <input name="material" placeholder="Материал продукта" id="material"
                                        class="form-control" value='{{$data->material}}'>
                                </div>
                                <div class="pt-4"> </div>

                                <div class="form-group">
                                    <label for="gender">Введите пол продукта</label>
                                    <input name="gender" placeholder="Пол продукта" id="name" class="form-control" value='{{$data->gender}}'>
                                </div>
                                <div class="pt-4"> </div>

                                <label for="exampleInputFile">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="photo[]" multiple
                                                id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Выберите фото продукта
                                                (Максимум 10)</label>
                                            <div class="pt-4"> </div>
                                        </div>
                                        <div class="mt-5 pt-5">
                                            <button type="submit" class="btn btn-success">отправить данные</button>
                                        </div>
                                        <div class="pt-4"> </div>

                                    </div>
                                </label>
                            </div>

                    </div>
                    {{-- @endforeach --}}

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
