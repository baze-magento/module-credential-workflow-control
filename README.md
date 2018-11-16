# Credential Workflow Control

Displays a page to enforce user credential entry when a user with no email, or with no password, logs in.

## Architecture

Standalone module.

## Properties

Module name: Baze_CredentialWorkflowControl

No configuration options available.

## Notes

### Caching

Users who are intercepted cannot interact with the standard cache system, as they would instead recieve an un-redirected version of the page before interception can take place. The standard cache plugin has been extended to completely ignore users with the intercept flag set.

### Interceptors (for the Future)

At this time, we have one interceptor in our entire codebase. (see https://github.com/baze-magento/module-credential-workflow-control/blob/master/Observer/LoginInterceptCheck.php)

That interceptor runs on every page load, and if it matches, it redirects to the destination path.

It also requires a cache plugin that ensures users being intercepted do not reach the cache, either to GET or SET entries. (see https://github.com/baze-magento/module-credential-workflow-control/blob/master/Plugin/Cache.php)

---

This, of course, has a performance hit. If and when we write more interceptors, simply adding them side-by-side would increase this hit, not to mention make the code less maintainable. As an alternative, we should write a separate module for the following:

1) The module exposes a method that adds an interceptor by name, destination, priority, and whitelist. This gets added to an array on the session.
2) The module contains a shared interceptor that can read and sort this array. As long as entries exist in the array, it performs at least one of the redirects.
3) The module exposes a method that removes an interceptor by name, to be called once its task is complete.
4) The module contains an override to the Cache loading plugin that checks to see whether an Intercept is to take place, and if so, disables all Caching logic on the operation.

At that point, we take the "LoginInterceptCheck" Observer out of Credential Workflow Control and rewrite the intercept code to point to the new module. We do the same for any new modules, as well.

The performance impact is only paid for a single interceptor, we get a simple interface to register as many as we need to, and any future feature additions can be made to a centralized location.
