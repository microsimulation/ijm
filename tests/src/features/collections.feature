@collection @manualOnly
Feature: Collections page latest collections

  Background:
    Given there are 30 issues

  Scenario: List shows latest 10 issues
    When user goes to the 'Issues' page
    Then user should see the 10 most-recently-updated issues in the 'Latest' list

  Scenario: Loading more content adds previous 10 to the list
    When user goes to the 'Issues' page
    And user clicks on 'LOAD MORE'
    Then user should see the 20 most-recently-updated collections in the 'Latest' list
