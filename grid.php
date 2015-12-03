<?php

    namespace GeoJson;
    use GeoJson\GeoJson;
    use GeoJson\Geometry\LinearRing;
    use GeoJson\Geometry\Polygon;
    use GeoJson\Geometry\Geometry;
    use GeoJson\Geometry\GeometryCollection;
    use GeoJson\Feature\Feature;
    use GeoJson\Feature\FeatureCollection;

    if (!file_exists($autoloadFile = 'vendor/autoload.php')) {
        throw new RuntimeException('Install dependencies to run test suite.');
    }
    require_once $autoloadFile;

    $row = 1;
    if (($handle = fopen("tpe_grid_latlng.csv", "r")) !== FALSE) {

        $features = array();
        $coordinates = array();
        $rowNum = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $latlon = array($data[4]*1,$data[3]*1);
            array_push($coordinates, $latlon);
            $rowNum++;
            if($rowNum >= 5){
                $propertie=array();
                $propertie['row'] = $data[0];
                $propertie['col'] = $data[1];
                $propertie['PM2.5'] = 0;
                $propertie['PM10'] = 0;
                $propertie['AQI'] = 0;
                $c = array();
                array_push($c,$coordinates);
                $polygon = new Polygon($c);
                $feature = new Feature($polygon,$propertie);
                array_push($features,$feature);
                //echo json_encode($feature);
                $coordinates = array();
                $rowNum = 0;
            }
        }
    }
    $allfeatures = new FeatureCollection($features);
    echo json_encode($allfeatures);
?>
