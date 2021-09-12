<?php

$countries = [
    'cAnAdA',
    'SwitZerLand',
    'GrEEce',
    'HUnGary',
    'CroATia',
    'IndOneSia',
    'IrElAnd',
    'InDia',
    'MonGoLia',
    'UNitED StaTes of AmeriCA',
    'ChiNa',
    'romaNia',
    'Poland',
    'SieRRA LeoNe',
    'fraNcE',
    'JaPAn',
    'Belgium',
    'TuRkEy',
    'Aland islANds',
    'YeMen',
    'Egypt',
];

// Search countries functions
function searchCountries($term, $countries) {
    // Convert the $term variable into lower case 
    $term = strtolower($term);
    // Adds the starting table tag to the $string variable 
    $string = "<table style='border: 1px solid rgb(200, 200, 200); border-collapse: collapse;'>";
    // Loops through $countries array
    foreach($countries as $value) {
        // Converts each array item into lower case
        $lowerCase= strtolower($value);
        // Condtional statement... 
        // strpos() checks for the first occurance of $term in $lowerCase, if it's not false do the following
        if (strpos($lowerCase, $term) !== false) {
            // appends a table row and two table cells to the $string variable
            $string .= 
            "<tr>
                <td style='text-align: center; border-right: 1px solid rgb(200, 200, 200); border-bottom: 1px solid rgb(200, 200, 200)'>".
                $value.
                "</td>
                <td style='text-align: center; border-right: 1px solid rgb(200, 200, 200); border-bottom: 1px solid rgb(200, 200, 200)'>".
                // Capitalizes the first letter of each word in a string
                ucwords($lowerCase).
                "</td>
            </tr>";
        }
    }

    //Conditional... checks if the string length is greater than 80, which is the length if no matches are found
    if (strlen($string) > 80) {
        // If there is a match it appends the last table tag and returns the string
        $string .= "</table>";
        return $string;
    } else {
        // if there are no matches returns the string below 
        return $string = "Sorry there are no matches";
    }
}

// Conditional... if the form input doesn't equal blank run and echo the function
if ($_GET['term'] !== "") {
    echo searchCountries($_GET['term'], $countries);
} else {
    // If the form input is blank echo the below message
    echo "Sorry you can't leave the input blank";
}


?>