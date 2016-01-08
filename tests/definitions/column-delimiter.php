<?php

return [
    "description" => "Orders data export",
    "serializer" => [
        "class" => "leandrogehlen\\exporter\\serializers\\ColumnSerializer",
        "delimiter" => "|",
    ],
    "sessions" => [
        [
            "name" => "order",
            "provider" => "order-provider",
            "columns" => [
                ["name" => "type", "value" => "010"],
                ["name" => "number"],
                ["name" => "created_at", "value" => function ($value, $row) {
                    return date_format(date_create($value), 'Y-m-d');
                }],
                ["name" => "firstName"],
                ["name" => "description", "value" => function ($value, $row) {
                    return $value . ' - ' . $row['salary'];
                }],
            ],
            "sessions" => [
                [
                    "name"  => "details",
                    "provider" => "detail-provider",
                    "columns" => [
                        ["name" => "type", "value" => "020"],
                        ["name" => "product_id"],
                        ["name" => "quantity"],
                        ["name" => "price"],
                        ["name" => "total"]
                    ]
                ]
            ]
        ]
    ],
    "providers" => [
        [
            "name" => "order-provider",
            "query" => "select invoice.*, person.firstName, person.salary from invoice join person on (person.id = invoice.person_id) where invoice.created_at = :created_at and person.active = :active"
        ],[
            "name" => "detail-provider",
            "query" => "select * from invoice_details where invoice_id = :id"
        ]
    ],
    "parameters" => [
        [
            "name" => "created_at",
            "label" => "Created At",
            "value" => function() { return date('Y-m-d'); }
        ],
        [
            "name" => "active",
            "label" => "Active",
            "value" => 1
        ]
    ],
    "events" => [
        [
            "name" => "beforeSerializeRow",
            "expression" => function ($data, $session) {
                return '|' . $data . '|';
            }
        ]
    ]
];
