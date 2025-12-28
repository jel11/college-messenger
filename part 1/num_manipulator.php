<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['op'])) {
            $op = $_POST['op'];
        switch ($op) {
            case 'min_max':
                echo '<form method="POST">
        <input type="hidden" name="op" value="min_max_result">
        <label><input name="numbers" required>Введите числа через запятую</label>
        <button type="submit">Вычислить максимум и минимум</button>
                      </form>';
                break;
            case 'facto':
                echo '<form method="POST">
        <input type="hidden" name="op" value="facto_result">
        <label><input name="number" type="number" required>Введите число</label>
        <button type="submit">Вычислить факториал</button>
                      </form>';
                break;
            case 'is_even':
                echo '<form method="POST">
        <input type="hidden" name="op" value="is_even_result">
        <label><input name="number" type="number" required>Введите число</label>
        <button type="submit">Проверить чётность</button>
                      </form>';
                break;
            case 'ceil':
                echo '<form method="POST">
        <input type="hidden" name="op" value="ceil_result">
        <label><input name="numbers" required>Введите число</label>
        <button type="submit">Округлить</button>
                      </form>';
                break;
            case 'calc':
                echo '<form method="POST">
        <input type="hidden" name="op" value="calc_result">
        <label>Число 1: <input name="num1" type="number" required></label>
        <label>Операция: 
        <select name="operation">
        <option value="+">+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
        </select>
        </label>
        <label>Число 2: <input name="num2" type="number" required></label>
        <button type="submit">Вычислить</button>
                      </form>';
                break;
            case 'min_max_result':
                $numbers = array_map('trim', explode(',', $_POST['numbers'] ?? ''));
                $numbers = array_filter($numbers, 'is_numeric');
                if ($numbers) {
                    echo "Максимум: " .max($numbers).", минимум: ".min($numbers);
                }
                break;
            case 'facto_result':
                $num = intval($_POST['number'] ?? 0);
                $fact = 1;
                for ($i=2; $i <= $num; $i++) {
                    $fact *= $i;
                }
                echo "Факториал $num = $fact";
                break;
            case 'is_even_result':
                $num = intval($_POST['number'] ?? 0);
                echo ($num % 2 === 0) ? "$num - четное число" : "$num - нечетное число";
                break;
            case 'ceil_result':
                $num = floatval($_POST['number'] ?? 0);
                echo "Округление $num = ".ceil($num);
                break;
            case 'calc_result':
                $num1 = floatval($_POST['num1'] ?? 0);
                $num2 = floatval($_POST['num2'] ?? 0);
                $operation = $_POST['operation'] ?? 0;
                switch($operation) {
                    case '+':
                        $res = $num1 + $num2;
                        break;
                    case '-':
                        $res = $num1 - $num2;
                        break;
                    case '*':
                        $res = $num1 * $num2;
                        break;
                    case '/':
                        $res = ($num2 != 0) ? $num1 / $num2 : "На ноль делить нельзя!";
                    default:
                        $res = "Неизвестная операция";
                }
                echo "Результат - $res";
                break;
        }
    }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Программа для манипуляций с числами</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        max-width: 450px;
        margin: 30px auto;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        color: #333;
    }
    form {
        margin-top: 20px;
        background: white;
        padding: 15px;
        border-radius: 6px;
        box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }
    select, input[type="text"], input[type="number"] {
        display: block;
        width: 100%;
        margin: 10px 0 15px;
        padding: 8px;
        font-size: 16px;
        border-radius: 4px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }
    button {
        background-color: #0066cc;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #004999;
    }
    label {
        font-weight: bold;
        color: #555;
    }
    .result {
        margin-top: 20px;
        padding: 10px;
        background-color: #e8f0fe;
        border-radius: 6px;
        border: 1px solid #c3d2fc;
        color: #2e3a87;
        font-weight: 700;
        text-align: center;
    }
</style>

</head>
<body>
    <form method="POST">
        <select name="op" onchange="this.form.submit()">
            <option value="min_max" <?= (($_POST['op'] ?? '') === 'min_max') ? 'selected' : '' ?> >Поиск максимума/минимума</option>
            <option value="facto" <?= (($_POST['op'] ?? '') === 'facto') ? 'selected' : '' ?> >Вычисление факториала</option>
            <option value="is_even" <?= (($_POST['op'] ?? '') === 'is_even') ? 'selected' : '' ?> >Проверка на четность/нечетность</option>
            <option value="ceil" <?= (($_POST['op'] ?? '') === 'ceil') ? 'selected' : '' ?> >Округление</option>
            <option value="calc" <?= (($_POST['op'] ?? '') === 'calc') ? 'selected' : '' ?> >Калькулятор</option>
        </select>
    </form>
</body>
</html>