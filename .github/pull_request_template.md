## Description

<!-- A single prose paragraph explaining what this PR does and why. On squash merge, this becomes the commit body — keep it concise and focused. -->

## Proposed Changes

<!-- An optional breakdown of what changed and why. Use this section for detail that helps reviewers but does not need to end up in the commit history. The GitHub summary generator output fits well here. -->

## Type of Change

- [ ] `feat` — new feature (minor version bump)
- [ ] `fix` — bug fix (patch version bump)
- [ ] `deps` — dependency update (patch version bump when releasable)
- [ ] `chore` / `docs` / `refactor` / `test` / `ci` / `build` — no version bump
- [ ] `feat!` or `BREAKING CHANGE:` — breaking change (major version bump)

## Related Issue

<!-- Link to the issue this addresses, if applicable: Closes #123 -->

## Checklist

- [ ] PR title follows Conventional Commits format: `<type>[optional scope]: <description>`
- [ ] Description is a single paragraph suitable for the squash-merge commit body
- [ ] PHP code has been formatted with `composer cs` (when applicable)
- [ ] Tests have been added or updated where appropriate
- [ ] All tests pass with `composer test`
- [ ] Dependency changes are reflected in `composer.json` and `composer.lock` where appropriate
- [ ] Breaking changes in updated dependencies have been assessed (dependency changes only)
- [ ] Fix has been verified locally (bug fixes only)
