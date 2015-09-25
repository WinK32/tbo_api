<?php

class TBO
{
    private $xml;
    public function __construct(){
        $this->xml = new DOMDocument("1.0", "UTF-8");
    }


    private function recursion($key,$value,&$xml_elem)
    {
        $attr = (isset($value['attr'])) ? $value['attr'] : null;
        $value['value'] = (isset($value['value'])) ? $value['value'] : '';
        if (is_array($value['value'])) {
            $xml_bdyreqele = $this->xml->createElement("hot:$key");
            if ($attr) {
                foreach ($attr as $k => $v) {
                    $xml_bdyreqele->setAttribute($k, $v);
                }
            }
            foreach ($value['value'] as $key2 => $value2) {
                $this->recursion($key2,$value2,$xml_bdyreqele);
            }
            $xml_elem->appendChild($xml_bdyreqele);
        } else {
            $xml_bdyreqele = $this->xml->createElement("hot:$key", $value['value']);
            if ($attr) {
                foreach ($attr as $k => $v) {
                    $xml_bdyreqele->setAttribute($k, $v);
                }
            }
            $xml_elem->appendChild($xml_bdyreqele);
        }
    }
    private function loadRequest($action,$arr_value)
    {
        $xml_env = $this->xml->createElement("soap:Envelope");
        $xml_env->setAttribute("xmlns:soap", "http://www.w3.org/2003/05/soap-envelope");
        $xml_env->setAttribute("xmlns:hot", "http://TekTravel/HotelBookingApi");

        /*create header*/
        $xml_hed = $this->xml->createElement("soap:Header");
        $xml_hed->setAttribute("xmlns:wsa", "http://www.w3.org/2005/08/addressing");

        $xml_cred = $this->xml->createElement("hot:Credentials");
        $xml_cred->setAttribute("UserName", "behtarin");
        $xml_cred->setAttribute("Password", "beht@123");

        $xml_wsaa = $this->xml->createElement("wsa:Action", "http://TekTravel/HotelBookingApi/$action");
        $xml_wsat = $this->xml->createElement("wsa:To", "http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc");

        $xml_hed->appendChild($xml_cred);
        $xml_hed->appendChild($xml_wsaa);
        $xml_hed->appendChild($xml_wsat);

        $xml_env->appendChild($xml_hed);

        /*create body*/
        $xml_bdy = $this->xml->createElement("soap:Body");
        $xml_bdyreq= $this->xml->createElement("hot:$action"."Request");


        foreach ($arr_value as $key => $value ) {
            $this->recursion($key,$value,$xml_bdyreq);
        }


        $xml_bdy->appendChild($xml_bdyreq);
        $xml_env->appendChild($xml_bdy);

        $this->xml->appendChild($xml_env);
        $request = $this->xml->saveXML();
        $location = "http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc";
        $action = "http://TekTravel/HotelBookingApi/$action";
        $client = new SoapClient("http://api.tbotechnology.in/hotelapi_v7/hotelservice.svc?wsdl");
        return $client->__doRequest($request, $location, $action, 2);


    }

    private function templateMethod($function,$tag_name,$arg=[]){
        $this->xml->loadXML($this->loadRequest($function,$arg));
        $xml_res = $this->xml->getElementsByTagName($tag_name);
        for($i = 0; $i < $xml_res->length; $i++) {
            $output = $xml_res->item($i)->attributes;
            for($k = 0; $k < $output->length; $k++){
                $result[$i][$output->item($k)->name] = $output->item($k)->value;
            }
        }
        return $result;
    }


    public function  DestinationCityList($arg){
        return $this->templateMethod(__FUNCTION__,'City',$arg);
    }
    public function  TopDestinations(){
        return $this->templateMethod(__FUNCTION__,'City');
    }
    public function  CountryList(){
        return $this->templateMethod(__FUNCTION__,'Country');
    }

    /**
     * @return mixed
     * Attention!!!!!!!!!!!!
     */
    public function  HotelCodeList(){
        return $this->templateMethod(__FUNCTION__,'Hotel');
    }
    public function  HotelSearch($arg){
       return($this->loadRequest(__FUNCTION__,$arg));

    }


}
$new = new TBO();
//$inp_arr = ["CountryCode"=>["value"=>["value"=>'AE',"attr"=>["vu"=>'ddd']]]];
//print_r($new->DestinationCityList($inp_arr));
//print_r($new->TopDestinations());
//print_r($new->CountryList());
///****************print_r($new->HotelCodeList());
$inp_arr = [
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
        "value" => 0
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

print_r($new->HotelSearch($inp_arr));



