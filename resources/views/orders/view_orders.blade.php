@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>

<script type="text/javascript">
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
        * {
            box-sizing: border-box;
        }

        /* Create two equal columns that floats next to each other */
        .column {
            float: left;
            width: 100%;
            padding: 10px;
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* Style the buttons */
        .btn {
            border: none;
            outline: none;
            padding: 12px 16px;
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #ddd;
        }

        .btn.active {
            background-color: #666;
            color: white;
        }


        table {
            border: 3px solid black;
            border-collapse: separate;
            border-spacing: 20px 20px;
        }

        table .even {
            background: #c0c0c0;
        }

        tr.headerTable {
            background: #666;
            background-color: rgb(138, 138, 138);
            border: 3px solid black;
            border-collapse: separate;
            border-spacing: 20px 20px;
        }

        div.hoverDiv:hover {
            background-color: #000000;
        }
    </style>


    <table class="table">
        <thead>
            <tr class='table table-dark'>
                <th>#</th>
                <th>
                    изображение
                </th>
                <th> Имя/Описание</th>
                <th>Информация по заказу</th>
                <th>
                    <center>Статус заказа</center>
                </th>
            </tr>
        </thead>
        @foreach ($data['order_info'] as $key)


            <tbody class="hoverDiv">
                <tr>
                    <th rowspan="7">{{ $key['id'] }};<br> id заказа{{ $key['id_order'] }}</th>
                    <th rowspan="7">
                        <img src="{{ $key['media'] }}" height="200", width="300">
                    </th>
                    <th rowspan="2">{{ $key['name'] }}</th>
                    <th> цена: {{ $key['price'] }};<br> скидка: {{ $key['sale'] ?? '0' }};<br>сумма:
                        {{ $key['sum'] }}</th>
                    <th rowspan="7">
                        <center>{{ $key['status'] }}</center>
                    </th>
                    <th rowspan="7">
                        <a href="{{ url('/upload/view_orders/detail/' . $key['id_order']) }}"> Редактировать</a>
                    </th>

                </tr>
                <tr>
                    <th>количество: {{ $key['amount'] }}</th>
                </tr>
                <tr>
                    <th rowspan="5">{{ $key['description'] }}</th>
                    <th>цвет: {{ $key['color'] }}</th>
                </tr>
                <tr>
                    <th>размер: {{ $key['size'] }}</th>
                </tr>
                <tr>
                    <th>адрес: {{ $key['address'] }}</th>
                </tr>
                <tr>
                    <th>номер телефона:{{ $key['phone_number'] }}</th>
                </tr>
                <tr>
                    <th>ФИО: {{ $key['SNP'] }}</th>
                </tr>
            </tbody>

        @endforeach

    </table>
    <div class="space"></div>

@endsection
