---
name: code-review
description: Review PHP API changes for correctness, regressions, security, architecture, and test coverage. Use for pull requests or diffs in projects based on this Slim Framework ADR template, especially changes to bootstrap code, application or domain logic, infrastructure, routing, exception handling, and persistence.
---

# Code review conventions

Review the code as it exists in the current project. Before applying the conventions below,
check the relevant dependency versions, bootstrap configuration, formatter rules, and test
layout because projects created from this template may have changed them.

Prioritise actionable defects introduced by the diff. Do not report a convention violation
unless it can affect correctness, maintainability, security, or an explicitly configured
project standard. Include the affected file and the smallest useful line range, explain the
failure mode, and avoid speculative findings.

## Architecture

- Treat Action-Domain-Responder (ADR) as the organising pattern, but verify that the project
  has retained these boundaries.
- Keep Actions at the HTTP boundary. Let them read request data, construct input DTOs,
  invoke application services, map results to output DTOs, and select the response. Do not
  put persistence queries or reusable business rules in Actions.
- Keep application services focused on use-case orchestration. Let them coordinate domain
  objects and repository interfaces without depending on PSR-7 requests or responses,
  routing, Eloquent models, query builders, or database-driver types.
- Keep the Domain independent of delivery and infrastructure concerns. Put business
  concepts, invariants, entities, value objects, domain exceptions, and repository
  contracts there; do not introduce Slim, PSR-7, or persistence-specific types.
- Keep Responder behaviour responsible for turning application outcomes into HTTP
  responses. In projects using `AndrewDyer\Actions\AbstractAction`, review its response
  helpers and exception mapping as the Responder part of ADR. Do not format HTTP responses
  in domain objects, repositories, or application services.
- Preserve dependency direction: Actions may depend on application services, and
  infrastructure adapters may implement domain contracts. Do not make application or
  domain code depend on concrete infrastructure implementations.
- Translate failures at boundaries so each layer exposes exceptions meaningful to its
  callers without leaking lower-level implementation details.

## Slim routing

- Unless bootstrap configuration selects another invocation strategy, expect Slim's default
  `RequestResponse` strategy to invoke route callables with the request, response, and an
  array of route arguments. A closure may declare fewer parameters: PHP accepts surplus
  arguments to user-defined functions, so a two-parameter route closure is not by itself an
  `ArgumentCountError` risk.
- When a container is attached, account for Slim 4's callable resolver binding route and
  route-group closures to that container. A `static` closure cannot be rebound and may
  therefore fail resolution. Do not make closures passed directly to route methods or
  `group()` static unless the project's Slim version or callable resolver has been verified
  to support it. This restriction does not apply to bootstrap closures invoked directly by
  project code.
- Distinguish FastRoute placeholder quantifiers: `{name:.+}` requires at least one character,
  while `{name:.*}` also matches an empty value. Check whether a catch-all route must cover
  the root path before choosing between them.

## Exception handling and HTTP status mapping

- When actions extend `AndrewDyer\Actions\AbstractAction`, verify which exception interfaces
  the installed package version catches. In the template's current dependency line these
  are `BadRequestExceptionInterface`, `ConflictExceptionInterface`,
  `ForbiddenExceptionInterface`, `NotFoundExceptionInterface`,
  `NotImplementedExceptionInterface`, and `UnauthenticatedExceptionInterface`.
- Do not expect generic SPL exceptions such as `InvalidArgumentException` or
  `RuntimeException` to automatically receive those HTTP mappings. An uncaught exception
  reaches the configured global error handler and normally becomes a 500 response.
- Infrastructure repositories should translate low-level persistence failures into domain
  exceptions. Application services can then translate domain exceptions into application
  exceptions implementing the interfaces expected by the action layer.

## Persistence

- Treat Eloquent through `illuminate/database` Capsule as the template's initial persistence
  adapter, but verify the current project configuration. Do not assume the full Laravel
  framework or Laravel-specific services are available.
- Treat database constraints as the final authority. A preflight `exists()` check may
  improve the normal error path but is vulnerable to a race between the check and write;
  it must not replace a unique constraint or handling of the resulting write failure.
- If code distinguishes constraint failures, require evidence appropriate to the configured
  database driver. SQLSTATE `23000` alone is not specific to duplicate values because it
  also covers other integrity violations. Avoid assuming MySQL error code `1062` in code
  intended to support other drivers.
- Check that Eloquent models are converted to domain objects at the repository boundary and
  that query builders, collections, and persistence models do not leak into domain or
  application APIs.

## Style

- Use the project's formatter as the source of truth. In an unchanged template,
  `.php-cs-fixer.dist.php` configures `ordered_class_elements` with properties before
  methods and alphabetical sorting within configured groups.
- Bootstrap factory closures invoked directly by project code may be `static`. Route and
  group closures are the exception described under Slim routing when container binding is
  active.

## Tests

- Follow the current test-suite configuration rather than assuming every derived project
  retains the template layout.
- In an unchanged template, expect unit and application integration tests to mirror the
  corresponding `app/` namespace. Place cross-cutting HTTP behaviour, such as CORS, global
  error formatting, and middleware, under `tests/Integration/Http/`.
- Integration tests use the configured database. Unit tests should use an in-memory or
  otherwise isolated repository double instead of requiring Eloquent or a live database.
- Ask for regression coverage when a changed behaviour is observable and practical to
  test, especially for routing, exception-to-status mapping, persistence constraints, and
  layer-boundary translation.
