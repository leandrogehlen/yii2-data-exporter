<?php

return [
    "description" => "Persons data export",
    "serializer" => "leandrogehlen\\exporter\\data\\serializers\\ColumnSerializer",
    "sessions" => [
         [
            "name" => "person",
            "providerName" => "person-provider",
            "columns" => [
                 ["name" => "recordType", "size" => 3, "value" => "010" ],
                 ["name" => "firstName", "size" => 10, "charComplete" => " " ],
                 ["name" => "lastName", "size" => 5, "charComplete" => " " ],
                 ["name" => "birthDate", "size" => 10, "expression" => function($value) {return date_format(date_create($value), 'd/m/Y');}],
                 ["name" => "salary", "dictionaryName" => "money" ],
                 ["name" => "active", "dictionaryName" => "boolean" ]
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
         ["name" => "money", "size" => 8, "charComplete" => "0", "align" => "right", "expression" => function($value) {return str_replace('.', '', $value);}],
         ["name" => "boolean", "size" => 3, "charComplete" => " ", "format" => "boolean" ]
    ]
];