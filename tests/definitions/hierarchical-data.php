<?php

use yii\db\Query;

return [
    "description" => "Orders data export",
    "sessions" => [
        [
            "name" => "invoices",
            "provider" => "invoice-provider",
            "columns" => [
                 ["name" => "type", "dictionary" => "type"],
                 ["name" => "number" ],
                 ["name" => "created_at", "value" => function ($value, $row) {
                     return date_format(date_create($value), 'Y-m-d');
                 }],
                 ["name" => "person"],
                 ["name" => "description"]
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
                 ],[
                    "name" => "person",
                    "provider" => "invoice-provider",
                    "columns" => [
                        ["name" => "firstName"],
                        ["name" => "lastName"],
                    ]
                ]
            ]
        ],[
            "name" => "persons",
            "provider" => "person-provider",
            "columns" => [
                ["name" => "firstName"],
                ["name" => "lastName"]
            ],
        ]
    ],
    "providers" => [
         [
            "name" => "invoice-provider",
            "query" => (new Query())
                ->select([
                    'invoice.*',
                    'person.firstName',
                    'person.lastName'
                ])
                ->from('invoice')
                ->innerJoin('person', 'person.id = invoice.person_id')
                ->where(['person.active' => true])
         ],[
            "name" => "detail-provider",
            "query" => (new Query())
                ->from('invoice_details')
                ->where('invoice_id = :id')
         ],[
            "name" => "person-provider",
            "query" => "select * from person"
        ]
    ],
    "dictionaries" => [
        ["name" => "type", "value" => "100"]
    ],
    "events" => [
        [
            "name" => "beforeSerializeRow",
            "expression" => function ($data, $session) {
                if ($session->name == 'invoices') {
                    $data['eventColumn'] = "event";
                }
                return $data;
            }
        ]
    ]
];
