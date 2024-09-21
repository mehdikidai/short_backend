<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if (!$request->hasFile('photo')) {
            return abort(404);
        }

        if ($user->photo) {

            $previousPhotoPath = str_replace(url('/'), '', $user->photo);

            File::delete(public_path($previousPhotoPath));
        }

        $photo = $request->file('photo');

        $photoName = Str::random(20) . time() . '.' . $photo->getClientOriginalExtension();

        $photo->move(public_path('photos'), $photoName);

        $photoPath = url('photos/' . $photoName);

        $user->photo = $photoPath;

        $user->save();

        Cache::forget('user_' . $user->id);

        return response()->json(['message' => 'Image uploaded successfully', 'photo' => $photoPath]);
    }
}
