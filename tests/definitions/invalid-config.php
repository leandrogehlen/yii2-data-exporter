<?php

return [
    "description" => "Persons data export",
    "serializer" => "leandrogehlen\\exporter\\serializers\\ColumnSerializer",
    "sessions" => [
        [
            "name" => "person",
            "providerName" => "invalid-provider",
            "columns" => [
                ["name" => "type", "size" => 3, "value" => "010"],
                ["name" => "firstName", "size" => 10, "charComplete" => " "],
                ["name" => "salary", "dictionaryName" => "invalid-dictionary"],
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
        ["name" => "money", "size" => 8, "charComplete" => "0", "align" => "right"]
    ]
];