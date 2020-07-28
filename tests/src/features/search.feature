@search
Feature: Search page

  Rules:
  - All content types are searchable
  - Search results are loaded in batches of 10
  - Searches are ordered by most relevant first by default
  - If the ordering can't separate two or more search results, fallback ordering is publication date (most recent first) then title (A first).

   # Background:
   # Given there is 44 research article about 'Demography'
   # And user is on the 'Search' page

@Ci
  Scenario Outline: List shows 10 most relevant results
    Given user navigates to "Home" page
    When user is on the Home page
    And user clicks on "Search button"
    And user searches for "<searchWord>"
    Then "<searchPage>" is displayed
    Examples:
      | searchWord                 | searchPage                      |
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

  @Ci
  Scenario Outline: Search by research category
    Given user navigates to "Home" page
    When user is on the Home page
    And user clicks on "Search button"
    And user searches for "Demography"
    Then "Search page" is displayed
    And a list of 10 articles is displayed
    When user selects "<researchCategory>" checkbox
    Then "<researchCategoriesResults>" is displayed
#    And number of results found is displayed
    Examples:
      | researchCategoriesResults  | researchCategory                |
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


  @javascript
  Scenario: Loading more adds previous 10 to the list
    When user searches for 'demography'
    And user clicks on 'load more' results
    Then user should see the 20 most relevant results for 'demography'




