# Run a command inside the created .venv (PowerShell)
# Usage: .\run_in_venv.ps1 -Url 'https://youtu.be/VIDEOID'
param(
    [Parameter(Mandatory=$true)]
    [string]$Url
)

# Ensure venv in script directory
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$venvPath = Join-Path $scriptDir '.venv'

if (-not (Test-Path $venvPath)) {
    Write-Error ".venv not found in $scriptDir. Run .\create_python_env.ps1 first to create the virtual environment."
    exit 1
}

Write-Host "Activating $venvPath and running fetch_transcript.ps1 for URL: $Url"

# Use a single inline PowerShell command to activate and run the fetch script from the script directory
$activate = ". '$venvPath\Scripts\Activate.ps1';"
$fetch = "& '$scriptDir\fetch_transcript.ps1' -Url '$Url'"

powershell -NoProfile -ExecutionPolicy Bypass -Command "$activate $fetch"
