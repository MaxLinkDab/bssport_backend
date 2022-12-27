@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    </script>

    <script type="text/javascript"></script>
    <style>
        .status {
            background-color: #ff0000;
            /* Цвет фона веб-страницы */
        }
    </style>
    @csrf

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Панель добавления баннера</div>
                    <center>
                        <div class="status">
                            <h1>
                            </h1>
                        </div>
                    </center>

                    <div class="card-body">
                        <div class="max-w-2xl mx-auto m-8"></div>
                        <form method="POST" action="/banners/add" enctype="multipart/form-data">
                            @csrf
                            <div class="form-grop">
                                <div class="form-group">
                                    <label for="title">Введите заголовок баннера</label>
                                    <input name="title" placeholder="Заголовок баннера" id="title"
                                        class="form-control">
                                </div>
                                <div class="pt-4"> </div>

                                <div class="form-group">
                                    <label for="product_id">Введите ссылку на продукт (id продукта)</label>
                                    <input name="product_id" placeholder="Ссылка на продукт" id="product_id"
                                        class="form-control">
                                </div>
                      

                                <label for="exampleInputFile">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="photo" 
                                                id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Выберите фото продукта
                                                (в размере 1 шт )</label>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
