@Run
Feature: Link the issue to the article

  Scenario: Link to the issue is available from article page
    Given user navigates to 'Home' page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on the first article from the list
    Then 'Article' page is displayed
    When user clicks on 'Linked volume'
    Then 'Issues' page is displayed

   Scenario: Link to the issue is available from article preview
     Given user navigates to 'Home' page
     When user is on the Home page
     Then a list of 10 articles is displayed
     When user clicks on 'Linked volume' of the first article
     Then 'Issues' page is displayed

  Scenario: Date is not displayed in article preview
    Given user navigates to 'Home' page
    When user is on the Home page
    Then a list of 10 articles is displayed
    And article preview doesn't contain date
