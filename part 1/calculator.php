<?php 
    $result = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $a = $_POST['a'] ?? null;
        $b = $_POST['b'] ?? null;
        $op = $_POST['op'] ?? null;

        if ($a === '' || $b === '') {
            $result = "Введите оба числа";
        } elseif (!is_numeric($a) || !is_numeric($b)) {
            $result = "Нужно вводить числа!";
        } else {
            $a = (float)$a;
            $b = (float)$b;

            switch ($op) {
                case '+':
                    $result = $a + $b;
                    break;
                case '-':
                    $result = $a - $b;
                    break;
                case '*':
                    $result = $a * $b;
                    break;
                case '/':
                    if ($b === 0) {
                        $result = "На ноль делить нельзя!";
                    } else {
                        $result = $a / $b;
                    }
                    break;
                default:
                    $result = "Выберите операцию";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор на PHP</title>
</head>
<body>
    <form method="post">
        <input type="text" name="a" value="<?= htmlspecialchars($_POST['a'] ?? '') ?>" >
        <select name="op">
            <option value="+" <?= (($_POST['op'] ?? '') === '+') ? 'selected' : '' ?>>+</option>
            <option value="-" <?= (($_POST['op'] ?? '') === '-') ? 'selected' : '' ?>>-</option>
            <option value="*" <?= (($_POST['op'] ?? '') === '*') ? 'selected' : '' ?>>*</option>
            <option value="/" <?= (($_POST['op'] ?? '') === '/') ? 'selected' : '' ?>>/</option>
        </select>
        <input type="text" name="b" value="<?= htmlspecialchars($_POST['b'] ?? '') ?>">
        <button type="submit">Посчитать</button>
    </form>

    <?php if ($result !== ''): ?>
        <p>Результат: <?= htmlspecialchars((string)$result) ?></p>
    <?php endif; ?>
</body>
</html>