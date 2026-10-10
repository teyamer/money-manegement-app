<?php
//DBの接続情報やログインページのユーザー情報
session_start();

if(!empty($_POST["logout"])){
    session_unset();
    session_destroy();
    
    header("Location:login_page.php");
    exit;
}

$dsn="mysql:dbname=xxxxxx; host=localhost";
$user="xxxxxx";
$password="xxxxxx";
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

$sql="CREATE TABLE IF NOT EXISTS user_money_data"
     ."("
     ."money_id INT AUTO_INCREMENT PRIMARY KEY,"
     ."user_id INT,"
     ."amount INT,"
     ."type CHAR(10),"
     ."category CHAR(32),"
     ."date DATE,"
     ."created_or_update_at TIMESTAMP,"
     ."memo TEXT"
     .")";
     
$pdo->query($sql);

$user_id=$_SESSION["user_id"];
$user_name=$_SESSION["user_name"];
?>

<?php 
//収支情報の新規登録
if(!empty($_POST["syushi_register"])){
    $type=$_POST["type"];
    $date=$_POST["date"];
    $amount=$_POST["amount"];
    $category=$_POST["category"];
    $memo=$_POST["memo"];
        
    $sql="INSERT INTO user_money_data (user_id,amount,type,category,date,created_or_update_at,memo) VALUES (:user_id,:amount,:type,:category,:date,now(),:memo)";
            
    $stmt=$pdo->prepare($sql);
            
    $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
    $stmt->bindParam(":amount",$amount,PDO::PARAM_INT);
    $stmt->bindParam(":type",$type);
    $stmt->bindParam(":category",$category,PDO::PARAM_STR);
    $stmt->bindParam(":date",$date);
    $stmt->bindParam(":memo",$memo,PDO::PARAM_STR);
            
    $stmt->execute();
    }
?>

<?php 
//編集情報の取得
if(!empty($_POST["edit"])){
    $money_id=$_POST["money_id"];
            
    $sql="SELECT * FROM user_money_data WHERE money_id=:money_id";
            
    $stmt=$pdo->prepare($sql);
            
    $stmt->bindParam(":money_id",$money_id,PDO::PARAM_INT);
            
    $stmt->execute();
            
    $edit_data=$stmt->fetch();
}
?>

<?php 
//編集対象の更新
if(!empty($_POST["update"])){
    $money_id=$_POST["money_id"];
    $type=$_POST["type"];
    $date=$_POST["date"];
    $amount=$_POST["amount"];
    $category=$_POST["category"];
    $memo=$_POST["memo"];
            
    $sql="UPDATE user_money_data SET amount=:amount,type=:type,category=:category,date=:date,created_or_update_at=NOW(),memo=:memo WHERE money_id=:money_id";
            
    $stmt=$pdo->prepare($sql);
            
    $stmt->bindParam(":amount",$amount,PDO::PARAM_INT);
    $stmt->bindParam(":type",$type,PDO::PARAM_STR);
    $stmt->bindParam(":category",$category,PDO::PARAM_STR);
    $stmt->bindParam(":date",$date);
    $stmt->bindParam(":memo",$memo,PDO::PARAM_STR);
    $stmt->bindParam(":money_id",$money_id,PDO::PARAM_INT);
            
    $stmt->execute();
}
?>


