@article-type
Feature: Article type page

  @manualOnly
  Scenario: List shows latest 10 articles
    Given 20 research articles have been published
    When I go to the research articles page
    Then I should see the latest 10 research articles in the 'Latest articles' list

  @manualOnly
  Scenario: Loading more articles adds previous 10 to the list
    Given 30 research articles have been published
    When I go to the research articles page
    And I load more articles
    Then I should see the latest 20 research articles in the 'Latest articles' list

  @Regression
  Scenario: Selection capability containing the special type of articles
    Given user navigates to "Home" page
    When user is on the Home page
    Then section "In addition to standard research articles, explore our:" is displayed
    And the following special type of articles is displayed:
      | Book reviews     |
      | Data watch       |
      | Research notes   |
      | Software reviews |

  @Ci
  Scenario Outline: Access special type of articles
    Given user navigates to "Home" page
    When user is on the Home page
    And user navigates to article type "<articleType>"
    Then "<pageName>" page is displayed
    Examples:
      | articleType      | pageName         |
      | book-reviews     | Book reviews     |
      | data-watch       | Data watch       |
      | research-notes   | Research notes   |
      | software-reviews | Software reviews |
