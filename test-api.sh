#!/bin/bash

API_URL="http://localhost:8000"

format_json() {
    if command -v jq &> /dev/null; then
        jq '.'
    elif command -v python3 &> /dev/null; then
        python3 -m json.tool
    else
        cat
    fi
}

echo "======================================"
echo "Probando Items API"
echo "======================================"
echo ""

# 1. Crear un item
echo "1. Creando item (POST /items)..."
curl -s -X POST $API_URL/items \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Dell XPS 15",
    "description": "Laptop de alta gama con procesador Intel i7",
    "price": 1299.99
  }' | format_json
echo ""
echo ""

# 2. Crear otro item
echo "2. Creando segundo item (POST /items)..."
curl -s -X POST $API_URL/items \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Mouse Logitech MX Master 3",
    "description": "Mouse ergonómico para productividad",
    "price": 99.99
  }' | format_json
echo ""
echo ""

# 3. Listar todos los items
echo "3. Listando todos los items (GET /items)..."
curl -s $API_URL/items | format_json
echo ""
echo ""

# 4. Obtener item por ID
echo "4. Obteniendo item con ID 1 (GET /items/1)..."
curl -s $API_URL/items/1 | format_json
echo ""
echo ""

# 5. Actualizar item
echo "5. Actualizando item con ID 1 (PUT /items/1)..."
curl -s -X PUT $API_URL/items/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Dell XPS 15 (Actualizada)",
    "price": 1199.99
  }' | format_json
echo ""
echo ""

# 6. Verificar actualización
echo "6. Verificando actualización (GET /items/1)..."
curl -s $API_URL/items/1 | format_json
echo ""
echo ""

# 7. Eliminar item
echo "7. Eliminando item con ID 2 (DELETE /items/2)..."
curl -s -X DELETE $API_URL/items/2 | format_json
echo ""
echo ""

# 8. Verificar eliminación
echo "8. Listando items después de eliminar (GET /items)..."
curl -s $API_URL/items | format_json
echo ""
echo ""

# 9. Probar error 404
echo "9. Probando error 404 (GET /items/999)..."
curl -s $API_URL/items/999 | format_json
echo ""
echo ""

# 10. Probar validaciones
echo "10. Probando validaciones (POST /items sin datos)..."
curl -s -X POST $API_URL/items \
  -H "Content-Type: application/json" \
  -d '{}' | format_json
echo ""
echo ""

echo "======================================"
echo "Pruebas completadas"
echo "======================================"
