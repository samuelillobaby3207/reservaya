#!/bin/bash

# Configuración
FECHA=$(date +"%Y-%m-%d_%H-%M")
ARCHIVO="copia_tablas.sql"

echo "Iniciando copia de seguridad de la base de datos..."

# Ejecutar el dump desde el contenedor de Docker (usando sudo para permisos)
sudo docker exec mysql_db mysqldump -u root -proot reservaya > $ARCHIVO

if [ $? -eq 0 ]; then
    echo "¡Éxito! Copia de seguridad guardada en $ARCHIVO"
    echo "Fecha de la copia: $FECHA"
else
    echo "Hubo un error al realizar la copia de seguridad."
fi
