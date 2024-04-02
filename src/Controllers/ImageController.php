<?php

namespace Bythepixel\NovaTinymceField\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImageController extends Controller
{
    public function upload(NovaRequest $request)
    {
        $field = $request->newResource()
            ->availableFields($request)
            ->findFieldByAttribute($request->field, function () {
                abort(404);
            });

        $postedImage = $request->file('image');

        $savedImage = Storage::disk($field->disk)->putFile($field->storagePath, $postedImage);

        $url = Storage::disk($field->disk)->url($savedImage);

        return response()->json(['url' => $url]);
    }
}
