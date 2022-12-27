@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        function onClickDelete(id, name, description) {
                window.location='banners/delete/'+id;
            
        }
        $(document).ready(
            function() {
                $("tbody").hover(function() {
                    $(this).css("background", $(".hoverDiv:hover").css("background", '#d1d1d1'));
                }, function() {
                    $(this).css("background", $(".hoverDiv").css("background", '#f8fafc'));
                });
            });
    </script>
    <style>
        .fab {
            width: 70px;
            height: 70px;
            background-color: red;
            border-radius: 50%;
            box-shadow: 0 6px 10px 0 #666;
            transition: all 0.1s ease-in-out;

            font-size: 50px;
            color: white;
            text-align: center;
            line-height: 70px;

            position: fixed;
            right: 50px;
            bottom: 50px;
        }

        .fab:hover {
            box-shadow: 0 6px 14px 0 #666;
            transform: scale(1.05);
        }
    </style>

    @csrf

    <div class="m-5">
        <div class="me-5">
            <div class="float-right "><button type="button" class="btn btn-success float-end"
                    onclick="window.location='{{ route('add.banner') }}'">
                    <h5>
                        <div class="p-2">+ add</div>
                    </h5>
                </button></div>
        </div>
        @foreach ($data as $key)
            <table class="table">
                <tbody>
                    <tr>
                        <td width='30'>{{ $key['id'] }}</td>
                        <td width='300'><img src="{{ $key['photo'] }}" height="100"></td>
                        <td>{{ $key['title'] }}<br>
                            <hr class="mt-2 mb-3">
                            {{-- {{ $key['description'] }} --}}
                        </td>
                        <td>

                            {{-- {{array_keys($key['size_and_price'])}} - {{array_keys($key['size_and_price'])}} --}}
                        </td>
                        <td>
                            {{--                             <button type="button" class="btn btn-success float-end"
                    onclick="window.location='{{ route("add.product") }}'"> --}}
                            <a href="upload/product/edit/{{ $key['id'] }}"><img
                                    src="http://127.0.0.1:8000/storage/image/assets/edit.png" height="24"
                                    width="auto"></a>
                            <button class="btn btn-link"
                                onclick="onClickDelete('{{ $key['id'] }}')">
                                <div class="delete_product"><img src="http://127.0.0.1:8000/storage/image/assets/delete.png"
                                        height="24" width="auto"></div>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
