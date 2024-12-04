<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Storage;

trait FileUploadTrait
{
    public function uploadDocumentFile($uploadedFile, $folder, $allowedTypes, $maxSize, $othername = "",$filenametogive="")
    {
        try {
            $this->validateUpload($uploadedFile, $allowedTypes);
            $file = $uploadedFile;
            $destinationPath = public_path() .'/'. $folder;
            $file_name = ($filenametogive!="")?$filenametogive.'.'.$file->getClientOriginalExtension(): time() . sanitize_file_name($othername) .'.'.$file->getClientOriginalExtension();
            $profile_image = "";
            if ($file->move($destinationPath, $file_name)) {
                $profile_image = $file_name;
            }
            return ['status' => 1, 'path' => $profile_image];

        } catch (Exception $exception) {

            return ['status' => 0, 'message' => $exception->getMessage()];

        }

    }
    public function uploadFile($uploadedFile, $folder, $allowedTypes, $maxSize, $othername = "")
    {
        try {
            $this->validateUpload($uploadedFile, $allowedTypes);
            $file = $uploadedFile;
            $destinationPath = public_path() . '/image/';
            $file_name = time() . sanitize_file_name($othername) . '.'.$file->getClientOriginalExtension();
            $profile_image = "";
            if ($file->move($destinationPath, $file_name)) {
                $profile_image = $file_name;
            }
            return ['status' => 1, 'path' => $profile_image];

        } catch (Exception $exception) {

            return ['status' => 0, 'message' => $exception->getMessage()];

        }

    }
    public function uploadFiles($files, $folder, $allowedtypes, $maxsize)
    {
        try {
            $paths = [];
            foreach ($files as $file) {
                $this->validateUpload($file, $allowedtypes);
                $newFileName = time() . '_' . $file->getClientOriginalName();
                $newFileName = generate_imageName($file->getClientOriginalName(), "product_image") . "." . $file->getClientOriginalExtension();
                $path = Storage::disk('public')->putFileAs($folder, $file, $newFileName);
                array_push($paths, $path);
            }
            return ['status' => 1, 'paths' => $paths];
        } catch (Exception $exception) {

            return ['status' => 0, 'paths' => $paths, 'error' => $exception->getMessage()];

        }
    }

    protected function validateUpload(UploadedFile $file, $allowedtypes)
    {
        // if (!in_array($file->getClientOriginalExtension(), $allowedtypes)) {
        //     throw new \Exception('Invalid file type.');
        // }
        // if ($file->getSize() > $maxSizeInBytes) {
        //     throw new \Exception('File size exceeds the limit.');
        // }
    }
}
