<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $photo = $request->file('photo');

        $photoName = time() . '.' . $photo->getClientOriginalExtension();

        $photo->move(public_path('photos'), $photoName);

        $photoPath = url('photos/' . $photoName);

        $user->photo = $photoPath;

        $user->save();

        Cache::forget('user_' . $user->id);

        return response()->json(['message' => 'Image uploaded successfully', 'photo' => $photoPath]);
    }
}