<?php
//データの削除
if(!empty($_POST["delete"])){
    $money_id=$_POST["money_id"];
            
    $sql="DELETE FROM user_money_data WHERE money_id=:money_id";
            
    $stmt=$pdo->prepare($sql);
            
    $stmt->bindParam(":money_id",$money_id,PDO::PARAM_INT);
    
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>お金管理画面</title>
        
        <style>
            * {
                box-sizing: border-box;
            }
        
            body {
                margin: 0;
                padding: 36px 24px;
                background: #f1f1f1;
                color: #111;
                font-family: "Meiryo", sans-serif;
            }
            
            .dashboard {
                width: 100%;
                max-width: 1000px;
                margin: 0 auto;
            }
            
            .welcome {
                color: #555;
                font-size: 14px;
            }
        
            .balance-card {
                margin-bottom: 30px;
                padding: 32px;
                border-radius: 10px;
                background: #111;
                color: #fff;
            }
        
            .balance-card p {
                margin: 0;
                color: #ccc;
                line-height: 1.8;
            }
        
            .balance-card .balance-label {
                font-size: 12px;
                letter-spacing: 2px;
            }
        
            .balance-card .balance-value {
                margin-top: 8px;
                color: #fff;
                font-size: clamp(28px, 5vw, 42px);
                font-weight: bold;
                letter-spacing: 1px;
            }
        
            .panel {
                margin-bottom: 28px;
                padding: 26px;
                background: #fff;
                border: 1px solid #dedede;
                border-radius: 9px;
            }
        
            h1, h2 {
                color: #111;
            }
        
            .panel h2 {
                margin: 0 0 24px;
                font-size: 19px;
            }
        
            label {
                display: inline-block;
                margin: 10px 12px 8px 0;
                font-size: 13px;
                font-weight: bold;
                margin-bottom: 12px;
            }
        
            input[type="text"],
            input[type="number"],
            input[type="date"],
            select,
            textarea {
                max-width: 100%;
                padding: 11px 12px;
                border: 1px solid #d5d5d5;
                border-radius: 5px;
                background: #fafafa;
                color: #111;
                font-size: 14px;
                margin-bottom: 10px;
            }
        
            input:focus, select:focus, textarea:focus {
                outline: 1px solid #111;
                border-color: #111;
            }
        
            input[type="submit"],
            button {
                padding: 10px 16px;
                border: 1px solid #111;
                border-radius: 5px;
                background: #111;
                color: #fff;
                font-size: 13px;
                cursor: pointer;
                transition: 0.2s;
            }
        
            input[type="submit"]:hover,
            button:hover {
                background: #fff;
                color: #111;
            }
        
            .table-wrap {
                width: 100%;
                overflow-x: auto;
                margin-bottom: 16px;
            }
        
            table {
                width: 100%;
                border-collapse: collapse;
                background: #fff;
                font-size: 14px;
            }
        
            th {
                padding: 14px 12px;
                background: #111;
                color: #fff;
                text-align: left;
                font-weight: 500;
                white-space: nowrap;
            }
        
            td {
                padding: 13px 12px;
                border-bottom: 1px solid #e5e5e5;
                color: #222;
                text-align: left;
            }
        
            tr:hover td {
                background: #f6f6f6;
            }
        
            td form {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin: 0;
            }
        
            td input[type="submit"] {
                padding: 7px 10px;
                font-size: 12px;
            }
        
            .secondary-button {
                background: #fff !important;
                color: #111 !important;
            }
        
            .logout-area {
                display: flex;
                justify-content: flex-end;
                margin-top: 24px;
            }
        
            .logout-area form {
                margin: 0;
            }
            
            @media (max-width: 600px) {
                body {
                    padding: 20px 12px;
                }
        
                table {
                    min-width: 560px;
                }
            }
        </style>
        
    </head>
    <body>
        <main class="dashboard">
            <section class="balance-card">
            <?php 
            echo "<p>ようこそ、".$user_name."さん！<p>";
            
            $sql="SELECT SUM(CASE WHEN type='収入' THEN amount ELSE 0 END)-SUM(CASE WHEN type='支出' THEN amount ELSE 0 END) AS balance FROM user_money_data WHERE user_id=:user_id";
            
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
            $stmt->execute();
            
            $result=$stmt->fetch();
            
            $balance=$result["balance"] ?? 0;
            
            echo "<p>現在の残高：".$balance." 円</p>";
            ?>
        
            </section>
            
            <section class="panel">
                <h2>新しい収支の登録</h2>
                <form method="post" action="">
                    <label>種類</label>
                    <select name="type">
                        <option value="収入">収入</option>
                        <option value="支出">支出</option>
                    </select>
                        
                    <br>
                        
                    <label>日付</label>
                    <input type="date" name="date" required>
                        
                    <br>
                        
                    <label>金額</label>
                    <input type="number" name="amount"　required>円
                        
                    <br>
                    
                    <label>カテゴリ</label>
                    <select name="category">
                        <option value="食事">食事</option>
                        <option value="交通費">交通費</option>
                        <option value="買い物">買い物</option>
                        <option value="遊び">遊び</option>
                        <option value="家賃・水道・光熱費等">家賃・水道・光熱費等</option>
                        <option value="給与">給与</option>
                        <option value="その他">その他</option>
                    </select>
                        
                    <br>
                        
                    <label>メモ</label>
                    <input type="text" name="memo">
                        
                    <br>
                        
                    <input type="submit" name="syushi_register" value="収支情報を登録">
                </form>
            </section>
        
        
            <section class="panel">
            <h2>最近の記録</h2>
            <?php 
            if(!empty($_POST["show_all"])){
                $sql="SELECT * FROM user_money_data WHERE user_id=:user_id ORDER BY date DESC,money_id DESC";
            }
            else{
                $sql="SELECT * FROM user_money_data WHERE user_id=:user_id ORDER BY date DESC,money_id DESC LIMIT 10";
            }
            
            $stmt=$pdo->prepare($sql);
            $stmt->bindParam(":user_id",$user_id,PDO::PARAM_INT);
            $stmt->execute();
            $results=$stmt->fetchAll();
            
            echo "<div class='table-wrap'>";
            echo "<table border=1>
                 <tr>
                 <th>日付</th>
                 <th>カテゴリ</th>
                 <th>金額</th>
                 <th>メモ</th>
                 <th>操作</th>
                 </tr>";
            
            foreach($results as $row){
                echo "<tr>
                     <td>".$row['date']."</td>
                     <td>".$row["category"]."</td>";
                
                if($row["type"]=="収入"){
                    echo "<td>"."+".$row["amount"]."</td>";
                }
                else{
                    echo "<td>"."-".$row["amount"]."</td>";
                }
                    
                     
                echo "<td>".$row["memo"]."</td>
                     <td>
                        <form method='post' action=''>
                            <input type='hidden' name='money_id' value='".$row["money_id"]."'>
                            <input type='submit' name='edit' value='編集'>
                            <input type='submit' name='delete' value='削除'>
                        </form>
                     </td>
                     </tr>";
            }
            
            echo "</table>";
            echo "</div>";
            
            ?>
            <form method="post" action="">
                <input type="submit" name="show_all" value="すべての履歴を見る">
            </form>
        </section>
        
        <?php 
        if(!empty($edit_data)){
        ?>
        
        
        <section class="panel">
            <h2>収支情報の編集</h2>
            <form method="post" action="">
                <input type="hidden" name="money_id" value="<?php echo $edit_data["money_id"]; ?>">
                
                <label>種類</label>
                <select name="type">
                    <option value="収入">
                        収入
                    </option>
                    
                    <option value="支出">
                        支出
                    </option>
                </select>
                
                <br>
                
                <label>日付</label>
                <input type="date" name="date" value="<?php echo $edit_data["date"];?>">
                
                <br>
                
                <label>金額</label>
                <input type="number" name="amount" value="<?php echo $edit_data["amount"]; ?>">
                
                <br>
                
                <label>カテゴリ</label>
                <input type="text" name="category" value="<?php echo $edit_data["category"]; ?>">
                
                <br>
                
                <label>メモ</label>
                <input type="text" name="memo" value="<?php echo $edit_data["memo"]; ?>">
                
                <br>
                
                <input type="submit" name="update" value="更新">
            </form>
        
        </section>
        
        <?php
        }
        ?>
        
        <?php 
        if(!empty($_POST["update"])){
            echo "データを更新しました。";
        }
        else if(!empty($_POST["delete"])){
            echo "データを削除しました";
        }
        ?>
        
        <div class="logout-area">
            <form method="post" action="">
                <input type="submit" name="logout" value="ログアウト">
            </form>
        </div>
    </main>
    </body>
</html>
