<?php
session_start();

$result = "0";
$calculation_input = "";
$last_operator = "";

// Fungsi untuk menangani operator ^
function handlePowerOperator($input, $lastOperator) {
    $operator = "^";
    if (empty($input) || substr($input, -1) == $lastOperator) {
        return [substr($input, 0, -1) . $operator, str_replace('^', '**', substr($input, 0, -1)) . "**", $operator];
    } else {
        return [$_POST["input"] . $operator, str_replace('^', '**', $_POST["input"]) . "**", $operator];
    }
}

// fungsi untuk operator khusus
function handleSpecialOperator($input, $lastOperator, $operator) {
    $specialOperators = ["×" => "*", "÷" => "/"];
    if (empty($input) || substr($input, -1) == $lastOperator) {
        return [substr($input, 0, -1) . $operator, substr($input, 0, -1) . $specialOperators[$operator], $operator];
    } else {
        return [$_POST["input"] . $operator, $_POST["input"] . $specialOperators[$operator], $operator];
    }
}


// Fungsi untuk menangani operator lainnya
function handleOperator($input, $lastOperator, $operator) {
    if (empty($input) || substr($input, -1) == $lastOperator) {
        return [substr($input, 0, -1) . $operator, substr($input, 0, -1), $operator];
    } else {
        return [$_POST["input"] . $operator, $_POST["input"], $operator];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["operator"])) {
        $operator = $_POST["operator"];
        switch ($operator) {
            case "clearall":
                $result = "0";
                $calculation_input = "";
                $last_operator = "";
                break;
            case "clear":
                $result = substr($_POST["input"], 0, -1);
                $calculation_input = $result;
                if (empty($result)) {
                    // Jika input kosong setelah penghapusan karakter, atur nilai ke "0"
                    $result = "0";
                }
                break;
            case "^":
                list($result, $calculation_input, $last_operator) = handlePowerOperator($_POST["input"], $last_operator);
                break;
            case "×":
            case "÷":
            list($result, $calculation_input, $last_operator) = handleSpecialOperator($_POST["input"], $last_operator, $operator);
                        break;
            case "equal":
                try {
                    $calculation_input = str_replace(['^', '×', '÷'], ['**', '*', '/'], $_POST["input"]);
                    $result = eval('return ' . $calculation_input . ';');
                    $last_operator = "";
                } catch (Throwable $e) {
                    $result = "Error: " . $e->getMessage();
                    $last_operator = "";
                }
                break;
            default:
                list($result, $calculation_input, $last_operator) = handleOperator($_POST["input"], $last_operator, $operator);
                break;
        }
    } elseif (isset($_POST["number"])) {
      if ($_POST["input"] == "0") {
          // Jika input adalah 0, ganti dengan nomor yang baru
          $result = $_POST["number"];
          $calculation_input = $result;
      } else {
          $result = $_POST["input"] . $_POST["number"];
          $calculation_input = $result;
      }
      $last_operator = "";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Fira+Code">
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
        <div class="calculator">
          <form method="post" >
            <input type="text" class="maininput" name="input" value="<?php echo htmlspecialchars($result); ?>"/>
            <div class="buttons">
              <button type="submit" name="operator" value="clearall">AC</button>
              <button type="submit" name="operator" value="clear">C</button>
              <button type="submit" name="operator" value="^">x<sup>n</sup></button></span>
              <button type="submit" name="operator" value="÷">÷</button></span>
              <button type="submit" name="number" value="7">7</button>
              <button type="submit" name="number" value="8">8</button>
              <button type="submit" name="number" value="9">9</button>
              <button type="submit" name="operator" value="×">×</button></span>
              <button type="submit" name="number" value="4">4</button>
              <button type="submit" name="number" value="5">5</button>
              <button type="submit" name="number" value="6">6</button>
              <button type="submit" name="operator" value="-">-</button></span>
              <button type="submit" name="number" value="1">1</button>
              <button type="submit" name="number" value="2">2</button>
              <button type="submit" name="number" value="3">3</button>
              <button type="submit" name="operator" value="+">+</button></span>
              <button type="submit" name="number" value="0">0</button>
              <button type="submit" name="number" value=".">.</button>
              <button type="submit" name="operator" value="equal">=</button>
            </div>
          </form>
        </div>
        <footer>Made with 🔥 by Zi Xuan.</footer>
  </body>
</html>


