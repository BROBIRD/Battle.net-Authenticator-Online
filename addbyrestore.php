<?php

//fix
$restorecodefalse = false;
require_once('classes/Authenticator.php');
include('includes/config.php');
$topnavvalue = "添加安全令";
include('includes/html_toubu/html_toubu.php');
include('includes/page_inc/header_normal.php');
if ($logincheck == 0) {
    $navurladd = SITEHOST . "welcome.php";
    $topnavvalue = "WELCOME";
    include('includes/page_inc/welcome_inc.php');
} else {
    $query = "SELECT * FROM `users` WHERE `user_name`='$user'";
    $rowtemp = queryRow($query);
    $user_id = $rowtemp['user_id'];
    $user_right = $rowtemp['user_right'];
    $user_donated = $rowtemp['user_donated'];
    if ($user_donated > 0) {
        define("USER_DONATED", TRUE);
    }
    $sql = "SELECT * FROM `authdata` WHERE `user_id`='$user_id'";
    if ((!USER_DONATED && queryNum_rows($sql) < MOST_AUTH) || (USER_DONATED && queryNum_rows($sql) < MOST_AUTH_DONATED)) {
        try {
            include('includes/auth_add/authadd_byrestore.php'); //生成AUTH用
        } catch (Exception $exc) {
            $authaddbyrestoreerrorid = 5;
        }
    } else {
        $authaddbyrestoreerrorid = 6;
    }
    switch ($authaddbyrestoreerrorid) {
        case 0:
            $jumptxt = "还原成功，即将跳转到该安全令页面。";
            if ($auth_moren == 1)
                $jumpurl = SITEHOST . "auth.php";
            else
                $jumpurl = SITEHOST . "normalauth.php?authid=" . $auth_id;
            break;
        case 1:
            $jumptxt = "填写的内容有误，请返回检查后再试。";
            $jumpurl = SITEHOST . "addauth.php#game-time-subscriptions";
            break;
        case 2:
            $jumptxt = "填写的内容有误，请返回检查后再试。";
            $jumpurl = SITEHOST . "addauth.php#game-time-subscriptions";
            break;
        case 3:
            $jumptxt = "不要这样吧，不登入也想玩？即将返回主页。";
            $jumpurl = SITEHOST . "index.php";
            break;
        case 4:
            $jumptxt = "验证码错误，请返回重试。";
            $jumpurl = SITEHOST . "addauth.php#game-time-subscriptions";
            break;
        case 5:
            if ($restorecodefalse) {
                $jumptxt = "输入的序列号与密钥/还原码不对应，请返回重试。";
                $jumpurl = SITEHOST . "addauth.php#game-time-subscriptions";
            } else {
                $jumptxt = "还原失败，暴雪服务器出错了";
                $jumpurl = SITEHOST . "addauth.php#game-time-subscriptions";
            }
            break;
        case 6:
            if (USER_DONATED) {
                $jumptxt = "你已经拥有" . MOST_AUTH_DONATED . "枚安全令了，不能再多了。即将返回主页";
            } else {
                $jumptxt = "你已经拥有" . MOST_AUTH . "枚安全令了，不能再多了。即将返回主页";
            }
            $jumpurl = SITEHOST . "index.php";
            break;
        default :
            $jumptxt = "未知错误，要不去找下鹳狸猿吧。";
            $jumpurl = SITEHOST . "addauth.php#game-time-subscriptions";
    }
    if ($authaddbyrestoreerrorid == 5 && !$restorecodefalse) {
        include('includes/auth_jump/auth_restore_refresh.php');
    } else {
        include('includes/auth_jump/auth_restore_jump.php');
    }
}
include('includes/page_inc/footer.php');
?>