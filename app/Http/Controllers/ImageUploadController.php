<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ImageProfileRequest;


class ImageUploadController extends Controller
{

    public $key_tinify;

    public function __construct()
    {

        $this->key_tinify = config('services.tinify.key');
    }

    public function __invoke(ImageProfileRequest $request)
    {

        $request->validated();

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


        try {

            \Tinify\setKey($this->key_tinify);

            $source = \Tinify\fromFile($photo);

            $resized = $source->resize(
                [
                    "method" => "cover",
                    "width" => 300,
                    "height" => 300
                ]

            );

            $resized->toFile(public_path('photos') . '/' . $photoName);

        } catch (\Throwable $e) {

            $photo->move(public_path('photos'), $photoName);
            Log::error('Tinify error: ' . $e->getMessage());

        }




        $photoPath = url('photos/' . $photoName);

        $user->photo = $photoPath;

        $user->save();

        Cache::forget('user_' . $user->id);

        return response()->json(['message' => 'Image uploaded successfully', 'photo' => $photoPath]);
    }
}
