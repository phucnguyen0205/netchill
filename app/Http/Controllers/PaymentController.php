<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class PaymentController extends Controller
{
    public function topup(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:100000000',
        ]);

        $user = Auth::user();
        $user->increment('balance', $request->amount);

        // lưu lịch sử (nếu có bảng transactions)
        if (class_exists(Transaction::class)) {
            Transaction::create([
                'user_id' => $user->id,
                'type'    => 'topup',
                'amount'  => $request->amount,
                'note'    => 'Nạp thủ công',
            ]);
        }

        return back()->with('success', 'Đã nạp ' . number_format($request->amount) . 'Ⓡ vào tài khoản.');
    }
}
