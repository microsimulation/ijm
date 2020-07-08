@subject
Feature: Subject page latest articles

  Background:
    Given there are 47 articles with the subject 'Taxes and benefits'

  Scenario: List shows latest 10 items
    When user goes to the 'Taxes and benefits' page
    Then user should see the latest 10 items with the 'Taxes and benefits' in the 'Latest articles' list

  @javascript
  Scenario: Loading more content adds previous 10 to the list
    When user goes to the 'Taxes and benefits' page
    And user clicks on 'Load More'
    Then user should see the latest 20 items with the 'Taxes and benefits' in the 'Latest articles' list
