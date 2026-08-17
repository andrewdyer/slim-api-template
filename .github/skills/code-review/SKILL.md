---
name: code-review
description: Review PHP API changes for correctness, regressions, security, ADR boundaries, runtime configuration, persistence, API contract documentation, and test coverage. Use for pull requests or diffs in this Slim Framework ADR project, including changes to application, domain, infrastructure, bootstrap, routes, middleware, error handling, database, OpenAPI attributes, tests, containers, or CI.
---

# Code review conventions

Review the current project as it exists. Check the relevant dependency versions, bootstrap
configuration, formatter rules, test setup, and deployment workflow before applying a convention
that may have changed.

Prioritise actionable defects introduced by the diff. Report a convention issue only when it
affects correctness, security, maintainability, or an explicitly configured project standard.
Use the smallest useful line range, explain the concrete failure mode, and avoid speculative
findings or unrelated pre-existing issues.

## ADR and layer boundaries

- Treat Action-Domain-Responder (ADR) as the organising pattern. Recognise that the project groups
  code into `Application`, `Domain`, and `Infrastructure` rather than creating a separate
  Responder directory.
- Keep Actions at the HTTP boundary. Let them read request data, construct input DTOs, invoke
  application services, map results to output DTOs, and choose a response. Do not put persistence
  queries or reusable business rules in Actions.
- Keep application services focused on use-case orchestration. Let them coordinate DTOs, domain
  objects, and repository contracts without depending on PSR-7 requests or responses, routes,
  Eloquent models, query builders, or database-driver types.
- Keep the Domain independent of Slim and concrete infrastructure. Put business concepts,
  invariants, entities, domain exceptions, and repository contracts there. Avoid using domain
  serialization as a substitute for the application output DTOs used to shape API responses.
- Treat the response helpers and exception mapping supplied by
  `AndrewDyer\Actions\AbstractAction` as the project's Responder behaviour. Keep HTTP status
  selection, payload envelopes, headers, and JSON response formatting out of domain objects,
  repositories, and application services.
- Preserve dependency direction: Actions may depend on application services, and infrastructure
  adapters may implement domain contracts. Do not make application or domain code depend on
  concrete infrastructure implementations.
- Translate failures at boundaries so each layer exposes exceptions meaningful to its callers
  without leaking lower-level implementation details.

## Bootstrap, configuration, and HTTP lifecycle

- Preserve the bootstrap sequence when the same dependencies remain: load the environment,
  register settings, dependencies, and repository bindings, build and attach the PHP-DI container,
  create the Slim app, register middleware and error handling, boot persistence, then register
  routes.
- Account for Slim middleware executing in last-in, first-out order. Verify the effective order,
  not only the order of `$app->add()` calls, when reviewing authentication, body parsing, routing,
  error handling, or other cross-cutting middleware.
- Keep interface-to-implementation bindings in the container/bootstrap layer. Avoid service
  location from domain code and avoid constructing infrastructure adapters inside Actions or
  application services.
- Keep configuration in environment variables mapped through `bootstrap/settings.php`. When a
  variable is added or renamed, update `.env.example` and every relevant test, Compose, container,
  CI, migration, or deployment configuration without committing secrets.
- Verify environment-loading behaviour before changing it. The project loads `.env.test` for
  `APP_ENV=testing`, loads `.env` only when `APP_ENV` is unset, and expects production
  configuration to be injected by the runtime environment.
- Review `public/index.php` when response emission or global failures change. Normal responses use
  the CORS-aware emitter, while fatal errors pass through the shutdown handler, JSON error handler,
  and the same emitter. Keep those paths consistent.
- Treat logging and detailed error output as security-sensitive. Do not expose stack traces,
  credentials, connection details, or internal exception messages when debug output is disabled.

## Slim routing

- Unless configuration selects another invocation strategy, expect Slim's default
  `RequestResponse` strategy to invoke route callables with the request, response, and an array of
  route arguments. PHP accepts surplus arguments to user-defined functions, so a two-parameter
  route closure is not by itself an `ArgumentCountError` risk.
- When a container is attached, account for the installed Slim 4 callable resolver binding route
  and route-group closures to that container. A `static` closure cannot be rebound and fails with
  the currently installed Slim version. Do not make closures passed directly to route methods or
  `group()` static unless the current resolver has been verified to support it. Bootstrap closures
  invoked directly by project code are unaffected.
- Distinguish FastRoute placeholder quantifiers: `{name:.+}` requires at least one character,
  while `{name:.*}` also matches an empty value. Check whether a catch-all route must cover the
  root path before choosing between them.
- Check route arguments and query parameters at the HTTP boundary. Casting arbitrary strings to
  integers can silently turn invalid input into `0`; accept clamping only where the API explicitly
  defines that behaviour and tests it.

## Errors and response mapping

- When Actions extend `AndrewDyer\Actions\AbstractAction`, verify the installed package version
  before relying on its mappings. The current dependency catches the bad-request, conflict,
  forbidden, not-found, not-implemented, and unauthenticated exception interfaces around
  `handle()` and converts them to JSON responses.
- Do not expect generic SPL or domain exceptions to receive those HTTP mappings automatically.
  Uncaught exceptions reach Slim's configured `JsonErrorHandler` and normally become a 500
  response; Slim `HttpException` instances are mapped separately by that handler.
- Keep deliberate exception translation visible: infrastructure failures become domain-level
  failures, and application services may translate those into the HTTP-aware application
  exceptions expected by the Action/Responder boundary.
- Preserve the response contract when changing Actions or error handling: status code, action
  payload envelope, metadata, JSON shape, content type, and empty-body semantics all matter.

## OpenAPI documentation

