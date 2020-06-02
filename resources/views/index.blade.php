<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<head>
    <meta charset="utf-8">
    <title>Facilicom</title>
</head>
<body>
<style>
    h3 {
        margin-top: 20px;
    }
</style>
<div class="container">
    <div class="row row-cols-1">
        <div class="col">
            <h3>Места:</h3>
            <form action="/checkin" method="post">
                @csrf
                <p><select size="10" name="account" required class="form-control">
                        <option disabled>Выберите место</option>
                        @foreach($accounts as $account)
                            <option value="{{$account->getKey()}}">{{$account->name}} {{$account->address}}</option>
                        @endforeach
                    </select></p>
                <p><input type="submit"  class="btn btn-primary" @if (!$canCheckin){{'disabled'}}@endif value="Посетить"></p>
            </form>
        </div>
    </div>
    <div class="row row-cols-1">
        <div class="col">
            <h3>Чекины:</h3>
            <table class="table">
                <tr>
                    <th>Место</th>
                    <th>Адрес</th>
                    <th>Прибытие</th>
                    <th>Убытие</th>
                    <th>Продолжительность</th>
                    <th>Статус</th>
                </tr>
                @foreach($checkins as $checkin)
                    <tr>
                        <td>{{$checkin->getAccount()->name}}</td>
                        <td>{{$checkin->getAccount()->address}}</td>
                        <td>{{$checkin->getArrivedAt()->format('d.m H:i')}}</td>
                        <td>{{ optional($checkin->getDepartedAt())->format('d.m H:i') ?? '-' }}</td>
                        <td>{{ $checkin->getDuration()->format('%h:%i') }}</td>
                        <td>
                            @if(! $checkin->isCheckedOut())
                                <form method="POST" action="/checkout">
                                    @csrf
                                    <button
                                        class="btn btn-outline-success btn-sm"
                                        type="submit"
                                        name="account" value="{{$checkin->getAccount()->getKey()}}">Завершить</button>
                                </form>
                            @else
                                завершен
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
</body>
</html>
