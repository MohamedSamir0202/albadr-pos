<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Upload a new file and attach it to the model.
     */
    public static function upload(Request $request, string $inputName, Model $model,
     string $usage, string $folder): void
    {
        if (!$request->hasFile($inputName)) {
            return;
        }

        $file = $request->file($inputName);
        $fileName = time() . '_' . $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $fileName, 'public');

        // Delete existing file if found
        if ($model->image) {
            Storage::disk('public')->delete($model->image->path);
            $model->image()->delete();
        }

        $model->image()->create([
            'path' => $path,
            'ext' => $ext,
            'usage' => $usage,
        ]);
    }

    /**
     * Update existing file (delete old and upload new one)
     */
    public static function update(Request $request, string $inputName,
     Model $model, string $usage, string $folder): void
    {
        if (!$request->hasFile($inputName)) {
            return;
        }

        // Delete old file if exists
        if ($model->image) {
            Storage::disk('public')->delete($model->image->path);
            $model->image()->delete();
        }

        self::upload($request, $inputName, $model, $usage, $folder);
    }

    /**
     * Delete model file and its record
     */
    public static function delete(Model $model): void
    {
        if ($model->image) {
            Storage::disk('public')->delete($model->image->path);
            $model->image()->delete();
        }
    }
}
