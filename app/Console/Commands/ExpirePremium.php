<?php
// app/Console/Commands/ExpirePremium.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ExpirePremium extends Command
{
    protected $signature = 'premium:expire';
    protected $description = 'Hạ cấp user hết hạn Premium';

    public function handle()
    {
        $count = User::where('is_premium', true)
            ->whereNotNull('premium_expires_at')
            ->where('premium_expires_at', '<', now())
            ->update(['is_premium' => false]);

        $this->info("Đã hạ cấp {$count} tài khoản hết hạn.");
    }
}
