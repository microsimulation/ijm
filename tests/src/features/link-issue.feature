Feature: Link the issue to the article
  @Ci
  Scenario: Link to the issue is available from article page
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on "First article" from the list
    Then "Article page" is displayed
    When user clicks on "Linked volume"
    Then "Issues page" is displayed

  @Regression
  Scenario: Link to the issue is available from article preview
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on 'Linked volume' of the random article
    Then "Issues page" is displayed

  @Ci
  Scenario: Date is not displayed in article preview
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    And article preview doesn't contain date

  @Ci
  Scenario: Add issue volume to the preview in category browsing
    Given user navigates to "Home" page
    When user is on the Home page
    And user navigates to "scientific-correspondence"
    Then "Book reviews" page is displayed
    When user clicks on "Volume from category"
    Then "Issues page" is displayed
    When user navigates to "research-article"
    Then "Research articles" page is displayed
    When user clicks on "Volume from category"
    Then "Issues page" is displayed

  @Ci
  Scenario: Add issue volume number to the preview in subject topic browsing
    Given user navigates to "Home" page
    Then user is on the Home page
    When user clicks on "Demography" subject
    Then "Demography" page is displayed
    When user clicks on "Volume from category"
    Then "Issues page" is displayed

  @Ci
  Scenario: Check Issue volume number to the preview in Search results
    Given user navigates to "Home" page
    When user is on the Home page
    And user clicks on "Search button"
    And user searches for "Demography"
    Then "Search page" is displayed
    And a list of 10 articles is displayed
    When user clicks on 'Linked volume' of the random article
    Then "Issues page" is displayed

