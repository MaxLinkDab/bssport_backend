@extends('layouts.app')

@section('content')
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(".m-5").hover(function() {
                $(this).css("background", $(".m-5:hover").css("background-color", 'black'));
            }, function() {
                $(this).css("background", $(".m-5").css("background-color", 'white'));
            });
            $('table.table').css('background') = 'red';
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
    @foreach ($data['order_info'] as $key)
        <div class="ms-5">
            <h1>{{ $key['name'] }}</h1>
        </div>
        <div class="m-5">
            <table class="table" align="left">
                <tbody align="left">
                    <tr>
                        <td width='650'><img src="{{ $key['media'] }}" height="300"></td>
                        <td>
                            <h5> {{ $key['description'] }}</h5>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Размер: {{ $key['size'] }} <br>
                            Цена: {{ $key['price'] }} <br>
                            Скидка: {{ $key['sale'] ?? 0 }} <br>
                            Количество: {{ $key['amount'] }} <br>
                            Сумма: {{ $key['sum'] }} <br>

                        </td>
                        <td>
                            @if ($key['status'] != 'Не оплачен')
                            <form action="/orders/update_status/{{ $key['id_order'] }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                
                                Статус: <select name="status">
                                        <option value="{{$key['status']}}">{{$key['status']}}</option>
                                        <option value="Заказ собирается">Заказ собирается</option>
                                        <option value="В пути">В пути</option>
                                        <option value="В пункте выдачи">В пункте выдачи</option>
                                        <option value="Произошла ошибка">Произошла ошибка</option>
                                    </select>
                                    <p><input type="submit" value="Применить"></p>
                                </form>
                            @endif
                            @if ($key['status'] == 'Не оплачен')
                                Статус: {{ $key['status'] }}
                            @endif


                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            ФИО Покупателя: {{ $key['SNP'] }} <br>
                            Номер телефона: {{ $key['SNP'] }} <br>
                            Адрес: {{ $key['address'] }}

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
@endsection
