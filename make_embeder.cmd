@echo off
del /q /f "emebeder2.exe" 2>nul
php.exe embeder2.php new embeder2
php.exe embeder2.php main embeder2 embeder2.php
php.exe embeder2.php add embeder2 out/console.exe out/console.exe

echo Done