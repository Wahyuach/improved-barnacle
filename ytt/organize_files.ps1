# Move any generated transcript/metadata/subtitle files into this folder and normalize names
# Usage: run from repository root or anywhere: powershell -File .\ytt\organize_files.ps1

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Definition
Write-Host "Organizing files into: $scriptDir"

# Patterns to move (from repo root or elsewhere)
$patterns = @('*.en.vtt','*.en.srt','metadata.txt','transcript.txt')

foreach ($p in $patterns) {
    $items = Get-ChildItem -Path (Resolve-Path -Path . -ErrorAction SilentlyContinue) -Filter $p -Recurse -File -ErrorAction SilentlyContinue
    foreach ($it in $items) {
        try {
            $dest = Join-Path $scriptDir $it.Name
            if ($it.DirectoryName -ieq $scriptDir) { continue }
            Write-Host "Moving $($it.FullName) -> $dest"
            Move-Item -LiteralPath $it.FullName -Destination $dest -Force
        } catch {
            Write-Warning "Failed to move $($it.FullName): $_"
        }
    }
}

Write-Host "Listing files in $scriptDir:"; Get-ChildItem -Path $scriptDir -File | Format-Table Name,Length -AutoSize
