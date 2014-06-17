Feature: Visit Google and search random stuff

@javascript
Scenario: Run a search for Behat
    Given I am on "http://google.com/?complete=0"
    When I fill in "lst-ib" with "Behat"
    And I press "Szukaj w Google"
    And wait 2 second
    Then I should see "BDD for PHP"

@javascript
Scenario: Run a search for Uriziel
    Given I am on "http://google.com/?complete=0"
    When I fill in "lst-ib" with "Uriziel01pl"
    And I press "Szukaj w Google"
    And wait 2 second
    Then I should see "Paweł Borecki"