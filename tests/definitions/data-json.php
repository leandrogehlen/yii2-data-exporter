<?php

return [
    "description" => "Orders data export",
    "serializer" => "leandrogehlen\\exporter\\data\\serializers\\JsonSerializer",
    "sessions" => [
         [
            "name" => "invoice",
            "providerName" => "invoice-provider",
            "columns" => [
                 ["name" => "type", "value" => "010", "size" => "false" ],
                 ["name" => "number", "size" => "false" ],
                 ["name" => "created_at", "size" => "false", "expression" => function ($value) {return date_format(date_create($value), 'Y-m-d');}],
                 ["name" => "person", "size" => "false" ],
                 ["name" => "description", "size" => "false"]
            ],
            "sessions" => [
                 [
                    "name"  => "details",
                    "providerName" => "detail-provider",
                    "columns" => [
                         ["name" => "type", "value" => "020", "size" => "false" ],
                         ["name" => "product_id", "size" => "false" ],
                         ["name" => "quantity", "size" => "false" ],
                         ["name" => "price", "size" => "false" ],
                         ["name" => "total", "size" => "false" ]
                    ]
                 ]
            ]
         ]
    ],
    "providers" => [
         [
            "name" => "invoice-provider",
            "query" => "select invoice.*, person.firstName as person from invoice join person on (person.id = invoice.person_id)"
         ], [
            "name" => "detail-provider",
            "query" => "select * from invoice_details where invoice_id = :id"
         ]
    ]
];