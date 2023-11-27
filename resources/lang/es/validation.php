<?php

return [
    'required' => 'El :attribute es requerido.',
    'email' => 'El :attribute debe ser una dirección de correo electrónico válida.',
    'boolean' => 'El :attribute debe ser un valor booleano (0 o 1).',
    'numeric' => 'El :attribute debe ser un numero valido.',
    'custom' => [
        'docente_id' => [
            'exists' => 'El docente seleccionado no existe.',
        ],
        'alumno_id' => [
            'exists' => 'El alumno seleccionado no existe.',
        ],
        'curso_id' => [
            'exists' => 'El curso seleccionado no existe.',
        ],
        'abreviacion' => [
            'in' => 'La abreviacion debe ser dentro de la indicadas (A,T,F).',
        ],
        
    ],
];
