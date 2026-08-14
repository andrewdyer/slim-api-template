# Contributing

Thank you for your interest in contributing! Contributions and suggestions that improve the project are always welcome. The guidelines below help ensure a smooth and productive experience for everyone involved.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Environment Setup](#environment-setup)
- [Development Setup](#development-setup)
- [Development Workflow](#development-workflow)
  - [Branching](#branching)
  - [Coding](#coding)
  - [Testing](#testing)
  - [Committing](#committing)
- [Dependency Management](#dependency-management)
- [Coding Standards](#coding-standards)
- [Issue Reporting](#issue-reporting)

## Code of Conduct

We strive to maintain a welcoming, respectful, and inclusive community where everyone can collaborate productively. Please adhere to our [Code of Conduct](./CODE_OF_CONDUCT.md) in all interactions.

## Environment Setup

Environment-specific configuration is kept outside the codebase so contributors can use local services and credentials safely. The committed `.env.example` file is the canonical reference for every required variable and should be updated whenever configuration is added or changed.

Create and configure the environment files needed for local development:

1. Copy `.env.example` to `.env` and update its values for the local application and database.
2. Copy `.env.example` to `.env.test` and configure a separate test database so the test suite cannot modify development data.

The application loads `.env` during local development and `.env.test` when `APP_ENV=testing`. PHPUnit sets the testing environment automatically. Other environments should provide configuration through their runtime or deployment platform; additional dotenv files can be introduced here in the future when the application supports them.

> **Note:** Populated environment files must never be committed. Keep placeholders and safe defaults in `.env.example` so new contributors can reproduce the required configuration without exposing credentials.

## Development Setup

A consistent development environment helps ensure contributors can run and test the project reliably.

Prepare the project for local development after cloning the repository:

1. Install PHP 8.3 or later and [Composer](https://getcomposer.org/). 
2. Install project dependencies with `composer install`.
3. Complete the [environment setup](#environment-setup).
4. Run migrations against the development database with `composer db:migrate`.
5. Start the development server with `php -S 127.0.0.1:8888 -t public public/index.php`.

A running development server confirms the environment is ready for local changes.

## Development Workflow

Moving from a new branch to a reviewable change follows the same sequence for every contribution.

### Branching

Keeping work isolated in focused branches makes reviews easier and reduces the risk of unrelated changes being introduced.

Choose the appropriate target branch before creating a feature branch:

- Bug fixes should be sent to the latest stable branch.
- Minor features that are fully backwards compatible with the current release may be sent to the latest stable branch.
- Major features should always be sent to the `main` branch, which contains the upcoming release.

Create and submit a branch for each change in order:

1. Create a feature branch for each change with `git checkout -b feature/your-feature-name`.
2. Complete the change, then test and commit it — see [Coding](#coding), [Testing](#testing), and [Committing](#committing).
3. Push the branch once changes are ready with `git push origin feature/your-feature-name`.
4. Open a pull request with a title and description that clearly explain the change — see [Committing](#committing) for the title format.

An open pull request signals the change is ready for review.

> **Tip:** GitHub pre-fills the description from the repository's single pull request template, ready to complete before submitting.

The review process continues until the change is ready to merge:

- Review feedback carefully and suggest improvements or alternatives when needed.
- Apply requested changes in follow-up commits instead of overwriting or squashing history; the merge will be squashed later.
- Keep the branch up to date with the target branch if new commits land while review is in progress.
- Re-request review after the requested changes are in place.
- Resolve review conversations once the underlying concern has been addressed.

The pull request is ready to merge once review conversations are resolved and required checks pass.

### Coding

Keeping changes focused makes reviews easier and reduces the likelihood of unrelated regressions.

Write the change with the existing codebase in mind:

- Keep changes limited to the branch's purpose, avoiding unrelated edits.
- Match existing patterns and conventions already used nearby in the codebase.
- Add or update tests alongside behavioural changes.
- Apply the formatting expectations described in [Coding Standards](#coding-standards) while writing code.

A focused, convention-following change is faster to review and less likely to introduce regressions.

### Testing

Writing tests helps verify changes behave as expected and reduces the chance of regressions reaching other contributors.

Follow these conventions when writing tests:

- Declare `#[CoversClass]`/`#[CoversFunction]` for the subject under test and any collaborator it genuinely exercises.
- Add a dedicated integration test when a repository has behaviour the service layer can't reach.
- Follow the existing test structure as the template when adding tests for a new resource.

Before running the test suite, complete the [environment setup](#environment-setup), confirm `.env.test` points to an available test database, and run its migrations with `APP_ENV=testing composer db:migrate`.

Run the validation suite before submitting changes:

- Execute all tests with `composer test`.
- Run fast, isolated unit tests with `composer test:unit`.
- Verify behaviour across layers with `composer test:integration`.

Structure tests consistently for readability and easier maintenance:

- Keep test cases small and focused.
- Use unit tests for isolated classes and methods.
- Use integration tests when behaviour crosses application layers or HTTP boundaries.

Passing tests confirm changes behave as intended and are ready for review.

### Committing

Consistent commit messages, written in a shared format, improve project history and clarify the intent behind each change.

Follow the format below for every commit:

```text
<type>(<scope>): <description>

<body>

[optional footer]
```

The subject line summarises the change and must:

1. **Use a valid commit type.**
   - A new feature uses `feat`.
   - A bug fix uses `fix`.
   - A dependency change uses `deps`.
   - Maintenance, documentation, refactors, tests, and CI changes use `chore`, `docs`, `refactor`, `test`, or `ci`.
2. **Use the scope to identify the affected area of the application.**
3. **Have a clear description.**
   - Use lowercase text.
   - Be written in the imperative style (for example, `add feature` instead of `added feature`).
   - Be concise and specific enough to understand the change at a glance.
   - Do not end with a full stop.
   - Keep the subject line under 72 characters.

> **Tip:** Omit the scope for repository-wide changes.

The body provides additional context about the change and should:

- Explain what changed and why.
- Include relevant context that helps reviewers understand the decision without reading the diff.
- Keep lines under 100 characters.
- Use paragraphs rather than lists or headers.

The footer is optional and is used for additional metadata, such as breaking changes.

- A breaking change should use `!` after the type (for example, `feat!`) or include a `BREAKING CHANGE:` footer.

> **Note:** Pull request titles follow the same format, since they become the squash merge commit message.

A well-formatted commit message helps reviewers and future contributors understand why a change was made.

## Dependency Management

Dependencies should be managed carefully to keep the project secure, compatible, and easy to maintain over time.

Check for outdated dependencies before planning an update with `composer outdated`.

Update dependencies to bring in fixes and improvements:

- Update a specific package with `composer update <vendor/package>`.
- Update every dependency within the constraints in `composer.json` with `composer update`.

After any dependency change, run the test suite, then commit `composer.json` and `composer.lock` together.

## Coding Standards

Following shared coding conventions keeps the codebase consistent, readable, and easier to maintain.

Apply consistent formatting before submitting changes with `composer cs`.

Formatted code passes CI checks without additional review comments on style.

## Issue Reporting

Clear issue reports make it easier to reproduce problems, discuss improvements, and track future work.

Select the template that matches the issue before submitting a report:

- Unexpected behaviour or defects use the Bug Report template.
- New features or improvements use the Feature Request template.
- Requests for help or clarification use the Question template.
- Avoid duplicate reports by searching existing issues and checking the README and documentation first.

> **Tip:** GitHub shows the matching template automatically once an issue category is selected.

A complete, well-templated report helps maintainers triage and respond quickly.
