<?php

return [
    "description" => "Orders data export",
    "sessions" => [
         [
            "name" => "invoices",
            "providerName" => "invoice-provider",
            "columns" => [
                 ["name" => "type", "dictionaryName" => "type"],
                 ["name" => "number" ],
                 ["name" => "created_at", "value" => function ($value) {
                     return date_format(date_create($value), 'Y-m-d');
                 }],
                 ["name" => "person"],
                 ["name" => "description"]
            ],
            "sessions" => [
                 [
                    "name"  => "details",
                    "providerName" => "detail-provider",
                    "columns" => [
                         ["name" => "type", "value" => "020"],
                         ["name" => "product_id"],
                         ["name" => "quantity"],
                         ["name" => "price"],
                         ["name" => "total"]
                    ]
                 ],[
                    "name" => "person",
                    "providerName" => "invoice-provider",
                    "columns" => [
                        ["name" => "firstName"],
                        ["name" => "lastName"],
                    ]
                ]
            ]
         ]
    ],
    "providers" => [
         [
            "name" => "invoice-provider",
            "query" => "select invoice.*, person.firstName, person.lastName from invoice join person on (person.id = invoice.person_id)"
         ], [
            "name" => "detail-provider",
            "query" => "select * from invoice_details where invoice_id = :id"
         ]
    ],
    "dictionaries" => [
        ["name" => "type", "value" => "100"]
    ]
];