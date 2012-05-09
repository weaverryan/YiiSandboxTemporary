Feature: In order to show the new iframe switching features
    As a user of this repository
    I need to be open up a page with iframes and play around

    # Iframe switching only works in selenium and selenium2
    # This is due to some majorly broken API's in Sahi :/
    # https://github.com/Behat/Mink/commit/ab7ed3d9aeb9a1a5554a9582262881c8fc8531a0
    @mink:selenium2
    Scenario: Go to the Mink API Docs and play
        When I go to "http://mink.behat.org/api/"
            And I should not see "Namespaces"
            And I switch to the namespaces frame
            And I should see "Namespaces"
            And I follow "Behat\Mink"
            And I switch to the navigation frame
            And I follow "Session"
            And I switch to the main frame
        Then I should see "Behat\Mink\Session"
