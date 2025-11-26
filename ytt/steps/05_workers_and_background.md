05 — Workers and background jobs
================================

Goal
- Decide how to run queue workers / background jobs for the Laravel app on Azure.

Options

- Option A: Use a separate Web App (or App Service) as a worker
  - Deploy a second App Service that runs `php artisan queue:work` (or a supervisor script).
  - Use the same artifact/deploy workflow but change the `Startup Command` or `startup.sh` to run the worker process.

- Option B: Use Azure Container Instances or Azure App Service for Containers
  - Containerize the worker and run it as a container instance or Container App.

- Option C: Use Azure Functions for small tasks
  - Offload discrete jobs (notifications, small processing) to serverless Functions.

Simple worker startup script (example)

```bash
# example: start a forever worker using a simple loop
php artisan queue:work --sleep=3 --tries=3 --timeout=90
```

Where to edit in repo
- Add a worker-specific `startup-worker.sh` that runs the worker loop.
- Update CI workflow to optionally publish to the worker App Service using a different publish profile secret (e.g. `AZURE_WORKER_PUBLISH_PROFILE`).

Notes
- Monitor workers using App Service metrics and logs. Restart on failures using App Service health checks or implement a process manager in the container.
