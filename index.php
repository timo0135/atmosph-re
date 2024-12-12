<?php
require_once 'src/meteo.php';
require_once 'src/ip.php';
require_once 'src/circulation.php';
require_once 'src/qualite.php';
require_once 'src/adresse.php';


$xml = getMeteo();
$qualite = getAirQualityTODAY();
$coord = getAddrIpClient();
$addr = getIUTAddress();
$latitude = $coord->latitude;
$longitude = $coord->longitude;
?>
<html lang="fr">
<head>
    <title>Prévisions Météo</title>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
</head>
<body>
<?php echo $xml; ?>
<div class="air-quality">
    <h3>Qualité de l'air à Nancy : </h3>
    <div style="display: flex; flex-direction: row; justify-content: flex-start; align-items: center"><p><?php echo $qualite->attributes->lib_qual ?>  </p><span style="background-color: <?php echo $qualite->attributes->coul_qual ?>; width: 15px; height: 15px; border-radius: 50%; display: inline-block;"></span></div>
    <p><?php echo $qualite->attributes->code_qual ?></p>
</div>
<div id="map" style="height: 600px;"></div>
<div class="used_api">
    <h3>APIs utilisées :</h3>
    <ul>
        <li><a href="https://ipapi.co/xml/">https://ipapi.co/</a></li>
        <li><a href="https://www.infoclimat.fr/public-api/gfs/xml?_ll=48.67103,6.15083&_auth=ARsDFFIsBCZRfFtsD3lSe1Q8ADUPeVRzBHgFZgtuAH1UMQNgUTNcPlU5VClSfVZkUn8AYVxmVW0Eb1I2WylSLgFgA25SNwRuUT1bPw83UnlUeAB9DzFUcwR4BWMLYwBhVCkDb1EzXCBVOFQoUmNWZlJnAH9cfFVsBGRSPVs1UjEBZwNkUjIEYVE6WyYPIFJjVGUAZg9mVD4EbwVhCzMAMFQzA2JRMlw5VThUKFJiVmtSZQBpXGtVbwRlUjVbKVIuARsDFFIsBCZRfFtsD3lSe1QyAD4PZA%3D%3D&_c=19f3aa7d766b6ba91191c8be71dd1ab2">https://www.infoclimat.fr/</a></li>
        <li><a href="https://services3.arcgis.com/Is0UwT37raQYl9Jj/arcgis/rest/services/ind_grandest/FeatureServer/0/query?where=lib_zone%3D%27Nancy%27&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&resultType=none&distance=0.0&units=esriSRUnit_Meter&returnGeodetic=false&outFields=*&returnGeometry=true&featureEncoding=esriDefault&multipatchOption=xyFootprint&maxAllowableOffset=&geometryPrecision=&outSR=&datumTransformation=&applyVCSProjection=false&returnIdsOnly=false&returnUniqueIdsOnly=false&returnCountOnly=false&returnExtentOnly=false&returnQueryGeometry=false&returnDistinctValues=false&cacheHint=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&having=&resultOffset=&resultRecordCount=&returnZ=false&returnM=false&returnExceededLimitFeatures=true&quantizationParameters=&sqlFormat=none&f=pjson&token=">https://services3.arcgis.com/</a></li>
        <li><a href="https://api-adresse.data.gouv.fr/search/?q=2%20boulevard%20charlemagne">https://api-adresse.data.gouv.fr/</a></li>
        <li><a href="https://carto.g-ny.org/data/cifs/cifs_waze_v2.json">https://carto.g-ny.org/</a></li>
    </ul>
</div>
<script>
    var map = L.map('map').setView([<?php echo $latitude ?>, <?php echo $longitude ?>], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
</script>
<script>
    const data = <?php echo getCirculation() ?>;
    data.incidents.forEach(incident => {
        var coords = incident.location.polyline.split(' ');
        var marker = L.marker([parseFloat(coords[0]), parseFloat(coords[1])]).addTo(map);
        marker.bindPopup(`<b>${incident.short_description}</b><br>${incident.description}<br>Depuis: ${incident.starttime}<br>Jusqu'au: ${incident.endtime}`);
    });

    const iutCoords = [<?php echo $addr->geometry->coordinates[1] ?>, <?php echo $addr->geometry->coordinates[0] ?>];
    L.marker(iutCoords).addTo(map).bindPopup(`<b><?php echo $addr->properties->label ?></b>`);


</script>

</body>
</html>
