beforeEach(function() {
  browser().navigateTo('/');
});

/**
 * Testing routes
 * Here we basically navigate the browser and check results.
 */


it('should jump to the /videos path when / is accessed', function() {
  expect(browser().location().path()).toBe("/");
});