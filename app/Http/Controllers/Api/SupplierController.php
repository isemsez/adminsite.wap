<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelCommon;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class SupplierController extends Controller
{
    private array $validation_rules;


    public function __construct()
    {
        $tmp = Supplier::validation_rules;
        $tmp += [ 'photo' =>
            [
                function ($attribute, $value, $fail) {
                    if ( $value ) {
                        $photo = Image::make( $value );
                        $photo_mime = explode( '/', $photo->mime() );
                        if ( $photo_mime[0] != 'image'
                            or !in_array( $photo_mime[1],
                                [ 'jpg', 'jpeg', 'png', 'bmp', 'gif' ] ) ) {
                            $fail( 'Отправленный вами файл должен быть изображением (jpg,png,gif,bmp).' );
                        }
                        // photo comes as string - "reader.readAsDataURL"
                        if ( strlen( $value ) > 1400000 ) {
                            $fail( 'Файл больше 1Мб.' );
                        }
                    }
                    return true;
                }
            ]
        ];
        $this->validation_rules = $tmp;
    }

    /** Store incoming "create new supplier" form.
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        $validation = ModelCommon::validate_form_data( $this->validation_rules );
        if ( !empty( $validation['failed'] ) ) {
            return $validation['validation_failed_jsonresponse'];
        }

        $supplier = new Supplier();
        $supplier->model_load_and_save();

        return response()->json( [ 'message' => 'Данные успешно сохранены.' ], 201 );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id): JsonResponse
    {
        $validation = ModelCommon::validate_form_data( $this->validation_rules );
        if ( !empty($validation['failed']) ) {
            return $validation['validation_failed_jsonresponse'];
        }

        $supplier = Supplier::query()->find( $id );
        $supplier->model_fill_data_and_save();

        return response()->json( [ 'message' => 'Данные успешно обновлены.' ], 202 );
    }


    /**
     * List all models of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $suppliers = Supplier::all();
        return response()->json( [ 'message' => 'Список поставщиков', 'data' => $suppliers ] );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $supplier = Supplier::query()->find( $id );
        if ( !$supplier ) {
            return response()->json( [
                'message' => 'Нет такого поставщика',
                'error' => "Нет поставщика с id $id.",
            ], 404 );
        }
        return response()->json( [ 'message' => 'Успешно!', 'data' => $supplier ] );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(int $id): JsonResponse
    {
        Supplier::query()->findOrFail( $id )->delete();
        return response()->json( [ 'message' => 'Поставщик успешно удален.' ] );
    }

}
