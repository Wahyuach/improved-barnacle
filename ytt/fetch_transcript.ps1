# Fetch transcript and metadata for a public YouTube video using yt-dlp
# Usage: Open PowerShell in this folder and run: .\fetch_transcript.ps1 -Url 'https://youtu.be/-PGhVFsOnGA'
param(
    [Parameter(Mandatory=$true)]
    [string]$Url
)

# Check yt-dlp availability
if (-not (Get-Command yt-dlp -ErrorAction SilentlyContinue)) {
    Write-Host "yt-dlp not found. Install via `pip install yt-dlp` or Scoop/Chocolatey." -ForegroundColor Yellow
    exit 1
}

# Print metadata
Write-Host "Fetching metadata..."
yt-dlp --no-warnings --skip-download --print "%(title)s%n%(uploader)s%n%(upload_date)s%n%(description)s" $Url > metadata.txt
Write-Host "Saved metadata -> metadata.txt"

# Try to download auto-generated English subtitles (or uploaded subs)
Write-Host "Attempting to download English subtitles (auto if needed)..."
yt-dlp --write-auto-sub --sub-lang en --convert-subs srt --skip-download $Url

# Find downloaded subtitles: prefer .srt, fall back to .vtt
$srt = Get-ChildItem -Filter "*.en.srt" -File -ErrorAction SilentlyContinue | Select-Object -First 1
if ($srt) {
    Write-Host "Subtitle saved: $($srt.Name)"
    # Convert SRT to plain text transcript (simple)
    $plain = "transcript.txt"
    Get-Content $srt.FullName | ForEach-Object {
        # Remove SRT index and timestamps
        if ($_ -match "^[0-9]+$") { return }
        if ($_ -match "^[0-9]{2}:[0-9]{2}:") { return }
        $_
    } | Where-Object { $_ -ne "" } | Set-Content $plain
    Write-Host "Plain transcript saved -> $plain"
} else {
    # Try VTT
    $vtt = Get-ChildItem -Filter "*.en.vtt" -File -ErrorAction SilentlyContinue | Select-Object -First 1
    if ($vtt) {
        Write-Host "Subtitle saved (VTT): $($vtt.Name)"
        $plain = "transcript.txt"
        # Simple VTT to plain text: skip WEBVTT header and timestamps
        Get-Content $vtt.FullName | ForEach-Object {
            if ($_ -match "^WEBVTT") { return }
            if ($_ -match "^NOTE") { return }
            if ($_ -match "^\s*$") { return }
            if ($_ -match "^([0-9]{2}:)?[0-9]{2}:[0-9]{2}\.[0-9]{3} -->") { return }
            # remove cue settings like "align:"
            $_ -replace "^[0-9]+$", "" -replace "^.*-->.*$", ""
        } | Where-Object { $_ -ne "" } | Set-Content $plain
        Write-Host "Plain transcript saved -> $plain"
    } else {
        Write-Host "No .en.srt or .en.vtt produced. Check if captions exist or try other languages." -ForegroundColor Yellow
    }
}

Write-Host "Done. Paste the transcript (transcript.txt) or metadata.txt content here for analysis." -ForegroundColor Green
