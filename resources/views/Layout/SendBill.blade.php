<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hóa Đơn Bán</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    .invoice {
      border: 1px solid #ddd;
      padding: 20px;
      max-width: 600px;
      margin: auto;
    }

    .invoice-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .invoice-items {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .invoice-items th, .invoice-items td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .invoice-total {
      margin-top: 20px;
      text-align: right;
    }
  </style>
</head>
<body>
<div class="invoice" id="bill">

  <div class="button button--primary" (click)="exportToPDF()">Xuất PDF</div>
  <div class="invoice-header">
    <h2>Hóa Đơn Bán</h2>
    {{-- <p>Ngày: {{ ngayBan }}</p> --}}
    {{-- <p>Mã Hóa Đơn: {{ maHoaDon }}</p> --}}
  </div>

  <table class="invoice-items">
    <thead>
    <tr>
      <th>Sản Phẩm</th>
      <th>Đơn Giá</th>
      <th>Số Lượng</th>
      <th>Thành Tiền</th>
    </tr>
    </thead>
    <tbody>
        @foreach($result as $item)
        <tr>
            <td>{{ $item->tenSanPham }}</td>
            <td>{{ $item->giaSanPham }}</td>
            <td>{{ $item->SL }}</td>
            <td>{{ $item->thanhTien }}</td>
        </tr>
        @endforeach
    </tbody>
  </table>

  <div class="invoice-total">
    <p>Tổng Tiền: {{ $bill->tongTienHDB }}</p>
  </div>
</div>
</body>
</html>
