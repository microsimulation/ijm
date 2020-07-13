@search
Feature: Search from page header

  Rules:
  - If the current page has a subject, the first subject appears as a limiting option when searching on that page

  Background:
    Given user is reading an article:
      | Subjects | LABOUR SUPPLY AND DEMAND |

  @javascript
  Scenario: Page has subjects
    When user clicks on 'Search'
    Then user should see the option to limit the search to 'LABOUR SUPPLY AND DEMAND'
