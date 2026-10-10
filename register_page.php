<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>新規登録</title>

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

        input[type="submit"] {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            border: 1px solid #111;
            border-radius: 6px;
            background: #111;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        input[type="submit"]:hover {
            background: #444;
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
            <h1>新規アカウント登録</h1>
            <p class="intro">新しいアカウントを作成してください。</p>

            <form action="" method="post">
                <label>ユーザー名</label>
                <input type="text" name="user_name" placeholder="新規ユーザー名を入力">

                <label>パスワード</label>
                <input type="password" name="password" placeholder="新規パスワードを入力">

                <input type="submit" value="アカウントを作成" name="new_user_register">
            </form>
        </section>
    </main>
</body>
</html>

<?php 
$dsn="mysql:dbname=xxxxxx; host=localhost";
$user="xxxxxx";
$password="xxxxxx";
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

if(!empty($_POST["new_user_register"])){
    if(!empty($_POST["user_name"]) && !empty($_POST["password"])){
        $user_name=$_POST["user_name"];
        $password=$_POST["password"];
        $user_name_flag=0;
        
        $sql="SELECT * FROM money_login_data";
        $stmt=$pdo->query($sql);
        $results=$stmt->fetchAll();
        
        foreach($results as $row){
            if($row["user_name"]==$user_name){
                echo "このユーザー名は存在しているため登録できません。別のユーザー名を設定してください。";
                $user_name_flag=1;
            }
        }
        
        if($user_name_flag==0){
            $sql="INSERT INTO money_login_data (user_name,password,created_at) VALUES(:user_name,:password,now())";
            $stmt=$pdo->prepare($sql);
        
            $stmt->bindParam(":user_name",$user_name,PDO::PARAM_STR);
            $stmt->bindParam(":password",$password,PDO::PARAM_STR);
        
            $stmt->execute();
            echo "ユーザー情報の新規登録が完了しました！";
        }
    }
    else{
        echo "ユーザー名またはパスワードが入力されていません。";
    }
}
?>
