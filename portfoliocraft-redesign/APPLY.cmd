@echo off
rem PortfolioCraft: apply the neon redesign to the Laravel project.
rem Usage:  APPLY.cmd                     (uses C:\dev\portfolio-generator)
rem         APPLY.cmd -Project "D:\my\folder"
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0apply.ps1" %*
echo.
pause
