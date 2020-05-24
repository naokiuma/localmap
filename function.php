<?php


ini_set('log_errors','on');
ini_set('error_log','php.log');
ini_set('display_errors', 1);
error_reporting(E_ALL);

//--------------------------------------------
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ:'.$str);
  }
}


//--------------------------------------------

function queryPost($dbh, $sql, $data){
  debug($sql);
  $stmt = $dbh->prepare($sql);
  if(!$stmt->execute($data)){
    debug('クエリ失敗');
    debug('失敗したSQL:'.print_r($stmt,true));
    return 0;
  }
    debug('クエリ成功');
    debug('成功したSQL:'.print_r($stmt,true));
    return $stmt;
}

//既存のアイコンを取得する。
function callMarker(){
    //例外処理
    try{
      $dbh = dbConnect();
      $sql = 'SELECT * FROM `locations`';
      $stmt = $dbh->query($sql);//stmtでdbにsqlをセット
      $search_rst = $stmt->fetchAll(PDO::FETCH_ASSOC);

     // debug('マーカーを呼び出します:'.print_r($search_rst,true));

      //クエリ実行
      //$stmt->execute();
      //debug('SQL:'.print_r($stmt,true));
      
      if($search_rst){
          debug('成功。再読み込み');
          return $search_rst;
        }
      
    } catch (Exception $e) {
      debug('エラー発生:' . $e->getMessage());
  }

}

  