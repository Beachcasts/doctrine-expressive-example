Feature: List of announcements
  Scenario: I want a list of announcements
    Given I am an unauthenticated user
    When I request a list of announcements from "http://localhost:8080"
    Then The results should include an announcement with ID "5954ddbb-cb01-4bd6-8062-e1710c422f32"