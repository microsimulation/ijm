@subject
Feature: Subjects page

  Rules:
  - All subjects are shown in alphabetical order

  Scenario: List shows all subjects
    Given there are 20 subjects
    When I go the Subjects page
    Then I should see the 20 subjects.

@Ci
  Scenario Outline: Access research subjects
    Given user navigates to "Home" page
    When user is on the Home page
    And user navigates to subject "<subjectName>"
    Then "<pageName>" page is displayed
    Examples:
      | subjectName                | pageName                        |
      | consumption-savings-wealth | Consumption, savings and wealth |
      | demography                 | Demography                      |
      | dynamic-microsimulation    | Dynamic microsimulation         |
      | education                  | Education                       |
      | environment                | Environment                     |
      | finance                    | Finance                         |
      | firm-behaviour             | Firm behaviour                  |
      | health                     | Health                          |
      | housing                    | Housing                         |
      | innovation                 | Innovation                      |
      | labour-supply-demand       | Labour supply and demand        |
      | methodology                | Methodology                     |
      | micro-macro-linkage        | Micro-macro linkage             |
      | miscellaneous              | Miscellaneous                   |
      | pensions-retirement        | Pensions and retirement         |
      | spatial-microsimulation    | Spatial microsimulation         |
      | taxes-benefits             | Taxes and benefits              |
      | trade                      | Trade                           |
      | transport                  | Transport                       |
      | institutions-incentives    | Institutions and incentives     |