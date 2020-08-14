@Regression
Feature: About page

  Scenario: Load about page
    Given Microsim site Home page was loaded
    When user click About link
    Then the About page is loaded
