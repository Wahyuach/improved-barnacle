This folder holds helper files for analyzing YouTube tutorial videos.

How to get the transcript (recommended)
- Install `yt-dlp` (Python/pip, Scoop, or Chocolatey).
- Run the provided `fetch_transcript.ps1` script to download auto-generated or uploaded subtitles and save them in this folder.

If you paste the transcript into `transcript.txt` here, I will:
1) Summarize the tutorial into actionable steps.
2) Map steps to files and changes in the repository.
3) Create an implementation branch + commits and run tests (with your confirmation to push).

Files:
- `fetch_transcript.ps1` - PowerShell helper script to fetch video metadata and subtitles with `yt-dlp`.
- `transcript.txt` - Put the transcript here (or paste into the chat) and I will analyze it.
- `analysis.md` - Where I will write my analysis once transcript is available.

Notes:
- I cannot bypass authentication on private or age-restricted videos. Use these steps only for public videos or videos you have permission to use.
- If you'd like me to attempt fetching the transcript directly, paste the transcript here or run `fetch_transcript.ps1` and paste the output file.