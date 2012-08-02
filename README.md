repos
=====

A simple caching mechanism, that can be used if you are making your own.

Say for example you want to cache following apis.

http://localhost/APIs/JsonAPI/getUser.php?id=3
http://localhost/APIs/JsonAPI/getDepartments.php

You need to change following parameters in index.php

$host = $protocol."://localhost/APIs/";
$endpoint = "JsonAPI/";

Change it to url and endpoint path of urs.

$pages_to_cache_array = array("getArtistsAPI.php"=>12*60*60,"categoryAPI.php"=>24*60*60);

stores amount of time in seconds to cache that particular api.

In case you wan to fetch nocached data you can append nocache=true parameter to return the same. For eg.

http://localhost/APIs/JsonAPI/getUser.php?id=3&nocache=true

will bypass the caching mechanism and will return nocached data...

Enjoy Caching.

For any queries contact
sanket.mehta7@gmail.com

