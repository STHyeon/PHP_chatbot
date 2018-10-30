<?php
function getmeal($days)
{
  $date = date("Y.m.d", strtotime("+$days days"));

  header("Content-type: application/json; charset=UTF-8");        // json type and UTF-8 encoding
  require("Snoopy.class.php");

  $snoopy = new Snoopy;
  $schulCode = "B100000662"; //학교 코드
  $officecode = "sen.go.kr"; //교육청 코드
  $schulScCode = "4"; //학교 분류 코드
  $schMmealCode = "2"; //급식 종류 코드
  //$URL = 'http://stu.'.$officecode.'/sts_sci_md01_001.do?schulCode='.$schulCode.'&schulCrseScCode='.$schulScCode.'&schMmealScCode='.$schMmealCode.'&schYmd='.$date;
  $URL ='http://stu.sen.go.kr/sts_sci_md01_001.do?schulCode=B100000662&schulCrseScCode=4&schMmealScCode=2&schYmd='.$date;
  $snoopy->fetch($URL);

  preg_match('/<tbody>(.*?)<\/tbody>/is', $snoopy->results, $tbody); // tbody 추출
  $final=$tbody[0];
  preg_match_all('/<tr>(.*?)<\/tr>/is', $final, $final); // tr 추출

  $final=$final[0][1]; // 첫 번째 항목(0)은 급식인원, 두 번째 항목은 식단표(1)이므로
  preg_match_all('/<td class="textC">(.*?)<\/td>/is', $final, $final); // td 추출
  $day=0; // weekday number를 가져옴
  if ( date('w')+$days > 6) {
    $day = (date('w')+$days)-7;
  } else {
    $day = date('w')+$days;
  }
  // 주말이면 인덱스가 넘어버리니까 수정(될지는 테스트 안해봄)
  $final=$final[0][$day]; // 해당 날의 급식을 가져옴
  $final=preg_replace("/[0-9]/", "", $final);
  $final=preg_replace("/[*]/", "", $final);
  // 숫자 제거(정규식이용)
  $array_filter = array('.', ' ', '<tdclass="textC">', '</td>');
  // 필터
  foreach ($array_filter as $filter) {
      $final=str_replace($filter, '', $final);
  } // 필터 내용 검색해 삭제
  $final=str_replace('<br/>', '\\n', $final); // br => 개행
  $final=substr($final, 0, -2); // 마지막 줄 개행문자 없애기
  if ( strcmp($final, '') == false ){
    $final = "급식이 없습니다."; // 급식이 없을 경우
  }
  $return = array($date, $final); // 해당날짜, 급식메뉴
  return $return;
}
?>
