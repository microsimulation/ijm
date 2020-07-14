@article-type
Feature: Article type page

  Rules:
  - Articles are loaded in batches of 10
  - Articles are shown most recent first
  - If an article is POA, the date used for ordering is the first POA date
  - If an article is VOR, the date used for ordering is the first VOR date

  Scenario: List shows latest 10 articles
    Given 20 research articles have been published
    When I go to the research articles page
    Then I should see the latest 10 research articles in the 'Latest articles' list

  @javascript
  Scenario: Loading more articles adds previous 10 to the list
    Given 30 research articles have been published
    When I go to the research articles page
    And I load more articles
    Then I should see the latest 20 research articles in the 'Latest articles' list

  Scenario: Selection capability containing the special type of articles
    Given user navigates to 'Home' page
    When user is on the Home page
    Then section "In addition to standard research articles, explore our:" is displayed
    And the following special type of articles is displayed:
      | Book reviews     |
      | Data watch       |
      | Research notes   |
      | Software reviews |

  Scenario Outline: Access special type of articles
    Given user navigates to 'Home' page
    When user is on the Home page
    And user navigates to "<articleName>" article
    Then "<pageName>" page is displayed
    Examples:
      | articleName               | pageName         |
      | scientific-correspondence | Book reviews     |
      | tools-resources           | Data watch       |
      | short-report              | Research notes   |
      | registered-report         | Software reviews |