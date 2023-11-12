<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\BillSale;
use App\Models\DetailBillSale;

class CheckoutController extends Controller
{
    public function vnpay_payment(Request $request) {
        $userId = auth('client')->user()->maKhachHang;

        $cart = Cart::where('maKhachHang', auth('client')->user()->maKhachHang)->first();

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://127.0.0.1:8000/api/bill";
        $vnp_TmnCode = "FLQYP5IJ";//Mã website tại VNPAY 
        $vnp_HashSecret = "JBOUUFLRZBNYQBEQHKFOHSCDRSVTNVRM"; //Chuỗi bí mật

        $vnp_TxnRef = rand(10000000, 99999999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toan VNPAY';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $cart->tongTienGH * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version
        // $vnp_ExpireDate = $_POST['txtexpire'];
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_ExpireDate"=>$vnp_ExpireDate,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array('code' => '00'
            , 'message' => 'success'
            , 'data' => $vnp_Url);
        
        $result = json_encode($returnData);

        return $result;
    }

    public function updateBill(Request $request) {
        $userId = auth('client')->user()->maKhachHang;

        $cart = Cart::where('maKhachHang', auth('client')->user()->maKhachHang)->first();
        $cartCode = $cart->maGioHang;
        $cartDetails = CartDetail::where('maGioHang', $cartCode)->get();

        $data = $request->query();

        $bill = BillSale::create([
            'maHDB' => rand(10000000, 99999999),
            'maKhachHang' => $userId,
            'ngayLapHD' => $data['vnp_PayDate'],
            'giamGia' => null,
            'PTTT' => $data['vnp_CardType'],
            'tongTienHDB' => $data['vnp_Amount']/100
        ]);

        foreach ($cartDetails as $cartDetail) {
            $product = $cartDetail->product;

            DetailBillSale::create([
                'maChiTietHDB' => rand(10000000, 99999999),
                'maHDB' => $bill->maHDB,
                'maSanPham' => $cartDetail->maSanPham,
                'SL' => $cartDetail->soLuongSP,
                'thanhTien' => $product->giaSanPham
            ]);

            $cartDetail->delete();
        }

        $result = DB::table('view_hdb_sanpham');
        return $result;
    }
}
