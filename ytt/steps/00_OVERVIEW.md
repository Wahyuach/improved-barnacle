Overview
========

This folder contains step-by-step instructions extracted from the video "Laravel on Azure Web App" and mapped to this repository. Use these files in order.

- `01_create_resources.md` — create Azure resource group, MySQL, and Web App.
- `02_configure_startup.md` — review and test `startup.sh`, App Service settings, and runtime setup.
- `03_setup_ci.md` — set up GitHub Actions to build, migrate (optional), and deploy via publish profile.
- `04_secrets.md` — obtain and add required secrets (publish profile, DB credentials) to GitHub.
- `05_workers_and_background.md` — options for queue/workers in Azure.
- `06_verify_and_monitor.md` — post-deploy verification and logging checks.

Follow these files for a repeatable deployment flow. The instructions include example `az` commands and the repository locations to edit.
