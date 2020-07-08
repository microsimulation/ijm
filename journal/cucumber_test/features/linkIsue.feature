
Feature: Link the issue to the article


  Scenario: List shows all subjects
    Given user navigates to 'Home' page
    Then a list of articles is displayed
    When user clicks on the first article from the list
    Then 'article' page is displayed
    When user clicks on 'Linked isue'
    Then 'Issue' page is displayed
