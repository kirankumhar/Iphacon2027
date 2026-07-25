<?php
return [
    'default' => [
        'length' => 4,
        'width' => 150,             // Increased width for larger text
        'height' => 50,             // Increased height for larger text
        'quality' => 90,
        'math' => false,
        'expire' => 180,
        'encrypt' => false,
        'characters' => ['2', '3', '4', '6', '7', '8', '9', '0', '1'],
        'sensitive' => false,
        'color' => '#2d3748',       // Dark gray for good contrast
        'background' => '#ffffff',   // White background for maximum contrast
        'angle' => 10,              // Slight rotation
        'blur' => 0,                // No blur
        'contrast' => 30,           // Higher contrast
        'lines' => 1,               // Fewer interference lines
        'bgImage' => false,         // No background image
        'bgColor' => '#1a202c',     // Ensure white background
    ],
];