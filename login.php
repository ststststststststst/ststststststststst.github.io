<?php
// 假设用户信息存储在数组中
$users = array(
    'admin' => 'sunny_town_kkk', // 管理员账号
    'sunny_town' => 'sunny_town_kkk', // 普通用户1
    'deng' => 'deng1'  // 普通用户2
);

// 获取表单提交的用户名和密码
$username = $_POST['username'];
$password = $_POST['password'];

// 在用户数组中验证用户名和密码
if (array_key_exists($username, $users) && $users[$username] == $password) {
    if ($username === 'admin') {
        echo "欢迎管理员登录！";
    } else {
        echo "普通用户登录成功！";
    }
} else {
    echo "用户名或密码错误";
}
?>
