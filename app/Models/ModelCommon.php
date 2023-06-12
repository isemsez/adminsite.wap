<?php

namespace App\Models;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;

/**
 * Helper class for Models, common methods.
 */
class ModelCommon extends Model
{
    use HasFactory;

    /** Validate incoming form data.
     * @param array $validation_rules
     * @return array<bool|JsonResponse>|void
     */
    public static function validate_form_data(array $validation_rules)
    {
        $validator = Validator::make( request()->all(), $validation_rules );
        if ( $validator->fails() ) {
            return [
                'failed' => true,
                'validation_failed_jsonresponse' => response()->json([
                    'message' => 'Проверьте ваши данные.',
                    'errors' => $validator->errors()
                ])
            ];
        }
    }


    /** Заполнить model данными из запроса и сохранить.
     * @return void
     */
    public function model_load_and_save()
    {
        foreach ( request()->post() as $key=>$value) {

            if ($key == 'photo' && $value) {

                $photo_url_path = Controller::save_photo( $value,
                    strtolower( $this->model_class_name() )
                );
                // replacing photo
                if ( !empty($this->photo) ) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . $this->photo);
                }
                $this->photo = $photo_url_path;

            } else {
                $this[$key] = $value;
            }
        }

        $this->query()->updateOrCreate( ['id' => $this->id], $this->toArray() );
    }


    /** Which Model class_name that initialized "model->find(id)".
     * @return non-empty-string
     */
    public function model_class_name(): string
    {
        return (new ReflectionClass( get_called_class() ))->getShortName();
    }


}
