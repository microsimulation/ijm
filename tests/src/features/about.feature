@Regression
Feature: About page

  Scenario Outline: Load about page
    Given Microsim site Home page was loaded
    When user click About link
    Then the About page is loaded
    When user navigates to about submenu "<Submenu>"
    Then "<pageName>" category page is displayed
    Examples:
      | pageName          | Submenu             |
      | editorial-board   | Editorial board     |
      | submission-policy | Submission policy   |
      | author-notes      | Notes for authors   |
      | reviewer-notes    | Notes for reviewers |
