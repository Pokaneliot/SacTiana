# MonLogiciel - Startup Script for PowerShell

Write-Host "======================================" -ForegroundColor Cyan
Write-Host "  MonLogiciel - Startup Script" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

# Check if PHP is available
$phpPath = "$PSScriptRoot\runtime\php\php.exe"
if (Test-Path $phpPath) {
    Write-Host "[OK] PHP found in runtime folder" -ForegroundColor Green
} else {
    Write-Host "[WARNING] PHP not found in runtime/php/" -ForegroundColor Yellow
    Write-Host "Please download PHP from https://windows.php.net/download/" -ForegroundColor Yellow
    Write-Host "and extract it to runtime/php/ folder" -ForegroundColor Yellow
    $phpPath = "php"
}

Write-Host ""
Write-Host "Starting Symfony Backend..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$PSScriptRoot\backend'; & '$phpPath' -S localhost:8000 -t public"

Write-Host ""
Write-Host "Waiting for backend to start..." -ForegroundColor Yellow
Start-Sleep -Seconds 3

Write-Host ""
Write-Host "Starting Tauri Application..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$PSScriptRoot\app'; npm run tauri:dev"

Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "  Both servers are starting..." -ForegroundColor Cyan
Write-Host "  Backend: http://localhost:8000" -ForegroundColor Green
Write-Host "  Frontend: Tauri window will open" -ForegroundColor Green
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

Read-Host "Press Enter to continue..."
