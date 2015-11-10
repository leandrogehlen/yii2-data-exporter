<?php

return [
    "description" => "Persons data export",
    "serializer" => "leandrogehlen\\exporter\\serializers\\ColumnSerializer",
    "sessions" => [
         [
            "name" => "person",
            "provider" => "person-provider",
            "columns" => [
                 ["name" => "type", "size" => 3, "value" => "010" ],
                 ["name" => "firstName", "size" => 10, "complete" => " " ],
                 ["name" => "lastName", "size" => 5, "complete" => " " ],
                 ["name" => "birthDate", "size" => 10, "value" => function($value, $row) {
                     return date_format(date_create($value), 'd/m/Y');
                 }],
                 ["name" => "salary", "dictionary" => "money" ],
                 ["name" => "active", "dictionary" => "boolean" ]
            ]
         ]
    ],
    "providers" => [
         [
            "name" => "person-provider",
            "query" => "select * from person"
         ]
    ],
    "dictionaries" => [
         ["name" => "money", "size" => 8, "complete" => "0", "align" => "right", "value" => function($value) {
             return str_replace('.', '', $value);
         }],
         ["name" => "boolean", "size" => 3, "complete" => " ", "format" => "boolean"]
    ]
];
