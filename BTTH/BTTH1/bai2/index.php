<?php
$quizFile = __DIR__ . '/Quiz.txt';

function parseQuiz($path) {
    $lines = file($path, FILE_IGNORE_NEW_LINES);
    $questions = [];
    $current = null;

    foreach ($lines as $raw) {
        $line = trim($raw);
        if ($line === '') {
            continue;
        }

        if (stripos($line, 'ANSWER:') === 0) {
            $ansPart = trim(substr($line, 7));
            $ansParts = preg_split('/\s*,\s*/', $ansPart);
            $ansParts = array_map('trim', $ansParts);
            if ($current !== null) {
                $current['answer'] = $ansParts;
                $questions[] = $current;
            }
            $current = null;
        } elseif (preg_match('/^[A-Z]\./u', $line)) {
            $letter = mb_substr($line, 0, 1);
            $text = trim(mb_substr($line, 2));
            if ($current === null) {
                $current = ['question' => '', 'options' => []];
            }
            $current['options'][$letter] = $text;
        } else {
            if ($current === null) {
                $current = ['question' => '', 'options' => []];
            }
            if ($current['question'] === '') {
                $current['question'] = $line;
            } else {
                $current['question'] .= ' ' . $line;
            }
        }
    }

    return $questions;
}

$questions = parseQuiz($quizFile);
$results = null;
$score = 0;

function normalizeAnswers($arr) {
    $out = [];
    foreach ($arr as $a) {
        $a = trim($a);
        if ($a === '') continue;
        $out[] = strtoupper($a);
    }
    sort($out);
    return $out;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $results = [];
    foreach ($questions as $i => $q) {
        $key = 'q' . $i;
        $user = [];
        if (isset($_POST[$key])) {
            $val = $_POST[$key];
            if (is_array($val)) $user = $val;
            else $user = [$val];
        }
        $userNorm = normalizeAnswers($user);
        $correctNorm = normalizeAnswers($q['answer']);
        $isCorrect = ($userNorm === $correctNorm);
        if ($isCorrect) $score++;
        $results[$i] = [
            'user' => $userNorm,
            'correct' => $correctNorm,
            'isCorrect' => $isCorrect,
        ];
    }
}

function esc($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Bài kiểm tra trắc nghiệm</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Bài kiểm tra trắc nghiệm</h1>

    <?php if ($results !== null): ?>
        <div class="summary">Bạn trả lời đúng <strong><?php echo $score; ?></strong> trên <strong><?php echo count($questions); ?></strong>.</div>
    <?php endif; ?>

    <form method="post">
        <?php foreach ($questions as $i => $q):
            $multi = count($q['answer']) > 1;
        ?>
        <fieldset class="question <?php if ($results !== null) echo ($results[$i]['isCorrect'] ? 'correct' : 'wrong'); ?>">
            <legend> Câu <?php echo $i+1; ?>: <?php echo esc($q['question']); ?></legend>
            <div class="options">
                <?php foreach ($q['options'] as $letter => $text):
                    $name = 'q' . $i . ($multi ? '[]' : '');
                    $value = $letter;
                    $checked = '';
                    if ($results !== null) {
                        $user = $results[$i]['user'];
                        if (in_array($letter, $user)) $checked = 'checked';
                    } else {
                        // preserve POST when validation fails
                        if (isset($_POST['q'.$i])) {
                            $val = $_POST['q'.$i];
                            if (is_array($val) && in_array($letter, $val)) $checked = 'checked';
                            if (!is_array($val) && $val === $letter) $checked = 'checked';
                        }
                    }
                ?>
                <label class="option">
                    <input type="<?php echo $multi ? 'checkbox' : 'radio'; ?>" name="<?php echo $name; ?>" value="<?php echo esc($value); ?>" <?php echo $checked; ?>>
                    <span class="letter"><?php echo esc($letter); ?>.</span>
                    <span class="text"><?php echo esc($text); ?></span>
                </label>
                <?php endforeach; ?>
            </div>

            <?php if ($results !== null):
                $user = $results[$i]['user'];
                $correct = $results[$i]['correct'];
            ?>
            <div class="feedback">
                <div><strong>Đáp án đúng:</strong>
                    <?php foreach ($correct as $c): ?>
                        <span class="badge correct-badge"><?php echo esc($c); ?></span>
                    <?php endforeach; ?>
                </div>
                <div><strong>Đáp án của bạn:</strong>
                    <?php if (empty($user)): ?> <em>Không trả lời</em>
                    <?php else: ?>
                        <?php foreach ($user as $u): ?>
                            <span class="badge <?php echo in_array($u, $correct) ? 'correct-badge' : 'wrong-badge'; ?>"><?php echo esc($u); ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </fieldset>
        <?php endforeach; ?>

        <div class="actions">
            <button type="submit">Nộp bài</button>
            <button type="button" onclick="location.reload()">Làm lại</button>
        </div>
    </form>
</div>
</body>
</html>
