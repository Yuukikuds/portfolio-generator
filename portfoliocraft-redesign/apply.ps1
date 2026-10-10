# apply.ps1
# Applies the redesign (neon-pink dark theme, new landing page, new navbar and footer)
# to the PortfolioCraft Laravel project. Safe to run more than once.

param([string]$Project = "C:\dev\portfolio-generator")

$ErrorActionPreference = "Stop"
$here = Split-Path -Parent $MyInvocation.MyCommand.Path
$overlay = Join-Path $here "overlay"

function Fail([string]$text) { Write-Host ""; Write-Host "ERROR: $text" -ForegroundColor Red; exit 1 }

function Run([string]$Exe, [string[]]$Arguments) {
  $previous = $ErrorActionPreference
  $ErrorActionPreference = "Continue"
  & $Exe @Arguments | Out-Host
  $code = $LASTEXITCODE
  $ErrorActionPreference = $previous
  return $code
}

Write-Host ""
Write-Host "PortfolioCraft: neon redesign" -ForegroundColor Cyan
Write-Host "Project folder: $Project"

if (-not (Test-Path (Join-Path $Project "artisan"))) {
  Fail "No Laravel project was found in $Project (no artisan file). Run the Laravel conversion (INSTALL.cmd) first."
}
if (-not (Test-Path $overlay)) { Fail "The 'overlay' folder is missing. Extract the whole zip file first." }
if (-not (Get-Command php -ErrorAction SilentlyContinue)) { Fail "PHP was not found. Open a NEW Command Prompt and try again." }
Set-Location $Project

Write-Host ""
Write-Host "[1/3] Saving a Git checkpoint (your undo button)..." -ForegroundColor Cyan
if ((Get-Command git -ErrorAction SilentlyContinue) -and (Test-Path (Join-Path $Project ".git"))) {
  $previous = $ErrorActionPreference
  $ErrorActionPreference = "Continue"
  git add -A | Out-Null
  git commit -m "Checkpoint before redesign" | Out-Null
  $ErrorActionPreference = $previous
  Write-Host "  Done. To undo the redesign later:  git reset --hard HEAD" -ForegroundColor Green
} else {
  Write-Host "  Git not found, skipped." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "[2/3] Copying the redesign files..." -ForegroundColor Cyan
$previous = $ErrorActionPreference
$ErrorActionPreference = "Continue"
& robocopy $overlay $Project /E /NFL /NDL /NJH /NJS /NP | Out-Null
$code = $LASTEXITCODE
$ErrorActionPreference = $previous
$global:LASTEXITCODE = 0
if ($code -ge 8) { Fail "Copying the files failed (robocopy code $code)." }
Write-Host "  Updated: landing page, navbar, footer, layout, and public\css\app.css" -ForegroundColor Green

Write-Host ""
Write-Host "[3/3] Checking the views..." -ForegroundColor Cyan
[void](Run "php" @("artisan", "optimize:clear"))
$viewCode = Run "php" @("artisan", "view:cache")
$problems = @()
if ($viewCode -eq 0) {
  $compiled = Join-Path $Project "storage\framework\views"
  $previous = $ErrorActionPreference
  $ErrorActionPreference = "Continue"
  Get-ChildItem -LiteralPath $compiled -Filter "*.php" -ErrorAction SilentlyContinue | ForEach-Object {
    $result = (& php -l $_.FullName 2>&1 | Out-String).Trim()
    if ($result -notlike "No syntax errors*") { $problems += $result }
  }
  $ErrorActionPreference = $previous
}
[void](Run "php" @("artisan", "view:clear"))

Write-Host ""
if ($viewCode -eq 0 -and $problems.Count -eq 0) {
  Write-Host "ALL DONE. The redesign is applied and every view compiles." -ForegroundColor Green
} else {
  Write-Host "A view has a problem. Read the messages above and send them to me." -ForegroundColor Yellow
  $problems | ForEach-Object { Write-Host $_ -ForegroundColor Yellow }
  Write-Host "To undo everything:  git reset --hard HEAD"
}
Write-Host ""
Write-Host "Next steps:"
Write-Host "  1. Start the site:   php artisan serve     then open http://127.0.0.1:8000"
Write-Host "     (hold Ctrl and press F5 to refresh the styles)"
Write-Host "  2. Deploy:           git add -A"
Write-Host "                       git commit -m ""Neon redesign"""
Write-Host "                       git push"
