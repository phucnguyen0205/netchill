<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VnPayController extends Controller
{
    protected $vnpUrl;
    protected $vnpTmnCode;
    protected $vnpHashSecret;

    public function __construct()
    {
        // Đặt vào .env: VNP_URL_SANDBOX, VNP_TMN_CODE, VNP_HASH_SECRET
        $this->vnpUrl = config('services.vnpay.url'); // ví dụ: https://sandbox.vnpayment.vn/paymentv2/vpcpay.html
        $this->vnpTmnCode = config('services.vnpay.tmn_code');
        $this->vnpHashSecret = config('services.vnpay.hash_secret');
    }

    public function showTopUp()
    {
        return view('wallet.topup'); // form chọn số tiền
    }

    public function createPayment(Request $request)
    {
        $request->validate(['amount' => 'required|integer|min:1000']);
    
        // Mã tham chiếu VNPAY yêu cầu: vnp_TxnRef -> bạn lưu vào txn_ref
        $txnRef    = 'NC' . now()->format('YmdHis') . Str::upper(Str::random(6)); // duy nhất
        // Nếu bạn có dùng thêm order_code nội bộ thì có thể dùng chung:
        $orderCode = $txnRef;
    
        $payment = Payment::create([
            'user_id'  => $request->user()->id,
            'provider' => 'vnpay',          // <<< cần có vì bạn vừa thêm cột
            'txn_ref'  => $txnRef,          // <<< bắt buộc để đối soát
            'amount'   => (int)$request->amount,
            'currency' => 'VND',
            'status'   => 'pending',
        ]);
    
        // build URL VNPAY với vnp_TxnRef = $txnRef ...
        $payUrl = $this->buildVnpayUrl([
            
            'vnp_TxnRef'   => $txnRef,
            'vnp_Amount'   => $payment->amount * 100, // bắt buộc x100
            'vnp_OrderInfo'=> 'Nap ' . number_format($payment->amount) . ' VND cho user ' . $request->user()->id,
            'vnp_OrderType'=> 'other',
            'vnp_ReturnUrl'=> config('services.vnpay.return_url'),
            'vnp_Locale'   => 'vn',
            'vnp_IpAddr'   => $request->ip(),
        ]);
    
        return redirect()->away($payUrl);
    }
    public function createPremiumCheckout(Request $request)
{
    $request->validate([
        'plan' => 'nullable|string' // rox_monthly, rox_yearly... sau mở rộng
    ]);

    // pricing đơn giản – bạn có thể move ra config/pricing.php
    $plan = $request->input('plan', 'rox_monthly');
    $amount = match ($plan) {
        'rox_yearly'  => 399000, // ví dụ
        default       => 49000,  // rox_monthly
    };

    // txn_ref duy nhất
    $txnRef = 'NC' . now()->format('YmdHis') . Str::upper(Str::random(6));

    $payment = \App\Models\Payment::create([
        'user_id'  => $request->user()->id,
        'provider' => 'vnpay',
        'txn_ref'  => $txnRef,
        'amount'   => $amount,
        'currency' => 'VND',
        'status'   => 'pending',
        'meta'     => [
            'purpose' => 'premium',
            'plan'    => $plan,
        ],
    ]);

    $payUrl = $this->buildVnpayUrl([
        'vnp_TxnRef'    => $txnRef,
        'vnp_Amount'    => $payment->amount * 100,
        'vnp_OrderInfo' => 'Nâng cấp RoX - ' . strtoupper($plan) . ' - User ' . $request->user()->id,
        'vnp_OrderType' => 'other',
        'vnp_ReturnUrl' => config('services.vnpay.return_url'),
        'vnp_Locale'    => 'vn',
        'vnp_IpAddr'    => $request->ip(),
    ]);

    \Log::info('VNPAY Premium URL', ['url' => $payUrl, 'txn_ref' => $txnRef]);

    return redirect()->away($payUrl);
}

    protected function buildVnpayUrl(array $overrides = []): string
{
    // Các tham số bắt buộc theo tài liệu VNPAY
    $params = array_merge([
        'vnp_Version'   => '2.1.0',
        'vnp_Command'   => 'pay',
        'vnp_TmnCode'   => $this->vnpTmnCode,
        'vnp_Amount'    => 0, // số tiền * 100
        'vnp_CurrCode'  => 'VND',
        'vnp_TxnRef'    => (string) Str::uuid(), // sẽ bị override ở $overrides
        'vnp_OrderInfo' => 'Nap tien tai khoan Netchill',
        'vnp_OrderType' => 'other',
        'vnp_Locale'    => 'vn',
        'vnp_ReturnUrl' => config('services.vnpay.return_url'),
        'vnp_IpAddr'    => request()->ip() ?? '127.0.0.1',
        'vnp_CreateDate'=> now()->format('YmdHis'),
    ], $overrides);

    // Sắp xếp key theo thứ tự abc, ký lại
    ksort($params);
    $query = urldecode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
    $secureHash = hash_hmac('sha512', $query, $this->vnpHashSecret);

    // Gắn secure hash vào URL
    $params['vnp_SecureHash'] = $secureHash;

    return $this->vnpUrl . '?' . http_build_query($params);
}

/**
 * (Tuỳ chọn) Hàm verify chữ ký ở trang return
 */
protected function verifyVnpReturn(array $input): bool
{
    $secureHash = $input['vnp_SecureHash'] ?? null;
    if (!$secureHash) return false;

    $data = $input;
    unset($data['vnp_SecureHash'], $data['vnp_SecureHashType']);
    ksort($data);
    $hashData = urldecode(http_build_query($data, '', '&', PHP_QUERY_RFC3986));
    $check = hash_hmac('sha512', $hashData, $this->vnpHashSecret);
    return hash_equals($check, $secureHash);
}
public function vnpReturn(Request $request)
{
    if (!$this->verifyVnpReturn($request->all())) {
        abort(400, 'Invalid signature');
    }

    $txnRef = $request->input('vnp_TxnRef');
    $code   = $request->input('vnp_ResponseCode');  // '00' là thành công
    $txnNo  = $request->input('vnp_TransactionNo');

    /** @var \App\Models\Payment $payment */
    $payment = \App\Models\Payment::where('txn_ref', $txnRef)->firstOrFail();

    $isSuccess = ($code === '00');
    $payment->update([
        'status'       => $isSuccess ? 'paid' : 'failed',
        'provider_txn' => $txnNo,
        'paid_at'      => $isSuccess ? now() : null,
        'meta'         => array_merge((array)$payment->meta, ['vnp_return' => $request->all()]),
    ]);

    // Nếu là thanh toán nâng cấp premium -> set cờ is_premium
    if ($isSuccess && data_get($payment->meta, 'purpose') === 'premium') {
        $user = $payment->user;
        $user->is_premium = true;

        // (tuỳ chọn) Nếu bạn có cột premium_until:
        // $user->premium_until = now()->addMonth();
        $user->save();
    }

    return redirect()->route('wallet.result')->with('status', $payment->status);
}

public function vnpayIpn(Request $request)
{
    $input = $request->all();
    $secureHash = $input['vnp_SecureHash'] ?? null;
    unset($input['vnp_SecureHash'], $input['vnp_SecureHashType']);

    ksort($input);
    $hashData  = urldecode(http_build_query($input, '', '&', PHP_QUERY_RFC3986));
    $checkHash = hash_hmac('sha512', $hashData, $this->vnpHashSecret);

    if (!hash_equals($checkHash, $secureHash ?? '')) {
        return response()->json(['RspCode'=>'97','Message'=>'Invalid signature'], 400);
    }

    $txnRef = $input['vnp_TxnRef'] ?? null;
    $code   = $input['vnp_ResponseCode'] ?? null;
    $amount = isset($input['vnp_Amount']) ? intval($input['vnp_Amount'] / 100) : 0;

    /** @var \App\Models\Payment|null $payment */
    $payment = \App\Models\Payment::where('txn_ref', $txnRef)->first();
    if (!$payment) {
        return response()->json(['RspCode'=>'01','Message'=>'Order not found'], 404);
    }

    if ($code === '00' && $payment->amount == $amount) {
        \DB::transaction(function () use ($payment, $input) {
            $payment->status       = 'paid';
            $payment->paid_at      = now();
            $payment->provider_txn = $input['vnp_TransactionNo'] ?? null;
            $payment->meta         = array_merge((array)$payment->meta, ['vnp_ipn' => $input]);
            $payment->save();

            if (data_get($payment->meta, 'purpose') === 'premium') {
                $user = $payment->user;
                $user->is_premium = true;
                // (tuỳ chọn) $user->premium_until = now()->addMonth();
                $user->save();
            }
        });

        return response()->json(['RspCode'=>'00','Message'=>'Confirm Success']);
    } else {
        $payment->status = 'failed';
        $payment->meta   = array_merge((array)$payment->meta, ['vnp_ipn' => $input]);
        $payment->save();
        return response()->json(['RspCode'=>'00','Message'=>'Confirm Failure recorded']);
    }
}

}
