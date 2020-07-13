@subject
Feature: Subjects page

  Rules:
  - All subjects are shown in alphabetical order

  Scenario: List shows all subjects
    Given there are 20 subjects
    When I go the Subjects page
    Then I should see the 20 subjects.


  Scenario: All research categories are displayed
    Given user is on the 'Home' page
    When user clicks on 'All research categories'
    Then the following list of categories is displayed:
      | Consumption, savings and wealth |
      | Demography                      |
      | Dynamic microsimulation         |
      | Education                       |
      | Environment                     |
      | Finance                         |
      | Firm behaviour                  |
      | Health                          |
      | Housing                         |
      | Innovation                      |
      | Labour supply and demand        |
      | Methodology                     |
      | Micro-macro linkage             |
      | Miscellaneous                   |
      | Pensions and retirement         |
      | Spatial microsimulation         |
      | Taxes and benefits              |
      | Trade                           |
      | Transport                       |