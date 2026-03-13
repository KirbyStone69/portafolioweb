@echo off
cd /d "C:\Users\ederv\Documents\pagina web"

echo Agregando cambios...
git add .

echo Creando commit...
git commit -m "Add favicon with muscle emoji to browser tab"

echo Subiendo cambios a GitLab...
git push origin main

echo.
echo ¡Commit completado y subido!
pause
