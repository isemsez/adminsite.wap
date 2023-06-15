<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Intervention\Image\Exception\NotWritableException;
use Intervention\Image\ImageManagerStatic;
use Symfony\Component\Console\Output\ConsoleOutput;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Save photo from request and return its url path.
     *
     * @param string $photo $request->photo data (url encoded)
     * @param string $which_entity Which Model's data. Each entity would have its own folder to be stored
     * @return string Url path
     */
    public static function save_photo($photo, string $which_entity): string
    {
        $which_entity = strtolower( $which_entity );

        $resized_image = ImageManagerStatic::make( $photo )->resize( null, 200,
            function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            }
        );

        $url_dir_path = "/backend/$which_entity/";
        $server_dir_path = public_path() . $url_dir_path;

        Controller::create_dir_if_necessary( $server_dir_path );

        $filename = 'img_' . time() . '.' . explode( '/', $resized_image->mime )[1];
        $resized_image->save( $server_dir_path . $filename );

        return $url_dir_path . $filename;
    }


    /**
     * Если указанной папки не существует, создать ее.
     *
     * @param non-empty-string $server_dir_path
     * @return void
     */
    public static function create_dir_if_necessary(string $server_dir_path)
    {
        if ( !is_dir( $server_dir_path ) ) {
            if ( !mkdir( $server_dir_path, 0770, true ) ) {
                throw new NotWritableException(
                    "Нет нужной папки и не получилось её создать."
                );
            }
        }
    }


    /**
     * Print debug message to terminal.
     * @param $msg
     * @return void
     */
    public static function print_to_console($msg)
    {
        ( new ConsoleOutput() )->writeln( print_r( $msg, true ) );
    }
}
