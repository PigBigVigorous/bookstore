<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đơn hàng</title>
</head>
<body>
    <h1>Cảm ơn bạn đã đặt hàng!</h1>
    <p>Mã đơn hàng: #{{ $order->id }}</p>
    <p>Tổng tiền: {{ number_format($order->total_price) }} VND</p>
    <p>Trạng thái: Đang chờ xử lý</p>
    <p>Chúng tôi sẽ sớm liên hệ giao hàng.</p>
</body>
</html>
