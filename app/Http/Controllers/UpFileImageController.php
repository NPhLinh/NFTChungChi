<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpFileImageController extends Controller
{
    public function uploadFolder(Request $request)
    {
        try {
            $check = $this->isUserToChucCapChungChi();
            if ($check) {
                $urls = [];
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $originalPath = $image->getClientOriginalName();
                        $path = $image->storeAs('uploads/images', $originalPath, 'public');
                        $urls[] = asset('storage/' . $path);
                    }
                }
                return response()->json([
                    'image_urls' => $urls,
                    'status' => true,
                    'message' => 'Tải lên thành công',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Có lỗi xảy ra'
            ]);
        }
    }
}
