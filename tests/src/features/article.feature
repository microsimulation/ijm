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

  Scenario: Article can be displayed
    Given articles are uploaded
    When user navigates to "Home" page
    Then a list of articles is displayed
    When user clicks on the first article from the list
    Then "Article" is displayed
    And following sections are displayed:
      | Abstract                       |
      | Introduction                   |
      | Data and methodology           |
      | Conclusions                    |
      | Appendix                       |
      | References                     |
      | Article and author information |

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

  Scenario Outline: Download article citations option
    Given user navigates to "Home" page
    When user is on the Home page
    When user navigates to "00170"
    Then "Article page" is displayed
    When user clicks on "Download"
    And user selects "<exportReference>"
    Then a "<exportReference>" file is downloaded
    Examples:
      | exportReference |
      | Article PDF     |
      | BibTeX          |
      | RIS             |

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
