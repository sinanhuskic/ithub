@echo off
echo Starting IT Hub server on http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
php -S localhost:8000 -t .
pause
