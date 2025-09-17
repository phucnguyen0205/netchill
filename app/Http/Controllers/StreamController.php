<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamController extends Controller
{
    /**
     * Stream video bằng cách xử lý HTTP Range.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movie  $movie
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function stream(Request $request, Movie $movie): StreamedResponse
    {
        // Xây dựng đường dẫn đến file trong thư mục public của storage
        $path = 'videos/' . $movie->file_name;

        // Kiểm tra xem file có tồn tại không
        if (!Storage::disk('public')->exists($path)) {
            // Nếu không tìm thấy, trả về lỗi 404
            abort(404, 'Video không được tìm thấy.');
        }

        // Lấy đường dẫn vật lý và kích thước của file
        $filePath = Storage::disk('public')->path($path);
        $size = Storage::disk('public')->size($path);
        $file = fopen($filePath, 'rb');

        $start = 0;
        $end = $size - 1;
        $status = 200;

        // Lấy MIME type động để hỗ trợ nhiều định dạng video khác nhau
        $mimeType = Storage::disk('public')->mimeType($path);
        if (!$mimeType) {
            $mimeType = 'video/mp4'; // Fallback nếu không xác định được MIME type
        }
        
        $headers = [
            'Content-Type' => $mimeType,
            'Accept-Ranges' => 'bytes',
        ];

        // Xử lý header HTTP_RANGE để hỗ trợ tua và nhảy video
        $rangeHeader = $request->header('Range');
        if ($rangeHeader && preg_match('/bytes=(\d+)-(\d+)?/', $rangeHeader, $matches)) {
            $start = intval($matches[1]);
            
            // Nếu có end byte, sử dụng nó. Nếu không, sử dụng kích thước file
            if (isset($matches[2]) && $matches[2] !== '') {
                $end = min(intval($matches[2]), $size - 1);
            }
            
            $status = 206; // Mã status cho Partial Content (Nội dung một phần)
            $length = $end - $start + 1;

            $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
            $headers['Content-Length'] = $length;

        } else {
            // Nếu không có HTTP Range, trả về toàn bộ file
            $length = $size;
            $headers['Content-Length'] = $length;
        }

        // Tạo luồng để gửi file từng phần về trình duyệt
        return response()->stream(function () use ($file, $start, $length) {
            fseek($file, $start);
            $buffer = 1024 * 64; // đọc 64KB một lần
            $bytesSent = 0;

            while (!feof($file) && $bytesSent < $length) {
                $read = min($buffer, $length - $bytesSent);
                echo fread($file, $read);
                $bytesSent += $read;
                @ob_flush();
                flush();
            }

            fclose($file);
        }, $status, $headers);
    }
}