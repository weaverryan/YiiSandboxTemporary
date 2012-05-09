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
			And I fill in "Content" with
			"""
			Line 1
			Line 2
			Line 3
			"""
			And I fill in "Tags" with "TestTag"
			And I select "Published" from "Status"
			And I press "Create"
		Then I should be on the "Test Post" blog post page
			And I should see "Line 1"

	Scenario: Edit Post
		Given there is a post titled "Post to Edit"
			And I am on "/"
			And I follow "Manage Posts"
		When I follow the "Update" link for post "Post to Edit"
			And I fill in "Title" with "Edited Post"
			And I press "Save"
		Then I should be on the "Edited Post" blog post page
		