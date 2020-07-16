Feature: Correct articles type for specific items
@Ci
  Scenario: Article type is correct
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user navigates to "00170"
    Then "Article page" is displayed
    And the article type is "RESEARCH NOTE"
    When user navigates to "00171"
    Then "Article page" is displayed
    And the article type is "RESEARCH NOTE"
