@echo off
echo ======================================
echo   MonLogiciel - Startup Script
echo ======================================
echo.

REM Check if PHP is available
if exist "runtime\php\php.exe" (
    echo [OK] PHP found in runtime folder
    set PHP_PATH=%~dp0runtime\php\php.exe
) else (
    echo [WARNING] PHP not found in runtime/php/
    echo Please download PHP from https://windows.php.net/download/
    echo and extract it to runtime/php/ folder
    set PHP_PATH=php
)

echo.
echo Starting Symfony Backend...
cd backend
start "Symfony Backend" cmd /k "%PHP_PATH%" -S localhost:8000 -t public

echo.
echo Waiting for backend to start...
timeout /t 3 /nobreak > nul

echo.
echo Starting Tauri Application...
cd ..\app
start "Tauri App" cmd /k npm run tauri:dev

echo.
echo ======================================
echo   Both servers are starting...
echo   Backend: http://localhost:8000
echo   Frontend: Tauri window will open
echo ======================================
echo.

pause
