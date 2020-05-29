<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Facilicom</title>
</head>
<body>
<p>Места:</p>
<form action="/checkin" method="post">
    @csrf
    <p><select size="10" name="account" required>
            <option disabled>Выберите место</option>
            @foreach($accounts as $account)
                <option value="{{$account->getKey()}}">{{$account->name}} {{$account->address}}</option>
            @endforeach
        </select></p>
    <p><input type="submit"  @if (!$canCheckin){{'disabled'}}@endif value="Посетить"></p>
</form>
<p>Чекины:</p>
<table border="1">
    <tr>
        <th>Место</th>
        <th>Адрес</th>
        <th>Время</th>
        <th>Статус</th>
    </tr>
    @foreach($locations as $location)
        <tr>
            <td>{{$location->account->name}}</td>
            <td>{{$location->account->address}}</td>
            <td>{{\Carbon\Carbon::parse($location->Date)->addHours(3)}}</td>
            <td>
                @if($location->IsCheckedOut === 0)
                    <form method="POST" action="/checkout">
                        @csrf
                        <button type="submit" name="account" value="{{$location->DirectumID}}">Завершить</button>
                    </form>
                @else
                    {{'завершен'}}
                @endif
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
