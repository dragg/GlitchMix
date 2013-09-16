<?
    function downloadFile ($url, $path) {
        $newfname = $path;
        $file = fopen ($url, "rb");
        if ($file) {
            $newf = fopen ($newfname, "wb");
        if ($newf)
            while(!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }
?>
<?
    session_start();
    if (isset($_GET['code'])) {
        unset($_SESSION['user_id']);
        $url = 
        "https://oauth.vk.com/access_token?".
        "client_id=ID&".
        "client_secret=KEY&".
        "code=".$_GET['code']."&".
        "redirect_uri=REDIRECT_URL/glitch.php";
        
        $content = file_get_contents($url);
        $main = $json = json_decode($content, true);
        if (isset($main['access_token'])) {
            $url =
            "https://api.vk.com/method/friends.get?".
            "user_id=".$main['user_id']."&".
            "count=50&".
            "order=random&".
            "fields=photo,photo_200&".
            "v=5.0&".
            "access_token=".$main['access_token'];


            $content = file_get_contents($url);
            $json = json_decode($content, true);
            for ($i = 0, $j = 0; $i < 25; $i++){
                for ($j = $j + 1; $j < $json['response']['count']; $j++) {
                    if (isset($json['response']['items'][$j]['photo_200'])) {
                        $photos_url[$i] = $json['response']['items'][$j]['photo_200'];
                        break;
                    }
                }
            }
            if (!is_dir("images/".$main['user_id'])) {
                mkdir("images/".$main['user_id']);
                
            }
            for($i = 0; $i < 25; $i++) {
                downloadFile($photos_url[$i], "images/".$main['user_id']."/photo-$i.jpg");
            }
            $_SESSION['user_id'] = $main['user_id'];
            header('Location: /glitch.php');
        }
        
    }
    else if ($_SESSION['user_id']) {
        $main['user_id'] = $_SESSION['user_id'];
    }
?>
<html>
    <head>
        <title>Glitch Mix</title>
        <link rel="stylesheet" type="text/css" href="css/Style.css" />
        <script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
        <script type="text/javascript" src="js/Glitch.js"></script>
    </head>
    <body>     
        <table>
            <tbody>
                <tr>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                </tr>
                <tr>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                </tr>
                <tr>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                </tr>
                <tr>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                </tr>
                <tr>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                    <td><canvas class="canvas" width="200" height="200"></canvas></td>
                </tr>
            </tbody>
        </table>
        
    </body>
</html>

<script>
    $(document).ready(function(){
        url = "<?="images/".$main['user_id']."/photo-"?>";
        var j = 0;
        for (i = 0; i < 25; i++){
            img = new Image();
            img.onload = function(){
                var canvas = $('.canvas')[j++];
                var ctx = canvas.getContext('2d');
                ctx.drawImage(this, 0, 0);
                var imgData = canvas.toDataURL("image/jpeg");
                imgDataArr = base64ToByteArray(imgData);
                glitchJpeg(imgDataArr, ctx);
            };
            img.src = url+i+".jpg";
        }
    });
</script>
