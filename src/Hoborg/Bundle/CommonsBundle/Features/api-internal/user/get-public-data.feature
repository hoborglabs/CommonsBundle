Feature: Getting user's public data using identity internal API.

  Background:
    Given a identity user exist:
      | Login | First Name | Last Name | Password |
      | test  | Test       | Ipsum     | lorem    |


  Scenario: Use Internal api to get user's public data.
    Given I am not logged in
    When I use Internal API to request user "test"
    Then I should get the following user
      | Login | First Name | Last Name |
      | test  | Test       | Ipsum     |
    But I should not get "logout" field
    And I should not get "token" field


  Scenario: Use Internal API to login with correct credentials.
    Given I am not logged in
    When I use Internal API to login with "test" and "lorem"
    Then I should get the following user
      | Login | First Name | Last Name |
      | test  | Test       | Ipsum     |
    When I use Internal API to request user "test"
    Then I should get the following user
      | Login | First Name | Last Name |
      | test  | Test       | Ipsum     |
    But I should not get "logout" field
    And I should not get "token" field


  Scenario: Use Internal API to login with incorrect credentials.
    Given I am not logged in
    When I use Internal API to request user "%t%"
    Then I should get "empty" response
