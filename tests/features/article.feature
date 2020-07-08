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
    When user navigates to 'Home' page
    Then a list of articles is displayed
    When user clicks on the first article from the list
    Then 'article' page is displayed
    And following sections are displayed:
      | Abstract                       |
      | Introduction                   |
      | Data and methodology           |
      | Conclusions                    |
      | Appendix                       |
      | References                     |
      | Article and author information |

  Scenario: Article can be downloaded
    Given articles are uploaded
    When user navigates to 'Home' page
    Then a list of articles is displayed
    When user clicks on the first article from the list
    Then 'article' page is displayed
    When user clicks on 'Download' button
    And user selects 'Article PDF'
    Then a PDF file is downloaded


  Scenario:Article's figure and data are displayed
    Given articles are uploaded
    When user navigates to 'Home' page
    Then a list of articles is displayed
    When user clicks on the first article from the list
    Then article is displayed in a new page
    When user clicks on 'Figures and data'
    Then 'Figures' page is displayed
    And following section are displayed:
      | Figures |
      | Tables  |
