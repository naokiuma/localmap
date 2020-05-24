<?php

require('db.php');
require('function.php');

if($_POST){
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $username = $_POST['username'];
    if(empty($username)) $username = "名無しさん";
    //debug($username);
    $content = $_POST['content'];
    if(empty($abouturl)) $abouturl = "";
    $abouturl = $_POST['abouturl'];
    $category_id = $_POST['category_id'];
    debug($lat);
    debug($lng);
    debug($content);
    debug($category_id);

    //例外処理
    try{
        $dbh = dbConnect();
        $sql = 'INSERT INTO locations (content,lat,lng,username,abouturl,category_id) VALUES (:content,:lat,:lng,:username,:abouturl,:category_id)';
        //$stmt = $dbh->prepare($sql);//stmtでdbにsqlをセット
        //debug('初回:'.print_r($stmt,true));

        $data = array(':content' => $content, ':username' => $username, ':lat' => $lat, ':lng' => $lng,':abouturl' => $abouturl,':category_id' => $category_id);
        $stmt = queryPost($dbh, $sql, $data);
        $dbh = null;
       
        if($stmt){
            debug('成功。再読み込み');
            header("Location: " . $_SERVER['PHP_SELF']);
          }
        
      } catch (Exception $e) {
        var_dump('エラー発生:' . $e->getMessage());
    }
  }

  $search_rst = json_encode(callMarker());

?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <title>ご近所LIKEマップ</title>
    <meta name="description" content="「ご近所LIKEマップ」は近所のお気に入りの場所を投稿、共有するサービスです。">
    <meta name="keywords" content="外食,テイクアウト,景色,近所,遊び場,公園,お気に入り,LIKE,地図,Googlemap">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="./js/smooth-scroll.polyfills.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/cferdinandi/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-167420050-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-167420050-1');
    </script>
</head>

<body ontouchstart="">
<div id="wrapper">

<header>
    <h1><a href="">ご近所LIKEマップ</a></h1>
    <nav>
        <a href="#thelocalmap">マップへ</a>
        <a href="#description">本サービスについて</a>
    </nav>
</header>


<section class="hero">
    <h2>好きな場所をシェアしよう。</h2>
    <div class="hero-wrapper">
        <div>
            <img class="hero-map" src="./img/map.png" alt="">
            <a href="#map"><img class="hero-button" src="https://maps.google.com/mapfiles/ms/micons/ltblue-dot.png" alt=""></a>
            <p class="fukidashi">地図を見る!</p>
        </div>
        <p class="hero-text">※本サービスはGoogle map apiを利用しています。</p>
    </div>

</section>

<div class="description" id="description">
　<h2 class="read-text">みんなで作るLIKE地図</h2>
    <p>美味しいご飯やテイクアウトのあるお店、<br class="sp_br">たくさんの猫がいる公園。<br>
    街がよく見下ろせる場所、<br class="sp_br">風が気持ちいい場所、空が広い場所。<br>
    強い対戦相手が見つかるゲーセン、<br class="sp_br">笑いの絶えないボルダリングジム。</p>
    <br>
    <p>『ご近所LiKEマップ』は<br class="sp_br">あなたの'好き'をみんなで共有するサービスです。</p>
    
</div>
<div class="img-wrapper">
        <div class="img-box"><img src="./img/hero-a.jpg" alt=""></div>
        <div class="img-box"><img src="./img/hero-b.jpg" alt=""></div>
        <div class="img-box"><img src="./img/hero-d.jpg" alt=""></div>
        <div class="img-box"><img src="./img/hero-e.jpg" alt=""></div>
        <div class="img-box"><img src="./img/hero-f.jpg" alt=""></div>
</div>

<h3 class="js-howto-show howto">使い方</h3>

<section class="about">
    <div class="about-wrapper">
        <div class="step">
            <h4>STEP1.散歩する</h4>
            <p>近所を散歩して、いいスポットを見つける。</p>
            <div class="stepimg-wrap">
            <img src="./img/step1.jpg" alt="散歩">
            </div>
        </div>
        <div style="height:30px" class="sp_br"></div>
        <div class="step">
            <h4>STEP2.地図に登録する</h4>
            <p>マップをクリックし、情報入力後、投稿！</p>
            <div class="stepimg-wrap">
            <img src="./img/step2.png" alt="地図画像">
</div>          
        </div>
    </div>
</section>

<div class="space"></div>

<h2 class="map-read-text" id="thelocalmap">みんなの地図</h2>
<p class="content-text">地図をクリックし、「内容」を入力し<br class="sp_br">「登録！」ボタンで地図に情報を登録できます。</p>
<div class="topmap" id="map"></div>
<button class="nowpos js-getnow">現在地に移動</button>
<span class="now-span">※お使いのブラウザで位置情報アクセスを許可されている必要があります。</span>
<section class="contents-wrapper">   
    <div class="marker-position">
        <form action="" method="post">
            <h3 class="read-text">好きな場所を投稿しよう。</h3><br>
            <div>緯度：<span id="getlat" class="getlat-num"></span><span class="need">※必須</span></div>
            <input class="lat_val hide" type="number" name="lat" value="" required>
            <div>経度：<span id="getlng" class="getlng-num"></span><span class="need">※必須</span></div><br>
            <input class="lng_val hide" type="number" name="lng" value="" required>
            <input type="radio" class="category_btn" name="category_id" value="1" required checked>のんびり
            <input type="radio" class="category_btn" name="category_id" value="2">たべもの
            <input type="radio" class="category_btn" name="category_id" value="3">あそぶ
            <div>投稿者名</div>
            <input name="username" type="text" value="名無しさん">
            <div>内容<span class="need">※必須</span></div>
            <textarea class="check" name="content" id="" cols="60" rows="7" placeholder="場所の名前や、良いところを入力" required></textarea>
            <div>URL</div>
            <input name="abouturl" type="url" placeholder="http">
            <br>
            <button type="submit" class="submit-btn">投稿！</button>

        </form>
        

    </div>
</section>

<div class="space"></div>


<div class="twitter-wrapper">
<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-hashtags="locallikemap" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>


<footer>
    <div class="copy">Copyright © ご近所LiKEマップ. All rights reserved </div>
</footer>

    <script>
    //phpから受け取ったDBデータ
    var allmarker = <?php echo $search_rst; ?>;
    //console.log("受け取ったデータ");
    //console.log(allmarker);
    
    var map;
    var marker = [];//既存マーカー
    var state = [];
    var newmarker;//新規マーカー
    var infoWindow = [];
    var nowpositon;//現在地のウインドウ
    var center =  {lat: 35.68151827504024, lng: 139.76683417941095};

    //smooth scroll
    var scroll = new SmoothScroll('a[href*="#"]');

    //クリックによる使い方toggle
    $('.js-howto-show').on("click", function() {
        $('.about').slideToggle();
    });
    </script> 


    <script type="text/javascript" src="./js/map.js"></script>


    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdMGovP86OVf0rhbzezWKGV5Z32TMxt3Y&callback=initMap" async defer></script>

    
</div>
</body>
</html>
