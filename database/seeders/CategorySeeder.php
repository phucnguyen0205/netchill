<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Anime','Âm Thực','Bí Ẩn','Chiến Tranh','Chiếu Rạp','Chính Kịch','Chính Luận','Chính Trị',
            'Chuyển Thể','Chương Trình Truyền Hình','Cung Đấu','Cuối Tuần','Cách Mạng','Cổ Trang','Cổ Tích','Cổ Điển',
            'DC','Disney','Đau Thương','Đời Thường','Gay Cấn','Gia Đình','Giáng Sinh','Giả Tưởng','Hoàng Cung',
            'Hoạt Hình','Hài','Hành Động','Hình Sự','Học Đường','Khoa Học','Kinh Dị','Kinh Điển','Kịch Nói','Kỳ Ảo',
            'LGBT+','Live Action','Lãng Mạn','Lịch Sử','Marvel','Miền Viễn Tây','Nghề Nghiệp','Người Mẫu',
            'Nhạc Kịch','Phiêu Lưu','Phép Thuật','Siêu Anh Hùng','Thiếu Nhi','Thần Thoại','Thể Thao',
            'Truyền Hình Thực Tế','Tuổi Trẻ','Tài Liệu','Tâm Lý','Tình Cảm','Tập Luyện','Viễn Tưởng','Võ Thuật','Xuyên Không',
        ];

        $rows = collect($categories)->map(function ($name) {
            return [
                'name'       => $name,
                // Nếu muốn slug giữ nguyên Unicode, thay bằng: (string) Str::of($name)->lower()->replace(' ', '-')
                'slug'       => Str::slug($name),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        // Không tạo trùng: unique theo 'name', cập nhật 'slug' nếu đổi
        Category::upsert($rows, ['name'], ['slug','updated_at']);
    }
}
