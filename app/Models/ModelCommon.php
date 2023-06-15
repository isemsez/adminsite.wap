<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;

/**
 * Helper class for Models, common methods.
 */
class ModelCommon extends Model
{
    use HasFactory;

    /**
     * Validate incoming form data.
     *
     * @param array $validation_rules
     * @return array|void
     */
    public static function validate_form_data(array $validation_rules)
    {
        $validator = Validator::make( request()->all(), $validation_rules );
        if ( $validator->fails() ) {
            return [
                'failed'                         => true,
                'validation_failed_jsonresponse' => response()->json( [
                    'message' => 'Проверьте ваши данные.',
                    'errors'  => $validator->errors()
                ], 422 )
            ];
        }
    }


    /**
     * Заполнить model данными из запроса и сохранить.
     *
     * @return void
     */
    public function model_load_and_save(): void
    {
        $incoming_data = request()->post();

        if ( array_key_exists( 'photo', $incoming_data ) ) {

            if ( !empty( $incoming_data['photo'] ) ) {

                $photo_url_path = Controller::save_photo( $incoming_data['photo'],
                    strtolower( $this->model_class_name() )
                );

                // replacing photo
                if ( $this->photo ) {
                    unlink( $_SERVER['DOCUMENT_ROOT'] . $this->photo );
                }

                $incoming_data['photo'] = $photo_url_path;

            } else {
                unset( $incoming_data['photo'] );  // not to erase existing photo

            }
        }

        $this->query()->updateOrCreate( [ 'id' => $this->id ], $incoming_data );
    }


    /** Name of Model that initialized "query()->find(id)" action.
     *
     * @return non-empty-string
     */
    public function model_class_name(): string
    {
        return ( new ReflectionClass( get_called_class() ) )->getShortName();
    }

}
