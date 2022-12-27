@extends('layouts.app')

@section('content')

    <style>
      td {
        text-align: center;
      }
    </style>
    <center>
        <table class="iksweb" alert="center">
            <tbody>
                <tr>
                    <td>
                        <div class="d-flex justify-content-center m-3">
                           <a href="{{url('/upload/product')}}">
                            <button type="button" class="btn btn-outline-secondary">
                                <div class="p-3">Редактировать товар</div>
                            </button></a>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center m-3">
                           <a href="{{url('banners')}}"> <button type="button" class="btn btn-outline-secondary">
                                <div class="p-3">Редактировать баннеры</div>
                            </button></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="d-flex justify-content-center m-3">
                            <button type="button" class="btn btn-outline-secondary">
                                <div class="p-3">Редактировать администраторов</div>
                            </button>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center m-3">
                            <button type="button" class="btn btn-outline-secondary">
                                <div class="p-3">Редактировать промокоды</div>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </center>
@endsection
