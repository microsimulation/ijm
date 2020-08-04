@homepage @manualOnly
Feature: Homepage 'Latest research' list

  Scenario: List shows latest 10 articles
    Given 20 articles have been published
    When I go to the homepage
    Then I should see the latest 10 articles in the 'Latest research' list

  Scenario: Loading more articles adds previous 10 to the list
    Given 30 articles have been published
    When I go to the homepage
    And I load more articles
    Then I should see the latest 20 articles in the 'Latest research' list


