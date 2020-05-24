//console.log("外部テスト");


function initMap() {
    //console.log("始める");
    map = new google.maps.Map(document.getElementById('map'), {
    center: center,
    zoom: 19,
    gestureHandling:"greedy",
    fullscreenControl:true        
    });
 
//既存マーカー一覧を表示

for(var i = 0;i < allmarker.length; i++){
    //console.log(allmarker[i]['lat']);
    //console.log(allmarker[i]['lng']);
    markerposition = new google.maps.LatLng(allmarker[i]['lat'],allmarker[i]['lng'])
    if(allmarker[i]['category_id'] === "1" ){
        icon = "https://maps.google.com/mapfiles/ms/micons/green-dot.png";
    }else if(allmarker[i]['category_id'] === "2"){
        icon = "https://maps.google.com/mapfiles/ms/micons/blue-dot.png";
    }else{
        icon = "https://maps.google.com/mapfiles/ms/micons/yellow-dot.png";
    }
    marker[i] = new google.maps.Marker({
        position:markerposition,
        map:map,
        icon:icon
    });
    infoWindow[i] = new google.maps.InfoWindow({
        content:`
        <div class="marker-username">${allmarker[i]['username']}</div>
        <div class="marker-content">${allmarker[i]['content']}
        </div><br>
        <a href="${allmarker[i]['abouturl']}" class="marker-link">リンク先</a>
        `
        
        //'<div><a href="">' + allmarker[i]['content'] + '</a></div>'
        
        
    })
    markerEvent(i);//マーカーにイベントを追加
    state[i] = "close";//stateという配列にフラグを指定。デフォルトではfalse。

    // マーカーにクリックイベントを追加
    function markerEvent(i) {
        marker[i].addListener('click', function() { // イベント発火
        console.log("発火");
            if(state[i] === "close"){
                infoWindow[i].open(map, marker[i]); // 吹き出しの表示
                state[i] = "open";
            }else{
                infoWindow[i].close(map, marker[i]);
                state[i] = "close";
            }
        });

    }
}

//マーカー追加メソッド
map.addListener('click',function (e){
    if(newmarker){
        //console.log("無くします");
        newmarker.setMap(null);
    }
        newmarker = new google.maps.Marker({
        position:e.latLng,
        map: map,
        icon: "https://maps.google.com/mapfiles/ms/micons/red.png",
        animation:google.maps.Animation.DROP
    });
    var response = newmarker.getPosition();//markerの場所を取得
    console.log(response.lat());
    document.getElementById('getlat').textContent = response.lat();
    document.getElementsByClassName('lat_val')[0].defaultValue = response.lat();

    console.log(response.lng());
    document.getElementById('getlng').textContent = response.lng();
    document.getElementsByClassName('lng_val')[0].defaultValue = response.lng();


    newmarker.addListener('mouseover',function(){
        this.setAnimation(google.maps.Animation.BOUNCE);
        //infoWindow.open(map,marker);

    })
    newmarker.addListener('mouseout', function(){
            this.setAnimation(null);
            //infoWindow.close(map,marker);
    });
    newmarker.addListener('click',function(){
        this.setMap(null);
    });
});

$('.js-getnow').on('click',function(){
    console.log("押した");
    if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position) { 
            //position から緯度経度（ユーザーの位置）のオブジェクトを作成し変数に代入
            var pos = { 
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            map.setCenter(pos);
            //this.panTo(e.latLng); アニメーションで中心位置を移動
    });
    }
});




}//init閉じタグ





