Feature: Test identity login/logout (internal)

  Background:
    Given a identity user exist:
      | Login | First Name | Last Name | Password |
      | test  | Test       | Ipsum     | lorem    |


  Scenario: Use Internal API to login with correct credentials.
    Given I am not logged in
    When I use Internal API to login with "test" and "lorem"
    Then I should get the following user
      | Login | First Name | Last Name |
      | test  | Test       | Ipsum     |
    When I use Internal API to logout "test"
    Then I should get "success" response


  Scenario: Use Internal API to login with incorrect password.
    Given I am not logged in
    When I use Internal API to login with "test" and "wrong password"
    Then I should get "empty" response


  Scenario: Use Internal API to login with incorrect login and existing password.
    Given I am not logged in
    When I use Internal API to login with " test " and "lorem"
    Then I should get "empty" response
