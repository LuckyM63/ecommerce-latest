<?php

namespace App\CPU;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PdfManager
{
    public static function upload(string $dir, $pdfFile = null)
    {
        if ($pdfFile != null) {
            $fileName = Carbon::now()->toDateString() . "-" . uniqid() . ".pdf";
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            Storage::disk('public')->put($dir . $fileName, file_get_contents($pdfFile));
        } else {
            return null; // Return null if $pdfFile is null
        }

        return $fileName;
    }

    public static function update(string $dir, $oldPdf, $pdfFile = null)
    {
        if (Storage::disk('public')->exists($dir . $oldPdf)) {
            Storage::disk('public')->delete($dir . $oldPdf);
        }

        $fileName = PdfManager::upload($dir, $pdfFile);

        return $fileName;
    }

    public static function delete($fullPath)
    {
        if (Storage::disk('public')->exists($fullPath)) {
            Storage::disk('public')->delete($fullPath);
        }

        return [
            'success' => 1,
            'message' => 'Removed successfully!'
        ];
    }
}
