<?php

return [
    /**
     * API CONSTANTS
     */
   'api' => [
       'limit' => 10,
       'secret' => 'PhysicianPatient',
       'date_format' => 'd/m/Y',
       'no_data_found' => 'No data found',
       'error' => [
           'login' => [
                'validation' => 'Login validation error.',
                'approved' => 'Admin not approved your account, Activate within 24 hours!'
           ],
           'register' => [
               'validation' => 'Register validation error.'
           ],
           'user' => [
               'approved' => 'User approved failure.',
               'approved_validation' => 'User required params validation error.'
           ],
           'specialists' => [
                'validation' => 'Specialist required params validation error',
                'create' => 'Specialists created failure!',
                'update' => 'Specialists updated failure!',
            ]
       ],
       'success' => [
           'register' => [
               'create' => 'User created successfully!',
               'update' => 'User updated successfully!',
           ],
           'user' => [
            'get' => 'Retrieved a user.',
            'get_all' => 'Retrieved all users.',
            'delete' => 'User deleted successfully!',
            'approved' => 'User approve updated.'
           ],
           'login' => [
               'valid' => 'Login successful'
           ],
           'specialists' => [
                'create' => 'Specialists created successfully!',
                'update' => 'Specialists updated successfully!',
                'get_all' => 'Retrieved all specialists'
           ]
       ]
   ]
];