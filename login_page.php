<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログイン</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f1f1f1;
            color: #111;
            font-family: "Meiryo", sans-serif;
        }

        .auth-shell {
            width: 100%;
            max-width: 420px;
        }

        .auth-card {
            padding: 38px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin: 12px 0;
            text-align: center;
            font-size: 25px;
        }

        .intro {
            margin-bottom: 30px;
            color: #777;
            text-align: center;
            font-size: 13px;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            font-size: 13px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #d8d8d8;
            border-radius: 6px;
            background: #f8f8f8;
            font-size: 14px;
        }

        input:focus {
            outline: 1px solid #111;
            border-color: #111;
        }

        input[type="submit"],
        .secondary-action {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 13px;
            border: 1px solid #111;
            border-radius: 6px;
            background: #111;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }

        input[type="submit"]:hover {
            background: #444;
        }

        .secondary-action {
            margin-top: 10px;
            background: #fff;
            color: #111;
        }

        .secondary-action:hover {
            background: #eee;
        }

        @media (max-width: 480px) {
            .auth-card {
                padding: 26px 22px;
            }
        }
    </style>
</head>

<body>
    <main class="auth-shell">
        <section class="auth-card">
            <h1>ログイン</h1>
            <p class="intro">アカウントにログインしてください。</p>

            <form action="" method="post">
                <label>ユーザー名</label>
                <input type="text" name="user_name" placeholder="ユーザー名">

                <label>パスワード</label>
                <input type="password" name="password" placeholder="パスワード">

                <input type="submit" name="login" value="ログイン">
            </form>

            <input
                type="button"
                class="secondary-action"
                onclick="location.href='register_page.php'"
                value="新規アカウントを作成"
            >
        </section>
    </main>
</body>
</html>

<?php 
$dsn="mysql:dbname=xxxxxx; host=localhost";
$user="xxxxxx";
$password="xxxxxx";
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

$sql="CREATE TABLE IF NOT EXISTS money_login_data"
     ."("
     ."user_id INT AUTO_INCREMENT PRIMARY KEY,"
     ."user_name CHAR(32),"
     ."password CHAR(32),"
     ."created_at TIMESTAMP,"
     ."update_at TIMESTAMP"
     .");";
     
$pdo->query($sql);

if(!empty($_POST["login"])){
    if(!empty($_POST["user_name"]) && !empty($_POST["password"])){
        $user_name=$_POST["user_name"];
        $password=$_POST["password"];
        
        $sql="SELECT * FROM money_login_data WHERE user_name=:user_name";
        
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(":user_name",$user_name,PDO::PARAM_STR);
        $stmt->execute();
        
        $result=$stmt->fetch();
        
        if($result){
            if($password==$result["password"]){
                $_SESSION["user_id"]=$result["user_id"];
                $_SESSION["user_name"]=$result["user_name"];
                
                header("Location:money_page.php");
                exit;
                
            }
            else{
                echo "パスワードが間違っています。";
            }
        }
        else{
            echo "ユーザー名が存在しません。";
        }
    }
    else{
        echo "ユーザー名またはパスワードが未入力です。";
    }
    
    
}
?>
