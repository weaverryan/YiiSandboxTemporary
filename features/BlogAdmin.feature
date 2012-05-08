Feature: Blog Administration
	In order to create and manage posts
	As a blog administrator
	I need to be able to

	Background:
    	Given I am authenticated

	Scenario: Create Post
		Given I am on "/"
			And I follow "Create New Post"
	    When I fill in "Title" with "Test Post"
			And I fill in "Content" with "Test Content"
			And I fill in "Tags" with "TestTag"
			And I select "Published" from "Status"
			And I press "Create"
		Then I should be on the "Test Post" blog post page
			And I should see "Test Content"
