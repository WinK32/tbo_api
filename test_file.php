<?php
require_once('TBO.php');

$new = new TBO();
$inp_arr = [
    "CountryCode"=>[
        "value"=>[
            "AE"
        ]
    ]
];
$inp_arr_hotel = [
    "CheckInDate"=>[
        "value"=>"2015-10-25T00:00:00.000+05:00"
    ],
    "CheckOutDate"=>[
        "value"=>"2015-10-26T00:00:00.000+05:00"
    ],
    "CountryName"=>[
        "value"=>"United Arab Emirates"
    ],
    "CityName"=>[
        "value"=>"Dubai"
    ],
    "CityId"=>[
        "value"=>"25921"
    ],
    "IsNearBySearchAllowed"=>[
        "value"=>'false'
    ],
    "NoOfRooms"=>[
        "value"=>1
    ],
    "GuestNationality"=>[
        "value"=>"IN"
    ],
    "RoomGuests"=>[
        "value"=>[
            "RoomGuest"=>[
                "attr"=>[
                    "AdultCount"=>1,
                    "ChildCount"=> 0
                ]
            ]
        ]
    ],
    "ResultCount" => [
        "value" => 1
    ],
    "Filters" => [
        "value" => [
            "StarRating" =>[
                "value"=>"All"
            ],
            "OrderBy" =>[
                "value"=>"PriceAsc"
            ]
        ]
    ]
];
$inp_arr_avail = [
    "SessionId"=>[
        "value"=>"0041766b-0109-4dbb-b3e9-d8954530d1f8"
    ],
    "ResultIndex"=>[
        "value"=>"2"
    ],
    "HotelCode"=>[
        "value"=>"306225"
    ]
];
//print_r($new->DestinationCityList($inp_arr));
//print_r($new->TopDestinations());
//print_r($new->CountryList());
//print_r($new->HotelSearch($inp_arr_hotel));
//print_r($new->HotelRoomAvailability($inp_arr_avail));
echo '----Timer:'.(($start-time())).'sec------';