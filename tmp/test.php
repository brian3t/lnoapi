<?php
require_once "../vendor/autoload.php";

use yii2helper\PHPHelper;

$currentLat = 74.0064;


define('K_EARTH_RADIUS',3960.0);
$degrees_to_radians = pi()/ 180.0;
define('K_RADIANS_TO_DEGREE', 180.0 /pi());
define('EARTH_R', 3960.0);

//"Given a distance north, return the change in latitude."
function change_in_latitude($miles)
{
    return ($miles / K_EARTH_RADIUS) * K_RADIANS_TO_DEGREE;
}

Names default to here(1);
// Desired map central position and distance to view boundary
Lat = 40.782222; //Latitude
Lon = -73.965278; //Longitude
Rad = 7; // miles

// Calculate bounding box coordinates with geodetic approximation (WGS84)
a = 6378137; // Radius of earth at equator (m)
e2 = 0.00669437999014; // eccentricity squared
m = 1609.344; // 1 mile in meters
r = Pi() / 180; // convert to radians
//Distance of 1° latitude (miles)
d1 = r * a * (1 - e2) / (1 - e2 * Sin(Lat * r) ^ 2) ^ (3 / 2) / m;
//Distance of 1° longitude (miles)
d2 = r * a * Cos(Lat * r) / Sqrt(1 - e2 * Sin(Lat * r) ^ 2) / m;

//Bounding box coordinates
{minLat, maxLat} = {Lat - Rad / d1, Lat + Rad / d1};
{minLon, maxLon} = {Lon - Rad / d2, Lon + Rad / d2};
//––––––––––––––––––––––––––––––––––––––––––––––––––

//Example map
dt = Open("$SAMPLE_DATA/SAT.jmp");
gb = dt << Graph Builder(Variables(X(:Longitude), Y(:Latitude)), Elements(Points(X, Y)));
Report(gb)[FrameBox(1)] << Background Map(Images("Street Map Service")); Wait(1);

//Set bounding box (Manhattan, ~13 miles long)
Report(gb)[ScaleBox(2)] << {Min(minLat), Max(maxLat)};
Report(gb)[ScaleBox(1)] << {Min(minLon), Max(maxLon)};

$longTraveledMiles = 100;
$longTraveledKM = 100 * 1.60934;
$longTraveledDeg = (1 / (111.320 * cos($currentLat))) * $longTraveledKM;

echo $longTraveledDeg;
