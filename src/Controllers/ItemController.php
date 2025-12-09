<?php

namespace Nicolas\ItemsApi\Controllers;

use Nicolas\ItemsApi\Models\Item;

class ItemController
{
    /**
     * GET /items - Listar todos los items
     */
    public function index()
    {
        try {
            $items = Item::all();
            return $this->jsonResponse($items, 200);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'error' => 'Error al obtener los items',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /items/{id} - Obtener un item por ID
     */
    public function show($id)
    {
        try {
            $item = Item::find($id);

            if (!$item) {
                return $this->jsonResponse([
                    'error' => 'Item no encontrado'
                ], 404);
            }

            return $this->jsonResponse($item, 200);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'error' => 'Error al obtener el item',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /items - Crear un nuevo item
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            $errors = Item::validate($data);
            if (!empty($errors)) {
                return $this->jsonResponse([
                    'error' => 'Errores de validación',
                    'errors' => $errors
                ], 422);
            }

            // Crear item
            $item = Item::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price']
            ]);

            return $this->jsonResponse([
                'message' => 'Item creado exitosamente',
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'error' => 'Error al crear el item',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * PUT /items/{id} - Actualizar un item
     */
    public function update($id)
    {
        try {
            $item = Item::find($id);

            if (!$item) {
                return $this->jsonResponse([
                    'error' => 'Item no encontrado'
                ], 404);
            }

            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            $errors = Item::validate($data, true);
            if (!empty($errors)) {
                return $this->jsonResponse([
                    'error' => 'Errores de validación',
                    'errors' => $errors
                ], 422);
            }

            // Actualizar solo los campos enviados
            if (isset($data['name'])) {
                $item->name = $data['name'];
            }
            if (isset($data['description'])) {
                $item->description = $data['description'];
            }
            if (isset($data['price'])) {
                $item->price = $data['price'];
            }

            $item->save();

            return $this->jsonResponse([
                'message' => 'Item actualizado exitosamente',
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'error' => 'Error al actualizar el item',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /items/{id} - Eliminar un item
     */
    public function destroy($id)
    {
        try {
            $item = Item::find($id);

            if (!$item) {
                return $this->jsonResponse([
                    'error' => 'Item no encontrado'
                ], 404);
            }

            $item->delete();

            return $this->jsonResponse([
                'message' => 'Item eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return $this->jsonResponse([
                'error' => 'Error al eliminar el item',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar respuesta JSON
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
