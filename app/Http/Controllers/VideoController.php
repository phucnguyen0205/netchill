<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class VideoController extends Controller
{
    /**
     * Tải lên từng chunk của video.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadChunk(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'video_id'    => 'required|string',
            'chunk_index' => 'required|integer',
            'chunk'       => 'required|file',
        ]);

        $videoId = $request->input('video_id');
        $chunkIndex = $request->input('chunk_index');
        $file = $request->file('chunk');

        // Lưu chunk vào thư mục tạm thời
        $chunkDir = "chunks/{$videoId}";
        $chunkPath = "{$chunkDir}/chunk_{$chunkIndex}.part";
        
        // Sử dụng putFileAs để an toàn hơn
        $file->storeAs($chunkDir, "chunk_{$chunkIndex}.part", 'local');

        return response()->json(['ok' => true]);
    }

    /**
     * Ghép các chunk thành một file video hoàn chỉnh.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mergeChunks(Request $request)
    {
        $this->authorize('admin');

        $request->validate([
            'video_id'     => 'required|string',
            'total_chunks' => 'required|integer',
            'ext'          => 'nullable|string', // mp4, mkv ...
        ]);

        $videoId = $request->input('video_id');
        $total = (int) $request->input('total_chunks');
        $ext = $request->input('ext') ?: 'mp4';

        // Tên file video cuối cùng
        $finalName = $videoId . '.' . $ext;
        $finalPath = "videos/{$finalName}"; // storage/app/public/videos

        // Tạo file đích để ghi vào
        $finalFile = Storage::disk('public')->path($finalPath);

        // Đảm bảo thư mục đích tồn tại
        if (!Storage::disk('public')->exists('videos')) {
            Storage::disk('public')->makeDirectory('videos');
        }

        // Mở file đích để ghi
        $out = fopen($finalFile, 'wb');


        // Ghép từng chunk
        for ($i = 0; $i < $total; $i++) {
            $chunkPath = "chunks/{$videoId}/chunk_{$i}.part";
            
            if (!Storage::disk('local')->exists($chunkPath)) {
                fclose($out);
                throw ValidationException::withMessages([
                    'chunk' => "Thiếu chunk {$i}",
                ]);
            }

            $chunkContents = Storage::disk('local')->get($chunkPath);
            fwrite($out, $chunkContents);

            // Xóa chunk sau khi ghép
            Storage::disk('local')->delete($chunkPath);
        }

        fclose($out);

        // Xóa thư mục chunks sau khi hoàn tất
        Storage::disk('local')->deleteDirectory("chunks/{$videoId}");

        return response()->json([
            'ok'        => true,
            'file_name' => $finalName, 
            'url'       => asset('storage/videos/' . $finalName),
        ]);
    }
}