<?php

namespace JLaso\SimpleMemoryDb\BigExample;

require_once __DIR__ . '/../../vendor/autoload.php';

use Faker\Factory;
use Dariuszp\CliProgressBar;

const TAX_TYPES = 125;
const CUSTOMERS = 10000;

$faker = Factory::create();

$countryNames = array(
    'Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan',
    'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island (Bouvetoya)', 'Brazil', 'British Indian Ocean Territory', 'British Virgin Islands', 'Brunei Darussalam', 'Bulgaria', 'Burkina Faso', 'Burundi',
    'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic',
    'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic',
    'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia',
    'Faroe Islands', 'Falkland Islands (Malvinas)', 'Fiji', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern Territories',
    'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guernsey', 'Guinea', 'Guinea-Bissau', 'Guyana',
    'Haiti', 'Heard Island and McDonald Islands', 'Holy See (Vatican City State)', 'Honduras', 'Hong Kong', 'Hungary',
    'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Isle of Man', 'Israel', 'Italy',
    'Jamaica', 'Japan', 'Jersey', 'Jordan',
    'Kazakhstan', 'Kenya', 'Kiribati', 'Korea', 'Korea', 'Kuwait', 'Kyrgyz Republic',
    'Lao People\'s Democratic Republic', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libyan Arab Jamahiriya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
    'Macao', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar',
    'Namibia', 'Nauru', 'Nepal', 'Netherlands Antilles', 'Netherlands', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'Northern Mariana Islands', 'Norway',
    'Oman',
    'Pakistan', 'Palau', 'Palestinian Territory', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn Islands', 'Poland', 'Portugal', 'Puerto Rico',
    'Qatar',
    'Reunion', 'Romania', 'Russian Federation', 'Rwanda',
    'Saint Barthelemy', 'Saint Helena', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Martin', 'Saint Pierre and Miquelon', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia (Slovak Republic)', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia and the South Sandwich Islands', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard & Jan Mayen Islands', 'Swaziland', 'Sweden', 'Switzerland', 'Syrian Arab Republic',
    'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo', 'Tokelau', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks and Caicos Islands', 'Tuvalu',
    'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States of America', 'United States Minor Outlying Islands', 'United States Virgin Islands', 'Uruguay', 'Uzbekistan',
    'Vanuatu', 'Venezuela', 'Vietnam',
    'Wallis and Futuna', 'Western Sahara',
    'Yemen',
    'Zambia', 'Zimbabwe'
);
$countriesNum = count($countryNames);

// creating fake customers
$bar = new CliProgressBar(CUSTOMERS);
$bar->display();
$customers = [];
for ($i = 0; $i < CUSTOMERS; $i++) {
    $customers[] = [
        'id' => $i + 1,
        'name' => $faker->name,
        'tax_type_id' => $faker->numberBetween(1, TAX_TYPES),
        'address' => $faker->streetAddress,
        'country' => $faker->country,
        'company' => $faker->company,
        'web' => $faker->url,
        'notes' => $faker->text,
        'country_id' => 1 + $faker->numberBetween(0, $countriesNum),
    ];
    $bar->progress();
}
file_put_contents(__DIR__ . '/customers.json', json_encode($customers, JSON_PRETTY_PRINT));
$bar->end();
printf("There are %d customers\r\n", count($customers));

// creating fake taxes
$bar = new CliProgressBar(TAX_TYPES);
$bar->display();
$taxes = [];
for ($i = 0; $i < TAX_TYPES; $i++) {
    $taxes[] = [
        'id' => $i + 1,
        'name' => 'Tax type #' . ($i + 1),
        'value' => $faker->numberBetween(1, 100) / 10,
    ];
    $bar->progress();
}
file_put_contents(__DIR__ . '/taxes.json', json_encode($taxes, JSON_PRETTY_PRINT));
$bar->end();
printf("There are %d taxes\r\n", count($taxes));

// creating countries
$bar = new CliProgressBar($countriesNum);
$bar->display();
$countries = [];
for ($i = 0; $i < $countriesNum; $i++) {
    $countries[] = [
        'id' => $i + 1,
        'name' => $countryNames[$i],
    ];
    $bar->progress();
}
file_put_contents(__DIR__ . '/countries.json', json_encode($countries, JSON_PRETTY_PRINT));
$bar->end();
printf("There are %d countries\r\n", count($countries));
