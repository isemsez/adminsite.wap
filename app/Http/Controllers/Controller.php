<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManagerStatic;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Если указанной папки не существует, создать ее.
     * @param non-empty-string $server_local_path
     * @return void
     */
    public static function createDir_ifNecessary(string $server_local_path)
    {
        if (!is_dir($server_local_path)) {
            if (! mkdir($server_local_path, 0770, true)) {
                throw new NotWritableException(
                    "Нет нужной папки и не получилось её создать."
                );
            }
        }
    }

    /**
     * Save photo from request's form data and return its url path.
     * @param $photo $request['photo'] data
     * @param string $whose_data Which model's data
     * @return string Url path
     */
    public static function save_photo($photo, string $whose_data): string
    {
        $resized_image = ImageManagerStatic::make($photo)->resize(null, 200,
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        );

        $whose_data = mb_strtolower($whose_data);
        $relative_dir_path = "/backend/$whose_data/";
        $server_local_path = $_SERVER['DOCUMENT_ROOT'].$relative_dir_path;
        Controller::createDir_ifNecessary($server_local_path);

        $filename = 'img_'.time().'.'.explode('/', $resized_image->mime)[1];
        $resized_image->save($server_local_path.$filename);

        return $relative_dir_path.$filename;
    }

}
