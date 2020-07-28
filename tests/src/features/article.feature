@article
Feature: Article page

  Rules:
  - Abstract, digest and first body section are open, the rest closed

  @javascript
  Scenario: Article sections can be closed
    Given there is a research article VoR
    When I go the research article page
    Then the "Abstract" section should be open
    And the "eLife digest" section should be open
    And the "Introduction" section should be open
    But the "Results" section should be closed

  @Failing
  Scenario: Article main sections are displayed
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on "First article" from the list
    Then "Article page" is displayed
    And following sections are displayed:
      | Abstract                       |
      | Introduction                   |
      | Appendix                       |
      | References                     |
      | Article and author information |
      | Conclusion                     |

  Scenario:Article's figure and data are displayed
    Given articles are uploaded
    When user navigates to "Home" page
    Then a list of articles is displayed
    When user clicks on the first article from the list
    Then article is displayed in a new page
    When user clicks on "Figures and data"
    Then "Figures" page is displayed
    And following section are displayed:
      | Figures |
      | Tables  |

  @issues
  Scenario Outline: List of issues is displayed and grouped into 3-year dropdown
    Given user navigates to "Home" page
    When user is on the Home page
    Then list of issues is displayed
    When user clicks on "<issueName>"
    Then dropdown with list of issues by 3-year is displayed
    And list of issues is grouped in a chronologically descending order
    Examples:
      | issueName |

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

    @Ci
  Scenario Outline: Open article citations option
    Given user navigates to "Home" page
    When user is on the Home page
    Then a list of 10 articles is displayed
    When user clicks on "First article" from the list
    Then "Article page" is displayed
    When user clicks on "Download"
    And user selects "<exportReference>"
    Then "<exportReference>" is displayed
    Examples:
      | exportReference |
      | Mendeley        |
      | ReadCube        |
