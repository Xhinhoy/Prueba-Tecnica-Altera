param(
    [string]$API_URL = "http://localhost:8000"
)

function Call-Api {
    param(
        [Parameter(Mandatory = $true)]
        [ValidateSet("GET","POST","PUT","DELETE")]
        [string]$Method,

        [Parameter(Mandatory = $true)]
        [string]$Path,

        [Parameter(Mandatory = $false)]
        [string]$BodyJson
    )

    $url = "$API_URL$Path"

    try {
        if ($BodyJson) {
            $resp = Invoke-RestMethod -Method $Method -Uri $url -ContentType "application/json" -Body $BodyJson
        } else {
            $resp = Invoke-RestMethod -Method $Method -Uri $url
        }

        $resp | ConvertTo-Json -Depth 5
    }
    catch {
        Write-Host "ERROR $Method $url"
        Write-Host $_.Exception.Message

        if ($_.Exception.Response -and $_.Exception.Response.GetResponseStream) {
            $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
            $raw = $reader.ReadToEnd()
            $reader.Close()

            if ($raw) {
                Write-Host "Respuesta cruda del servidor:"
                $raw
            }
        }
    }
}

Write-Output "======================================"
Write-Output "Probando Items API"
Write-Output "======================================"
Write-Output ""

# 1. Crear un item
Write-Output "1. Creando item (POST /items)..."
Call-Api -Method POST -Path "/items" -BodyJson '{
    "name": "Laptop Dell XPS 15",
    "description": "Laptop de alta gama con procesador Intel i7",
    "price": 1299.99
}'
Write-Output ""

# 2. Crear otro item
Write-Output "2. Creando segundo item (POST /items)..."
Call-Api -Method POST -Path "/items" -BodyJson '{
    "name": "Mouse Logitech MX Master 3",
    "description": "Mouse ergonómico para productividad",
    "price": 99.99
}'
Write-Output ""

# 3. Listar todos los items
Write-Output "3. Listando todos los items (GET /items)..."
Call-Api -Method GET -Path "/items"
Write-Output ""

# 4. Obtener item por ID
Write-Output "4. Obteniendo item con ID 1 (GET /items/1)..."
Call-Api -Method GET -Path "/items/1"
Write-Output ""

# 5. Actualizar item
Write-Output "5. Actualizando item con ID 1 (PUT /items/1)..."
Call-Api -Method PUT -Path "/items/1" -BodyJson '{
    "name": "Laptop Dell XPS 15 (Actualizada)",
    "price": 1199.99
}'
Write-Output ""

# 6. Verificar actualización
Write-Output "6. Verificando actualización (GET /items/1)..."
Call-Api -Method GET -Path "/items/1"
Write-Output ""

# 7. Eliminar item
Write-Output "7. Eliminando item con ID 2 (DELETE /items/2)..."
Call-Api -Method DELETE -Path "/items/2"
Write-Output ""

# 8. Verificar eliminación
Write-Output "8. Listando items después de eliminar (GET /items)..."
Call-Api -Method GET -Path "/items"
Write-Output ""

# 9. Probar error 404
Write-Output "9. Probando error 404 (GET /items/999)..."
Call-Api -Method GET -Path "/items/999"
Write-Output ""

# 10. Probar validaciones
Write-Output "10. Probando validaciones (POST /items sin datos)..."
Call-Api -Method POST -Path "/items" -BodyJson '{}'
Write-Output ""

Write-Output "======================================"
Write-Output "Pruebas completadas"
Write-Output "======================================"
