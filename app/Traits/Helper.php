<?php

namespace App\Traits;

use App\Models\Files;
use Illuminate\Http\UploadedFile;

trait Helper
{

    public function autoFill($request)
    {
        $this->fill(array_filter($request->only($this->getFillable()), function ($key) use ($request) {
            return in_array($key, array_keys($request->all())) || @$this->getCasts()[$key] == 'boolean';
        }, ARRAY_FILTER_USE_KEY))
            ->save();

        return $this;
    }

    public function upload(UploadedFile $uploader, $path = null)
    {
        $file_path = $uploader->store($path);

        $existing = Files::where('filepath', $file_path)->first();
        if ($existing) {
            return $existing->id;
        }

        try {
            $mime_type = $uploader->getMimeType();
        } catch (\Exception $e) {
            $mime_type = null;
        }
        $ext = $uploader->getClientOriginalExtension();

        $file = new Files();
        $file->fill([
            'filepath'      => $file_path,
            'extension'     => $ext,
            'mime_type'     => $mime_type,
            'size'          => $uploader->getClientSize(),
            'original_name' => $uploader->getClientOriginalName(),
        ])->save();
        if ($file->id) {
            return $file;
        }
        throw new \Exception('Can\'t save file into database file', 1);
    }

    public function success($message, $route = null)
    {
        if (!is_null($route)) {
            return redirect()->route($route)->with('message', ['type' => 'success', 'text' => $message]);
        }

        return redirect()->back()->with('message', ['type' => 'success', 'text' => $message]);
    }

    public function error($message, $route = null)
    {
        if (!is_null($route)) {
            return redirect()->route($route)->with('message', ['type' => 'danger', 'text' => $message]);
        }

        return redirect()->back()->with('message', ['type' => 'danger', 'text' => $message]);
    }
}