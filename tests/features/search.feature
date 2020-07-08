@search
Feature: Search page

  Rules:
  - All content types are searchable
  - Search results are loaded in batches of 10
  - Searches are ordered by most relevant first by default
  - If the ordering can't separate two or more search results, fallback ordering is publication date (most recent first) then title (A first).

  Background:
    Given there is 44 research article about 'Demography'
    And user is on the 'Search' page

  Scenario: List shows 10 most relevant results
    When user searches for 'demography'
    Then user should see the 10 most relevant results for 'demography'

  @javascript
  Scenario: Loading more adds previous 10 to the list
    When user searches for 'demography'
    And user clicks on 'load more' results
    Then user should see the 20 most relevant results for 'demography'
