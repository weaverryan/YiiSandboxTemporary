Feature: In order to prove that our Behat beta is working
    As a user of this repository
    I need to be able to test out the integration of this repo

    Scenario: Go to the homepage
        When I go to "/"
        Then the response status code should be 200
        And I should see "Yii Blog Enhanced"