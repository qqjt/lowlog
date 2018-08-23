<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        if ($file = $request->file('file')) {
            try {
                $allowedExtensions = ["png", "jpg", "gif", 'jpeg'];
                $extension = strtolower($file->getClientOriginalExtension());
                if (!in_array($extension, $allowedExtensions)) {
                    throw new \Exception(__("You can only upload image with extensions: ") . implode($allowedExtensions, ','));
                }
                $folderName = 'uploads/images/' . date("Ym", time()) . '/' . date("d", time()) . '/' . \Auth::user()->hashid;
                $filePath = $file->store($folderName, 'public');
                $data['filename'] = url('storage'. '/' . $filePath);
            } catch (\Exception $exception) {
                return ['error' => $exception->getMessage()];
            }
        } else {
            $data['error'] = __("Error while uploading file");
        }
        return $data;
    }
}
