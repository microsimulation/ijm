Feature: Link the issue to the article

  @Run
  Scenario: Link to the issue is available from article page
    Given user navigates to 'Home' page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on the first article from the list
    Then 'Article' page is displayed
    When user clicks on 'Linked volume'
    Then 'Issues' page is displayed
