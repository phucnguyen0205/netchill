<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class PremiumController extends Controller
{
    public function upgrade(Request $request)
    {
        $user = Auth::user();
        $plan = $request->input('plan');

        // Định nghĩa các gói
        $plans = [
            'month1'  => ['price' => 39000,  'days' => 30,  'label' => '1 tháng'],
            'month6'  => ['price' => 189000, 'days' => 180, 'label' => '6 tháng'],
            'month12' => ['price' => 339000, 'days' => 365, 'label' => '12 tháng'],
        ];

        // Kiểm tra gói
        if (!isset($plans[$plan])) {
            return back()->with('status', 'Gói không hợp lệ.');
        }

        $price = $plans[$plan]['price'];
        $days  = $plans[$plan]['days'];
        $label = $plans[$plan]['label'];

        // Kiểm tra số dư
        if ($user->balance < $price) {
            return back()->with('status', 'Số dư không đủ để mua gói ' . $label . '.');
        }

        // Trừ tiền
        $user->decrement('balance', $price);

        // Cập nhật trạng thái premium
        $user->is_premium = true;
        $user->premium_expires_at = now()->addDays($days);
        $user->save();

        // Ghi giao dịch
        if (class_exists(Transaction::class)) {
            Transaction::create([
                'user_id' => $user->id,
                'type'    => 'upgrade',
                'amount'  => -$price,
                'note'    => 'Mua gói Premium ' . $label,
            ]);
        }

        return back()->with('status', 'Đã nâng cấp thành công gói ' . $label . '!');
    }
    public function pricing()
    {
        $user = Auth::user();
        $balance = $user->balance ?? 0;

        return view('premium.pricing', compact('user', 'balance'));
    }
}