- Treat `#[OA\...]` attributes as documentation of the real wire contract, not aspirational or
  copy-pasted boilerplate. Verify response content matches the serialized body when one exists —
  for a success response, the outer envelope (e.g. a `data`/`meta` wrapper) as well as the inner
  shape; for an error response, the real error envelope and the actual `type`/status values the
  error-mapping layer produces, not placeholder strings. A bodyless response (e.g. `204`) should
  declare no `content` at all, not an empty or invented schema.
- Don't require error-response content to be inlined on every operation, and don't require it to
  be factored into a shared component either — swagger-php supports both via `#[OA\Response]`
  reused through `$ref`, and a project may reasonably choose either. The only hard constraint is
  that attribute arguments must be compile-time constant expressions, so a shared *PHP builder
  function* can't be called from inside one; that doesn't rule out shared components. Check that
  whichever pattern the project has actually adopted is applied consistently, rather than assuming
  one approach is mandatory.
- Check that example values are representative of the response's real structure, meaning, and
  error type for that specific operation and status code — not a generic placeholder reused
  everywhere. Exact literal text isn't required, and often can't be: many real messages carry
  dynamic values (an ID, an email), so an example only needs to match the format and intent, not
  be pinned to one exact runtime string.
- Require an explicit `operationId` on every path operation attribute. Without one, the generator
  falls back to an opaque content hash, which breaks readability of the generated spec and any
  client code generated from it.
- Confirm every `$ref` (e.g. `#/components/schemas/...`) still resolves to a schema actually
  defined somewhere in the scanned source tree. A rename or removal needs every reference updated
  in the same change — the generator does not necessarily fail loudly on a dangling reference.
- Verify documented responses cover the supported HTTP outcomes for that operation, including
  responses produced by shared helpers, middleware, or framework error handling when they form
  part of the API contract.
- Check whether the generated spec artifact is gitignored or committed in this project before
  judging its presence or absence in the diff. It is produced by a Composer script from these
  attributes, and either choice is valid: gitignored means its absence is intentional, not an
  oversight; committed means it should appear in the diff and match what a fresh regeneration
  produces. Either way, regenerate it locally when reviewing a non-trivial attribute change and
  confirm generation completes without warnings.

## Persistence and migrations

- Treat Eloquent through `illuminate/database` Capsule as the configured persistence adapter, but
  verify the current project configuration. Do not assume the full Laravel framework or
  Laravel-specific services are available.
- Keep Eloquent models, builders, and collections inside Infrastructure. Convert persistence
  models to domain objects at repository boundaries and return the shapes promised by repository
  contracts.
- Keep Phinx migrations, Eloquent model configuration, repository reads and writes, database
  defaults, nullability, indexes, and timestamp behaviour consistent. Review both upgrade and
  rollback paths for schema changes.
- Treat database constraints as the final authority. A preflight `exists()` check may improve the
  normal error path but is vulnerable to a race between the check and write; do not let it replace
  a unique constraint or handling of the resulting write failure.
- When distinguishing constraint failures, use evidence appropriate to the configured driver.
  SQLSTATE `23000` alone covers multiple integrity violations, and MySQL code `1062` is not
  portable to other drivers.
- Check pagination for deterministic ordering, bounded page sizes, correct totals, and consistent
  behaviour between production and in-memory repository implementations.

## Tests and verification

- Use `phpunit.xml` and Composer scripts as the source of truth for suite names and commands. Do
  not infer test placement solely from production namespaces.
- Keep isolated use-case and DTO tests under the unit suite and full HTTP-stack tests under the
  integration suite. Put cross-cutting HTTP behaviour such as routing, middleware, CORS, and
  global error formatting in focused HTTP integration tests.
- Use repository doubles that implement the same domain contract for unit tests. Check semantic
  parity with production adapters, especially uniqueness, missing records, partial updates,
  pagination, and return types.
- Treat integration tests as database-backed while they load the configured application. They do
  not run migrations automatically, so require a separate test database and applied migrations.
  The suite does not currently truncate or reset data between runs, so tests must tolerate
  accumulated data (e.g. delta-based assertions, unique generated values) unless a change adds
  real cleanup. Never allow tests to target development or production data.
- Test response emission at the correct boundary. Calling `$app->handle()` covers the Slim app but
  bypasses the CORS emitter and shutdown logic in `public/index.php`.
- Ask for regression coverage for observable changed behaviour, including success and failure
  paths, boundary values, exception-to-status mapping, schema constraints, and layer translation.
- Use the project formatter as the style authority. The current PHP-CS-Fixer configuration scans
  `app`, `bootstrap`, `database`, `public`, `tests`, and `workbench`; its class-element rule orders
  properties before methods and sorts configured groups alphabetically. Class constants are not
  part of that configured order and sort to the end of the class, after every method, not
  alongside properties — verify placement rather than assuming it.
- Review `composer.json` and `composer.lock` together for dependency changes. Check PHP and
  extension requirements across local setup, CI, and the production image, then run the smallest
  relevant test suites plus formatting checks.

## Containers and CI

- Keep the web document root restricted to `public/` and retain the server configuration required
  for Slim routing. Do not copy local secrets or development-only state into production images.
- Verify that production images install locked production dependencies, include required PHP
  extensions, write only to intended runtime directories, and receive configuration at runtime.
- Keep CI service versions, PHP versions, extensions, environment variables, migrations, and test
  commands aligned with supported local and production environments.
- Treat publish, deploy, and rollback workflow changes as operationally sensitive. Validate event
  inputs, permissions, image tags, registry paths, failure propagation, and secret handling; do not
  mistake placeholder deployment steps for a working deployment.
