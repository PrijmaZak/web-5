<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задание 5</title>
    <style>
        body{font-family:'Segoe UI',sans-serif;background:linear-gradient(135deg,#667eea,#764ba2);margin:0;padding:60px 0;position:relative;}
        .top-nav {position:absolute;top:20px;right:20px;display:flex;gap:10px;}
        .nav-btn {background:rgba(255,255,255,0.2);color:white;padding:8px 16px;border:1px solid white;border-radius:5px;text-decoration:none;transition:0.3s;}
        .nav-btn:hover {background:white;color:#764ba2;}
        .container{max-width:700px;margin:0 auto;background:white;border-radius:20px;box-shadow:0 15px 35px rgba(0,0,0,0.2);overflow:hidden;}
        header{background:#333;color:white;padding:20px;text-align:center;}
        .form-body{padding:40px;}
        label{display:block;margin:15px 0 5px;font-weight:600;}
        input,select,textarea{width:100%;padding:10px;border:2px solid #ddd;border-radius:8px;box-sizing:border-box;}
        .error-input{border-color:#e74c3c !important;}
        .error-text{color:#e74c3c;font-size:12px;margin-top:4px;}
        button{background:#667eea;color:white;padding:15px;border:none;border-radius:8px;cursor:pointer;width:100%;font-size:18px;margin-top:20px;}
        .msg-box{background:#d4edda;color:#155724;padding:15px;margin:0 auto 20px;border:1px solid #c3e6cb;max-width:700px;border-radius:8px;text-align:center;}
    </style>
</head>
<body>

<div class="top-nav">
    <?php if ($is_logged): ?>
        <a href="logout.php" class="nav-btn">Регистрация (Новый ID)</a>
    <?php else: ?>
        <a href="login.php" class="nav-btn">Вход</a>
    <?php endif; ?>
</div>

<?php if (!empty($messages)): ?>
    <?php foreach ($messages as $m): ?>
        <div class="msg-box"><?= $m ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="container">
    <header><h2>Анкета (Задание 5)</h2></header>
    <div class="form-body">
        <form method="post">
            <label>ФИО</label>
            <input type="text" name="fio" value="<?= htmlspecialchars($values['fio'] ?? '') ?>" class="<?= isset($errors['fio']) ? 'error-input' : '' ?>">
            <?php if(isset($errors['fio'])) echo '<div class="error-text">'.$errors['fio'].'</div>'; ?>

            <label>Телефон</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($values['phone'] ?? '') ?>" class="<?= isset($errors['phone']) ? 'error-input' : '' ?>">
            <?php if(isset($errors['phone'])) echo '<div class="error-text">'.$errors['phone'].'</div>'; ?>

            <label>E-mail</label>
            <input type="email" name="email" value="<?= htmlspecialchars($values['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'error-input' : '' ?>">
            <?php if(isset($errors['email'])) echo '<div class="error-text">'.$errors['email'].'</div>'; ?>

            <label>Дата рождения</label>
            <input type="date" name="birth_date" value="<?= htmlspecialchars($values['birth_date'] ?? '') ?>">

            <label>Пол</label>
            <input type="radio" name="gender" value="male" style="width:auto" <?= ($values['gender']??'')=='male'?'checked':'' ?>> Муж
            <input type="radio" name="gender" value="female" style="width:auto" <?= ($values['gender']??'')=='female'?'checked':'' ?>> Жен

            <label>Языки</label>
            <select name="languages[]" multiple size="5">
                <?php foreach($allowed_languages as $lang): ?>
                    <option value="<?=$lang?>" <?= in_array($lang, $values['languages']??[]) ? 'selected' : '' ?>><?=$lang?></option>
                <?php endforeach; ?>
            </select>

            <label>Биография</label>
            <textarea name="biography"><?= htmlspecialchars($values['biography'] ?? '') ?></textarea>

            <label><input type="checkbox" name="contract" style="width:auto" <?= ($values['contract']??0)?'checked':'' ?>> Согласен</label>
            <?php if(isset($errors['contract'])) echo '<div class="error-text">'.$errors['contract'].'</div>'; ?>

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>
</body>
</html>
