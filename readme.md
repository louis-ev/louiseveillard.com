# louiseveillard.com

Source code of my online portfolio — [Kirby 5](https://getkirby.com), CSS/JS with no build step (same stack as `latelier-des-chercheurs.fr`).

## Local development

Requirements: PHP ≥ 8.2 and Composer.

```sh
composer install   # installs Kirby into kirby/ (not versioned)
npm run dev        # http://localhost:8000 — Panel at /panel
npm run check      # renders every page and reports errors (also run before each deploy)
```

- `site/`: templates, snippets, blueprints, plugin `site/plugins/louiseveillard` (kirbytags `image`, `video`, `vimeo`, `link popup:`, `lienProjet`, `jour`, `toutesDescriptions`; a `---` line in a project text starts a new section)
- `assets/css/`: `fonts.css` (GT Cinetype, embedded), `variables.css` (colors, project categories), `base.css` (normalize), `main.css`
- `assets/js/main.js`: plain JS, no dependencies — project list sorted by category from the home intro links, full-screen covers on hover, zoom on project covers, autoplaying videos only while visible. Page changes use native cross-document view transitions.
- `content/`: the contents. **The Panel is disabled online** (`'panel' => false` in `site/config/config.php`, enabled locally via `config.localhost.php`): content is edited locally, and each deploy also uploads `content/`.

## Deploy

Each `v*` tag triggers [.github/workflows/deploy.yml](.github/workflows/deploy.yml): `composer install --no-dev`, page check, then FTP sync of `content/` and of the site (excluding `media/`, accounts, sessions, cache).

It can also be run manually from **Actions → Deploy to FTP → Run workflow** with a `server_dir` option (remote folder to use instead of the `FTP_SERVER_DIR` secret, e.g. a test subdomain).

Each deploy writes `site/config/.deploy` (commit sha): the Kirby page cache, only active online, starts fresh on each deploy.

```sh
npm version patch   # creates the vX.Y.Z tag and pushes it → deploy
```

### GitHub Actions secrets

| Secret           | Required | Description                                          |
| ---------------- | -------- | ---------------------------------------------------- |
| `FTP_SERVER`     | Yes      | FTP host                                             |
| `FTP_USERNAME`   | Yes      | FTP user                                             |
| `FTP_PASSWORD`   | Yes      | FTP password                                         |
| `FTP_PORT`       | No       | Port (default `21`)                                  |
| `FTP_PROTOCOL`   | No       | `ftp` or `ftps` (default `ftp`)                      |
| `FTP_SERVER_DIR` | No       | Remote folder, with a trailing slash (e.g. `www/`)   |

## Switching from Kirby 2 (once)

1. Check that the hosting runs **PHP ≥ 8.2** and back up the server (especially `content/`).
2. License: the Kirby 2 key isn't valid for Kirby 5, a Kirby 5 license has to be activated from the Panel.
3. Ideally, deploy first to a test folder / subdomain (`server_dir`).
4. The content in this repo is already migrated (`scripts/migrate-content.php`: numbered folders `1-home` → `1_home`, files starting with `_` renamed, `Imagecover` field as a YAML list). If the server has content that isn't in the repo, fetch it and run `php scripts/migrate-content.php path/to/content` before deploying.
5. Deploy (`npm version major`), then delete the Kirby 2 leftovers on the server: `panel/`, `thumbs/`, `assets/production/`, `assets/less/`, `assets/fonts/`, `site/fields/`, `site/tags/`, `site/plugins/field-selector/`, `site/plugins/horizontalrulestosection/`, `site/plugins/kirby-tracking/`, `site/plugins/outils/`, `site/plugins/videoext/`, the `site/blueprints/*.php|yml|yaml` files at the root of the blueprints folder, the old `content/1-home`, `content/2-infos`, `content/projets/<n>-…` folders and `content/kirby-tracking/`.
6. Create the admin account at `/panel` locally (Kirby 2 accounts aren't compatible).
