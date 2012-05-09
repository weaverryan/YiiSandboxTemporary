Feature: Login Feature
  In order to do blog administration
  As a guest user
  I need to be able to login using username and password


  Scenario:
    Given I am on "/"
    And There is an admin account
    When I follow "Login"
    And I fill in "Username" with "demo"
    And I fill in "Password" with "demo"
    And press "Login"
    Then I should be authenticated

  Scenario: Logout
    Given I am authenticated
    When I click logout link
    Then I should not be authenticated