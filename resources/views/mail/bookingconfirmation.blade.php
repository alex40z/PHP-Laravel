<div>
    <h1>Подтверждение бронирования</h1><br>
    Отель: {{ $booking->room->hotel->name }}<br>
    Номер: {{ $booking->room->name }}<br>
    Период: с {{ $booking->started_at }} по {{ $booking->finished_at }}<br>
    Cтоимость: {{ $booking->price * $booking->days }} руб. за {{ $booking->days }} ночей
</div>
