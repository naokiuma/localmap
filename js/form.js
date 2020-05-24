$('.check').change(function() {
    //始めにjQueryで送信ボタンを無効化する
    if($('.getlat-num').text().length > 0 && $('.getlng-num').text().length > 0){
        console.log("checekおk");
        $('.submit-btn').prop('disabled', true);
    }
    
})
