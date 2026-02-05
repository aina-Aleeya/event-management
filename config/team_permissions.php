<?php

return [
    /**
     * All available permissions in the system
     */
    'all_permissions' => [
        'create_event' => [
            'label' => 'Create Event',
            'description' => 'Can create new events',
            'group' => 'Event Management',
        ],
        'view_participants' => [
            'label' => 'View Participants',
            'description' => 'View participant list and details',
            'group' => 'Participant Management',
        ],
    'group_participants' => [
            'label' => 'Group Participants',
            'description' => 'Create groups and assign participants to groups',
            'group' => 'Grouping System',
        ],
        'manage_scoresheet' => [
            'label' => 'Manage Scoresheet',
            'description' => 'View, export, and manage scoresheets',
            'group' => 'Scoring',
        ],
        'submit_scores' => [
            'label' => 'Submit Scores',
            'description' => 'Enter and update participant scores',
            'group' => 'Scoring',
        ],
        'manage_certificates' => [
            'label' => 'Manage Certificates',
            'description' => 'Generate, download, and manage certificates',
            'group' => 'Certificate Management',
        ],
    ],

    /**
     * Default permissions for system roles
     */
    'role_permissions' => [
        'administrator' => [
            'create_event',
            'group_participants',
            'manage_scoresheet',
            'submit_scores',
            'manage_certificates',
            'view_participants',
        ],
        'judge' => [
            'view_participants',
            'manage_scoresheet',
            'submit_scores',
            'group_participants',
        ],
        'coordinator' => [
            'view_participants',
            'group_participants',
            'manage_certificates',
        ],
        'technical-support' => [
            'view_participants',
            'group_participants',
            'manage_scoresheet',
        ],
    ],
];