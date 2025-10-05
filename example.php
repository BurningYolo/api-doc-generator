<?php

require 'vendor/autoload.php';

use ApiDocGenerator\ApiDocGenerator;
use ApiDocGenerator\Route;

// Initialize the generator
$generator = new ApiDocGenerator();

// Set API information
$generator->setTitle('My Awesome API')
          ->setDescription('This is a comprehensive API for managing users, posts, and comments.')
          ->setVersion('2.0.0')
          ->setBaseUrl('https://api.example.com/v2');

// Example 1: Simple GET route
$getUsersRoute = new Route('GET', '/users');
$getUsersRoute->setTitle('Get All Users')
              ->setDescription('Retrieve a paginated list of all users in the system.')
              ->addQueryParam('page', 'integer', false, 'Page number for pagination', 1)
              ->addQueryParam('limit', 'integer', false, 'Number of items per page', 10)
              ->addQueryParam('sort', 'string', false, 'Sort field (name, email, created_at)', 'created_at')
              ->addHeader('Accept', 'string', true, 'application/json')
              ->addResponse(200, 'Success', [
                  'data' => [
                      [
                          'id' => 1,
                          'name' => 'John Doe',
                          'email' => 'john@example.com',
                          'created_at' => '2024-01-15T10:30:00Z'
                      ]
                  ],
                  'meta' => [
                      'page' => 1,
                      'limit' => 10,
                      'total' => 150
                  ]
              ])
              ->addResponse(500, 'Internal Server Error', [
                  'error' => 'An unexpected error occurred'
              ]);

// Example 2: GET route with path parameter
$getUserRoute = new Route('GET', '/users/{id}');
$getUserRoute->setTitle('Get User by ID')
             ->setDescription('Retrieve detailed information about a specific user.')
             ->addPathParam('id', 'integer', 'The unique identifier of the user')
             ->addHeader('Accept', 'string', true, 'application/json')
             ->addResponse(200, 'Success', [
                 'id' => 1,
                 'name' => 'John Doe',
                 'email' => 'john@example.com',
                 'bio' => 'Software developer',
                 'created_at' => '2024-01-15T10:30:00Z'
             ])
             ->addResponse(404, 'User Not Found', [
                 'error' => 'User not found'
             ]);

// Example 3: POST route with authentication and body
$createUserRoute = new Route('POST', '/users');
$createUserRoute->setTitle('Create New User')
                ->setDescription('Create a new user account in the system.')
                ->requireAuth('Bearer', 'Admin access token required')
                ->addHeader('Content-Type', 'string', true, 'application/json')
                ->addBodyParam('name', 'string', true, 'Full name of the user', 'John Doe')
                ->addBodyParam('email', 'string', true, 'Email address (must be unique)', 'john@example.com')
                ->addBodyParam('password', 'string', true, 'Password (min 8 characters)', 'SecurePass123!')
                ->addBodyParam('role', 'string', false, 'User role (admin, user)', 'user')
                ->addResponse(201, 'User Created Successfully', [
                    'id' => 123,
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'role' => 'user',
                    'created_at' => '2024-01-15T10:30:00Z'
                ])
                ->addResponse(400, 'Validation Error', [
                    'error' => 'Validation failed',
                    'details' => [
                        'email' => ['The email has already been taken.']
                    ]
                ])
                ->addResponse(401, 'Unauthorized', [
                    'error' => 'Invalid or missing authentication token'
                ]);

// Example 4: PUT route for updating
$updateUserRoute = new Route('PUT', '/users/{id}');
$updateUserRoute->setTitle('Update User')
                ->setDescription('Update an existing user\'s information.')
                ->requireAuth('Bearer', 'User must be authenticated and authorized')
                ->addPathParam('id', 'integer', 'User ID to update')
                ->addHeader('Content-Type', 'string', true, 'application/json')
                ->addBodyParam('name', 'string', false, 'Updated full name', 'Jane Doe')
                ->addBodyParam('email', 'string', false, 'Updated email address', 'jane@example.com')
                ->addBodyParam('bio', 'string', false, 'User biography', 'Updated bio text')
                ->addResponse(200, 'User Updated Successfully', [
                    'id' => 123,
                    'name' => 'Jane Doe',
                    'email' => 'jane@example.com',
                    'bio' => 'Updated bio text',
                    'updated_at' => '2024-01-15T11:30:00Z'
                ])
                ->addResponse(403, 'Forbidden', [
                    'error' => 'You do not have permission to update this user'
                ])
                ->addResponse(404, 'Not Found', [
                    'error' => 'User not found'
                ]);

// Example 5: DELETE route
$deleteUserRoute = new Route('DELETE', '/users/{id}');
$deleteUserRoute->setTitle('Delete User')
                ->setDescription('Permanently delete a user from the system.')
                ->requireAuth('Bearer', 'Admin access token required')
                ->addPathParam('id', 'integer', 'User ID to delete')
                ->addResponse(204, 'User Deleted Successfully (No Content)', null)
                ->addResponse(403, 'Forbidden', [
                    'error' => 'Only admins can delete users'
                ])
                ->addResponse(404, 'Not Found', [
                    'error' => 'User not found'
                ]);

// Add all routes to the generator
$generator->addRoute($getUsersRoute)
          ->addRoute($getUserRoute)
          ->addRoute($createUserRoute)
          ->addRoute($updateUserRoute)
          ->addRoute($deleteUserRoute);

// Generate the documentation
$outputPath = 'docs/api-documentation.md';
if ($generator->generate($outputPath)) {
    echo "API documentation generated successfully at: {$outputPath}\n";
} else {
    echo "Failed to generate API documentation.\n";
}