<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => true,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'administrator' => [
            'users' => 'c,r,u,d',
            'blog-posts' => 'c,r,u,d',
            'profile' => 'r,u',
            'chat' => 'c,r,d',
            'pro-situations' => 'c,r,u,d',
            'profiles' => 'c,r,u,d',
            'disciplinary-boards' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
            'vacation-types' => 'c,r,u,d',
            'vacations' => 'c,r,u,d',
            'license-types' => 'c,r,u,d',
            'licenses' => 'c,r,u,d',
            'templates' => 'c,r,u,d',
            'note-criterias' => 'c,r,u,d',
            'document-viewer' => 'r',
            'assignment-types' => 'c,r,u,d',
            'assignments' => 'c,r,u,d',
            'settings' => 'c,r,u,d',
            'divisions' => 'c,r,u,d',
            'submissions' => 'c,r,u,d',
            'statistiques' => 'r',
            'contacts' => 'c,r,u,d',
            'trainings' => 'c,r,u,d',
            'disciplinary-teams' => 'c,r,u,d',
            'blog-categories' => 'c,r,u,d',
            'contracts' => 'c,r,u,d',
            'sanctions' => 'c,r,u,d',
            'careers' => 'c,r,u,d',
        ],
        'user' => [
            'profile' => 'r,u',
        ],
        'blogger' => [
            'profile' => 'r,u',
            'blog-posts' => 'c',
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
