<?php
  include("data/lunch.php");
  include("data/echoKakao.php");

  $data = json_decode(file_get_contents('php://input'), true);
  $content = $data["content"];

  switch($content){
    case "오늘 급식":
      $final = getmeal(0);
      start_echo();
          start_msg();
              echo_text($final[0] . "\\n==========\\n" . $final[1], 0);
          end_msg(1);
          keyboard_button(array("오늘 급식", "내일 급식"));
      end_echo();
      break;
    case "내일 급식":
      $final = getmeal(1);
      start_echo();
        start_msg();
          echo_text($final[0] . "\\n========\\n" . $final[1], 0);
        end_msg(1);
        keyboard_button(array("오늘 급식", "내일 급식"));
      end_echo();
      break;
    default:
      echo'{
        "message":{
          "text": "개발안해"
        },
        "keyboard":{
          "type": "buttons",
          "buttons": ["오늘 급식", "내일 급식"]
        }
      }';
      break;
    }
?>
