<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    /**
     * Display a listing of media files.
     */
    public function index()
    {
        $mediaPath = storage_path('app/public/media');
        $files = [];
        
        if (is_dir($mediaPath)) {
            $allFiles = glob($mediaPath . '/*');
            foreach ($allFiles as $file) {
                if (is_file($file)) {
                    $filename = basename($file);
                    $files[] = [
                        'name' => $filename,
                        'url' => asset('storage/media/' . $filename),
                        'size' => $this->formatBytes(filesize($file)),
                        'type' => mime_content_type($file),
                        'modified' => date('Y-m-d H:i:s', filemtime($file)),
                    ];
                }
            }
        }
        
        // Sort by modified date descending
        usort($files, function($a, $b) {
            return strtotime($b['modified']) - strtotime($a['modified']);
        });
        
        return view('admin.media.index', compact('files'));
    }

    /**
     * Upload new media file.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/media', $filename);

        return redirect()->route('admin.media.index')
            ->with('success', 'File berhasil diupload.');
    }

    /**
     * Delete media file.
     */
    public function destroy($filename)
    {
        $path = 'public/media/' . $filename;
        
        if (Storage::exists($path)) {
            Storage::delete($path);
            return redirect()->route('admin.media.index')
                ->with('success', 'File berhasil dihapus.');
        }

        return redirect()->route('admin.media.index')
            ->with('error', 'File tidak ditemukan.');
    }

    /**
     * Format bytes to human readable.
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
?>
