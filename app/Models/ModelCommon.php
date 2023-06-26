<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
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
     * @param array $validation_messages
     * @return array|void
     */
    public static function validate_form_data(array $validation_rules, array $validation_messages = [])
    {
        $validator = Validator::make( request()->all(), $validation_rules, $validation_messages );
        if ( $validator->fails() ) {
            return [
                'failed' => true,
                'validation_failed_json_response' => response()->json( [
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

                $new_photo_url_path = Controller::save_photo( $incoming_data['photo'],
                    strtolower( $this->model_class_name() )
                );

                // replacing photo
                if ( $this->photo ) { // previous photo in db
                    unlink( $_SERVER['DOCUMENT_ROOT'] . $this->photo );
                }
                $incoming_data['photo'] = $new_photo_url_path;

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


    /**
     * Validate incoming photo. Custom rule, closure.
     *
     * @return mixed
     */
    public static function photo_validation_rule(): array
    {
        return [
            'photo' => function ($attribute, $value, $fail) {
                if ($value) {

                    $photo_mime = explode('/', Image::make($value)->mime());
                    if ($photo_mime[0] != 'image'
                        or !in_array($photo_mime[1],
                            ['jpg', 'jpeg', 'png', 'bmp', 'gif'])) {

                        $fail('Отправленный вами файл должен быть изображением (jpg,png,bmp,gif).');
                    }
                    // photo comes as string - "reader.readAsDataURL"
                    if (strlen($value) > 1400000) {
                        $fail('Файл больше 1Мб.');
                    }
                }
                return true;
            }
        ];
    }


}
