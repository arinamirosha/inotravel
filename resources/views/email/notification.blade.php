<!DOCTYPE html>
<html>
<head>
    <title>Оповещение</title>
</head>
<body>
    <div>Одобренная завка с <b>{{ $booking->arrival }}</b> по <b>{{ $booking->departure }}</b> удалена в связи с тем, что хозяин удалил свое жилье</div>
    <div>Название: <b>{{ $house->name }}</div>
    <div>Город: <b>{{ $house->city }}</div>
</body>
</html>
