# Deploy this hardening package to GitHub

This package contains only files that should be overlaid onto the repository. It does not contain credentials.

From a clean clone of `mauirixxx/gwtt`:

```bash
git checkout master
git pull --ff-only
git checkout -b hardening/full-repair

# Extract/copy the contents of this package into the repository root, preserving paths.
git status
git diff

# PHP syntax check (recommended)
for f in gw-*.php; do php -l "$f" || exit 1; done

git add README.md DEPLOY.md gw-*.php db/master-schema.sql \
  db/gw-treasure-history.sql db/gw-treasure-users.sql \
  db/migrate-2026-hardening.sql
git commit -m "Harden application and reconcile database schema"
git push -u origin hardening/full-repair
```

Then open a pull request from `hardening/full-repair` into `master`.

## Database warning
Do **not** run `db/master-schema.sql` on the existing production database; it is destructive.

Before upgrading an existing DB:
1. Take a tested backup.
2. Inspect legacy `history` data for non-numeric values in `material`, `itemtype`,
   `itemattribute`, `itemrarity`, and `runetype`.
3. Review `db/migrate-2026-hardening.sql`.
4. Test the migration on a copy first.
5. Deploy the PHP changes and DB migration together because the new code expects `history.drop_type`.

The migration assumes legacy `itemtype=16` means Rune and `itemtype=17` means Nothing, matching the pre-hardening PHP application.
