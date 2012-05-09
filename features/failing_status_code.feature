Feature: In order to show our custom assert in action
    As a user of this repository
    I need to be able to show that we can get "close" on our status code

    Scenario: Go to the homepage
        When I go to "/"
        # this fails, but with a different message
        Then the response status code should be 201