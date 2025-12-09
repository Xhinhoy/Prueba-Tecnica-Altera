<?php

namespace Nicolas\ItemsApi\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'name',
        'description',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public static function validate(array $data, $isUpdate = false)
    {
        $errors = [];

        // Validar name
        if (!$isUpdate || isset($data['name'])) {
            if (empty($data['name'])) {
                $errors['name'] = 'El campo name es requerido';
            } elseif (!is_string($data['name'])) {
                $errors['name'] = 'El campo name debe ser una cadena de texto';
            }
        }

        // Validar price
        if (!$isUpdate || isset($data['price'])) {
            if (!isset($data['price']) || $data['price'] === '') {
                $errors['price'] = 'El campo price es requerido';
            } elseif (!is_numeric($data['price'])) {
                $errors['price'] = 'El campo price debe ser un número';
            } elseif ($data['price'] < 0) {
                $errors['price'] = 'El campo price debe ser mayor o igual a 0';
            }
        }

        // Validar description (opcional)
        if (isset($data['description']) && !is_string($data['description'])) {
            $errors['description'] = 'El campo description debe ser una cadena de texto';
        }

        return $errors;
    }
}
