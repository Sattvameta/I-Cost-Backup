<?php

return [  
    'REGIONS' => [
        ''=> 'Select Region',
        'South East'=>'South East',
        'London' => 'London', /* will change it after marketplace categories */
        'South West'=>'South West',
        'Midlands' => "Midlands", /* will change it after marketplace categories */
         'North East' => 'North East', /* will change it after marketplace categories */
        'Scotland' => "Scotland", /* will change it after marketplace categories */
    ],
    'CONTRACT_TYPES' => [
        'NEC Contract' => 'NEC Contract', /* will change it after marketplace categories */ 
        'NEC Contract1' => 'NEC Contract1',
    ],
    'SHIFTS' => [
        ''=> 'Select shift',
        'Day' => 'Day', /* will change it after marketplace categories */ 
        'Night' => 'Night',
    ],
    'email_template_types' => [
        'account_confirmation' => 'Account Comfirmation',
    ],
    'TENDER_STATUS' => [
        1 => 'Live',
        2 => 'Tender',
        0 => 'Dead'
        
    ],
    'licence_types' => [
        "Security Licence" => "Security Licence",
        "Security Licence Licensed" => "Security Licence for Licensed Venues (note: This will warn if there isn't a First Aid and RSA)",
        "Crowd Control Licence" => "Crowd Control Licence",
        "RSA" => "RSA",
        "RCG" => "RCG",
        "RSA/RCG" => "RSA/RCG",
        "WWCC" => "WWCC",
        "Drivers Licence" => "Drivers Licence (please note the class, state &amp; number)",
        "Crim Check" => "Crim Check",
        "PART" => "PART Training",
        "First Aid" => "First Aid",
        "CPR" => "CPR",
        "Traffic Controller" => "Traffic Controller",
        "Child Safe Certificate" => "Child Safe Certificate",
        "Kanangra CRC" => "Kanangra CRC",
        "Induction" => "Induction",
        "Other" => "Other"
    ],

    'minor_incidents' => [
        "1" => "Refuse Entry",
        "2" => "Refuse Service",
        "3" => "Theft",
        "4" => "Malicious Complaint",
        "5" => "Complaint",
        "6" => "Minors",
        "7" => "Self Exclusion",
        "8" => "Gaming",
        "9" => "Approaching Intoxicated",
        "10" => "Suspect Intoxicated",
        "11" => "Inappropriate Behaviour",
        "12" => "Confiscated Item (detail item in Summary)",
    ],
    'serious_incidents' => [
        "1" => "Violence - Brawl/Affray",
        "2" => "Violence - Glassing",
        "3" => "Anti-Social Behaviour",
        "4" => "Asked to Leave",
        "5" => "Injury/Medical Assistance",
        "6" => "Suspected Drug Use    ",
        "7" => "Behaving Irrationally",
        "8" => "Prohibited Item (detail item in Summary)",
        "9" => "Failure to Comply",
    ],
    'actions' => [
        "1" => "Patron Asked to Leave",
        "2" => "Patron refused entry",
        "3" => "Patron refused service",
        "4" => "First Aid Treatment Supplied",
        "5" => "Ambulance Attended",
        "6" => "Security Attended",
        "7" => "Police Called by Venue Staff",
        "8" => "Police Involved",
        "9" => "Fail to Quit Notice Issued",
        "10" => "Crime Scene Preserved",
        "11" => "Police/Regulatory Inspection",
        "12" => "Other",
    ],

    'DOCUMENT_TYPE' => [
        "HR" => "HR",
        "Clinical" => "Clinical",
        "ESS Operations" => "ESS Operations",
        "Security Operations" => "Security Operations",
    ],
    'DOCUMENT_SUB_TYPE' => [
        "Organisational" => "Organisational",
        "Location" => "Location",
        "Participant specific" => "Participant specific",
    ],
    'NOTE_TYPE' => [
        "0" => ["title"=>"Asleep",          "color"=>"#ffffff", "border"=>"#cccccc", "textColor"=>"#000000"],
        "1" => ["title"=>"Happy",           "color"=>"#f029e0", "border"=>"#f029e0", "textColor"=>"#ffffff"],
        "2" => ["title"=>"Calm",            "color"=>"#2cd253", "border"=>"#2cd253", "textColor"=>"#ffffff"],
        "3" => ["title"=>"Agitated",        "color"=>"#fff601", "border"=>"#fff601", "textColor"=>"#000000"],
        "4" => ["title"=>"PRN",             "color"=>"#8a2621", "border"=>"#8a2621", "textColor"=>"#ffffff"],
        "5" => ["title"=>"Mild Behaviour",  "color"=>"#fd020e", "border"=>"#fd020e", "textColor"=>"#ffffff"],
        "6" => ["title"=>"Severe Outburst", "color"=>"#000000", "border"=>"#000000", "textColor"=>"#ffffff"],

    ],

];
