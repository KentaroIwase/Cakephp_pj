
<script type="text/javascript">
	var map;
	var marker_ary = new Array();
	var currentInfoWindow

	function initialize() {
		var latlng = new google.maps.LatLng(35.681298, 139.766247);
		var myOptions = {
			zoom: 15,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

        //イベント登録　地図の表示領域が変更されたらイベントを発生させる
        google.maps.event.addListener(map, 'idle', function(){
        	setPointMarker();
        });
      }

    //地図中央の緯度経度を表示
    function getMapcenter() {
        //地図中央の緯度経度を取得
        var mapcenter = map.getCenter();

        //テキストフィールドにセット
        document.getElementById("keido").value = mapcenter.lng();
        document.getElementById("ido").value = mapcenter.lat();
      }

    //地図の中央にマーカー
    function setMarker() {
    	var mapCenter = map.getCenter();

        //マーカー削除
        MarkerClear();

        //マーカー表示
        MarkerSet(mapCenter.lat(),mapCenter.lng(),'test');
      }

    //マーカー削除
    function MarkerClear() {
        //表示中のマーカーがあれば削除
        if(marker_ary.length > 0){
            //マーカー削除
            for (i = 0; i <  marker_ary.length; i++) {
            	marker_ary[i].setMap();
            }
            //配列削除
            for (i = 0; i <=  marker_ary.length; i++) {
            	marker_ary.shift();
            }
          }
        }

        function MarkerSet(lat,lng,text){
        	var marker_num = marker_ary.length;
        	var marker_position = new google.maps.LatLng(lat,lng);
        	var markerOpts = {
        		map: map, 
        		position: marker_position
        	};
        	marker_ary[marker_num] = new google.maps.Marker(markerOpts);

        //textが渡されていたらふきだしをセット
        if(text.length>0){
        	var infoWndOpts = {
        		content : text
        	};
        	var infoWnd = new google.maps.InfoWindow(infoWndOpts);
        	google.maps.event.addListener(marker_ary[marker_num], "click", function(){

                //先に開いた情報ウィンドウがあれば、closeする
                if (currentInfoWindow) {
                	currentInfoWindow.close();
                }

                //情報ウィンドウを開く
                infoWnd.open(map, marker_ary[marker_num]);

                //開いた情報ウィンドウを記録しておく
                currentInfoWindow = infoWnd;
              });
        }
      }

    //XMLで取得した地点を地図上でマーカーに表示
    function setPointMarker(){
        //リストの内容を削除
        $('#pointlist > ul').empty();

        //マーカー削除
        MarkerClear();

        //地図の範囲内を取得
        var bounds = map.getBounds();
        map_ne_lat = bounds.getNorthEast().lat();
        map_sw_lat = bounds.getSouthWest().lat();
        map_ne_lng = bounds.getNorthEast().lng();
        map_sw_lng = bounds.getSouthWest().lng();

        //XML取得
        $.ajax({
        	url: 'places/xml.xml?ne_lat='+map_ne_lat+'&sw_lat='+map_sw_lat+'&ne_lng='+map_ne_lng+'&sw_lng='+map_sw_lng,
        	type: 'GET',
        	dataType: 'xml',
        	timeout: 1000,
        	error: function(){
        		alert("情報の読み込みに失敗しました");
        	},
        	success: function(xml){
                //帰ってきた地点の数だけループ
                $(xml).find("places").each(function(){
                	var LocateLat = $("lat",this).text();
                	var LocateLng = $("lng",this).text();
                	var LocateName = $("name",this).text();
                    //マーカーをセット
                    MarkerSet(LocateLat,LocateLng,LocateName);

                    //リスト表示
                    //リストに対応するマーカー配列キーをセット
                    var marker_num = marker_ary.length - 1;
                    //liとaタグをセット
                    loc = $('<li>').append($('<a href="javascript:void(0)"/>').text(LocateName));
                    //セットしたタグにイベント「マーカーがクリックされた」をセット
                    loc.bind('click', function(){
                    	google.maps.event.trigger(marker_ary[marker_num], 'click');
                    });
                    //リスト表示
                    $('#pointlist > ul').append(loc);
                  });
              }
            });
}
</script>

<body onload="initialize()">
	<div id="pointlist" style="width:20em;float:left;">
		<ul>
			<li>地点リスト</li>
		</ul>
	</div>
	<div style="float:left;">
		<div id="map_canvas" style="width:600px; height:600px"></div>
	</div>
</body>
</html>