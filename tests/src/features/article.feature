@article
Feature: Article page

  @manualOnly
  Scenario: Article sections can be closed
    Given there is a research article VoR
    When I go the research article page
    Then the "Abstract" section should be open
    And the "eLife digest" section should be open
    And the "Introduction" section should be open
    But the "Results" section should be closed

  @Regression
  Scenario: Article main sections are displayed
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user navigates to "00201"
    Then "Article page" is displayed
    And following sections are displayed:
      | Abstract                       |
      | Introduction                   |
      | References                     |
      | Article and author information |
      | Conclusions                    |

  @Regression
  Scenario Outline: List of issues is displayed and grouped into 3-year dropdown
    Given user navigates to "Home" page
    When user is on the Home page
    Then list of issue group is displayed
    When user clicks on issue group "<issueGroupYear>"
    Then dropdown with list of issues by "<issueGroupYear>" is displayed
    Examples:
      | issueGroupYear |
      | 2019-2017      |
      | 2016-2014      |
      | 2013-2011      |
      | 2010-2008      |
      | 2007-2005      |

  @Ci
  Scenario Outline: Download article citations option
    Given user navigates to "Home" page
    And user is on the Home page
    When user navigates to "<ArticleId>"
    Then "Article page" is displayed
    When user clicks on "Download"
    And user selects "Article PDF"
    Then a "Article PDF" file is downloaded
    When user selects "BibTeX"
    Then a "BibTeX" file is downloaded
    When user selects "RIS"
    Then a "RIS" file is downloaded
    Examples:
      | ArticleId |
      | 00170     |
      | 00197     |
      | 00198     |
      | 00199     |
      | 00200     |
      | 00201     |
      | 00202     |
      | 00203     |
      | 00204     |
      | 00205     |
      | 00206     |
      | 00207     |
      | 00208     |

  @Regression
  Scenario: Open article citations using Mendeley option
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on "First article" from the list
    Then "Article page" is displayed
    When user clicks on "Download"
    And user selects "Mendeley"
    And user logs in to Mendeley
    And user clicks on "Import" button
    And user clicks on "Library" menu item
    Then article is displayed in Mendeley

  @Regression
  Scenario Outline:  Images in articles are displayed
    Given user navigates to "Home" page
    And user is on the Home page
    When user navigates to "<articleId>"
    Then "Article page" is displayed
    And Images in article are loaded
    Examples:
      | articleId |
      | 00198     |
      | 00199     |
      | 00200     |
      | 00201     |
      | 00202     |
      | 00205     |
      | 00206     |
      | 00208     |
