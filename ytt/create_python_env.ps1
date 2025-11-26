# Create a Python virtual environment in `ytt` and install requirements
# Usage: open PowerShell in this folder and run: .\create_python_env.ps1

try {
    Write-Host "Creating Python virtual environment in ytt/.venv (script dir)..."
    python -V > $null 2>&1
} catch {
    Write-Error "Python is not available in PATH. Install Python 3.8+ and ensure 'python' is on PATH."
    exit 1
}

# Ensure venv is created inside the script directory (ytt), not the caller's CWD
$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
$venvPath = Join-Path $scriptDir '.venv'

if (Test-Path $venvPath) {
    Write-Host ".venv already exists in $scriptDir. Skipping creation." -ForegroundColor Yellow
} else {
    python -m venv "$venvPath"
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Failed to create virtual environment at $venvPath."
        exit 1
    }
}

# Activate and upgrade pip
Write-Host "Activating virtual environment and upgrading pip..."
& "${venvPath}\Scripts\Activate.ps1"
if ($?) {
    & "${venvPath}\Scripts\python.exe" -m pip install --upgrade pip
} else {
    Write-Warning "Could not activate virtualenv (you may need to run PowerShell as Administrator or adjust ExecutionPolicy)."
}

# Install requirements
if (Test-Path (Join-Path $scriptDir 'requirements.txt')) {
    Write-Host "Installing requirements from requirements.txt..."
    & "${venvPath}\Scripts\python.exe" -m pip install -r (Join-Path $scriptDir 'requirements.txt')
} else {
    Write-Warning "requirements.txt not found in $scriptDir."
}

Write-Host "Virtual environment ready in: $venvPath"
Write-Host "To use it in this session run: . '$venvPath\Scripts\Activate.ps1'" -ForegroundColor Green
