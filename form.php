<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма заявки — Задание 5</title>
    <style>
        body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#667eea,#764ba2);margin:0;padding:40px 0;}
        .container{max-width:700px;margin:0 auto;background:white;border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,0.2);overflow:hidden;}
        header{background:#333;color:white;padding:30px;text-align:center;}
        .form-body{padding:40px;}
        label{display:block;margin:18px 0 6px;font-weight:600;}
        input,select,textarea{width:100%;padding:12px;border:2px solid #ddd;border-radius:8px;font-size:16px;box-sizing:border-box;}
        input:focus,select:focus,textarea:focus{border-color:#667eea;outline:none;}
        .radio-group{display:flex;gap:30px;margin:10px 0;}
        .error{color:#e74c3c;font-size:13px;margin-top:3px;margin-bottom:18px;}
        .success{background:#2ecc71;color:white;padding:15px;border-radius:8px;text-align:center;margin-bottom:25px;}
        button{background:#667eea;color:white;padding:15px 40px;font-size:18px;border:none;border-radius:8px;cursor:pointer;margin-top:20px;width:100%;}
        button:hover{background:#764ba2;}
        .error-input{border-color:#e74c3c !important;}
        .login-box{background:#f8f9fa;padding:20px;border-radius:12px;margin-bottom:25px;}
    </style>
</head>
<body>
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  foreach ($messages as $message) {
    print('<div style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px auto; border: 1px solid #c3e6cb; border-radius: 4px; max-width: 600px; text-align: center;">' . $message . '</div>');
  }
  print('</div>');
}
?>
<div class="container">
<div class="container">
    <header><h1>Форма заявки</h1><p>Программно-аппаратные средства Web — Задание 5</p></header>
    <div class="form-body">
        <?php foreach($messages as $m) echo $m; ?>

        <form method="post">
            <label>ФИО</label>
            <input type="text" name="fio" value="<?=htmlspecialchars($values['fio']??'')?>" class="<?=isset($errors['fio'])?'error-input':''?>">
            <?php if(isset($errors['fio'])) echo '<div class="error">'.$errors['fio'].'</div>'; ?>

            <label>Телефон</label>
            <input type="tel" name="phone" value="<?=htmlspecialchars($values['phone']??'')?>" class="<?=isset($errors['phone'])?'error-input':''?>">
            <?php if(isset($errors['phone'])) echo '<div class="error">'.$errors['phone'].'</div>'; ?>

            <label>e-mail</label>
            <input type="email" name="email" value="<?=htmlspecialchars($values['email']??'')?>" class="<?=isset($errors['email'])?'error-input':''?>">
            <?php if(isset($errors['email'])) echo '<div class="error">'.$errors['email'].'</div>'; ?>

            <label>Дата рождения</label>
            <input type="date" name="birth_date" value="<?=htmlspecialchars($values['birth_date']??'')?>" class="<?=isset($errors['birth_date'])?'error-input':''?>">
            <?php if(isset($errors['birth_date'])) echo '<div class="error">'.$errors['birth_date'].'</div>'; ?>

            <label>Пол</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" <?=($values['gender']??'')==='male'?'checked':''?>> Мужской</label>
                <label><input type="radio" name="gender" value="female" <?=($values['gender']??'')==='female'?'checked':''?>> Женский</label>
            </div>
            <?php if(isset($errors['gender'])) echo '<div class="error">'.$errors['gender'].'</div>'; ?>

            <label>Любимый язык программирования (можно несколько)</label>
            <select name="languages[]" multiple size="8" style="height:180px;" class="<?=isset($errors['languages'])?'error-input':''?>">
                <?php foreach($allowed_languages as $lang): ?>
                    <option value="<?=$lang?>" <?=in_array($lang,$values['languages']??[])?'selected':''?>><?=$lang?></option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($errors['languages'])) echo '<div class="error">'.$errors['languages'].'</div>'; ?>

            <label>Биография</label>
            <textarea name="biography" rows="6"><?=htmlspecialchars($values['biography']??'')?></textarea>

            <label>
                <input type="checkbox" name="contract" value="1" <?=($values['contract']??0)?'checked':''?>>
                С контрактом ознакомлен(а)
            </label>
            <?php if(isset($errors['contract'])) echo '<div class="error">'.$errors['contract'].'</div>'; ?>

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>