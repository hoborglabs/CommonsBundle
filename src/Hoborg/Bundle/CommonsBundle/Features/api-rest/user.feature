Feature: Test usre identity REST API

  Background:
    Given a identity user exist:
      | Login | First Name | Last Name | Password |
      | test  | Test       | Ipsum     | lorem    |


  Scenario: Use REST api to get user's public data.
    Given I am not logged in
    When I use REST API to request user "test"
    Then I should get the following user
      | Login | First Name | Last Name |
      | test  | Test       | Ipsum     |
    But I should not get "logout" field
    When I use REST API to logout user "test"
    Then I should get "failure" response


  Scenario: Use REST API to login with correct credentials.
    Given I am not logged in
    When I use REST API to login with "test" and "lorem"
    Then I should get the following user
      | Login | First Name | Last Name |
      | test  | Test       | Ipsum     |
    When I use REST API to logout user "test"
    Then I should get "success" response


  Scenario: Use REST API to login with incorrect credentials.
    Given I am not logged in
    When I use REST API to login with "test" and "wrong password"
    Then I should get "empty" response
    When I use REST API to logout user "test"
    Then I should get "failure" response
