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
<div class="header-gradient">
    <div class="header-content">
        <div class="header-icon">📝</div>
        <h1>Bài Kiểm Tra Trắc Nghiệm</h1>
        <p class="subtitle">Lớp 65HTT1 - Khóa học CSE485.202401</p>
    </div>
</div>

<div class="stats-bar">
    <div class="stat-item">
        <div class="stat-number"><?php echo count($questions); ?></div>
        <div class="stat-label">Tổng Câu Hỏi</div>
    </div>
    <?php if ($results !== null): ?>
    <div class="stat-item">
        <div class="stat-number" style="color:#22c55e"><?php echo $score; ?></div>
        <div class="stat-label">Câu Đúng</div>
    </div>
    <div class="stat-item">
        <div class="stat-number" style="color:#ef4444"><?php echo count($questions) - $score; ?></div>
        <div class="stat-label">Câu Sai</div>
    </div>
    <?php else: ?>
    <div class="stat-item">
        <div class="stat-number">--</div>
        <div class="stat-label">Câu Đúng</div>
    </div>
    <div class="stat-item">
        <div class="stat-number">--</div>
        <div class="stat-label">Điểm Số</div>
    </div>
    <?php endif; ?>
</div>

<div class="container">

    <form method="post">
        <?php foreach ($questions as $i => $q):
            $multi = count($q['answer']) > 1;
        ?>
        <div class="question-card <?php if ($results !== null) echo ($results[$i]['isCorrect'] ? 'correct' : 'wrong'); ?>">
            <div class="question-header">
                <span class="question-number">Câu <?php echo $i+1; ?></span>
                <?php if ($results !== null): ?>
                    <span class="result-badge <?php echo $results[$i]['isCorrect'] ? 'badge-correct' : 'badge-wrong'; ?>">
                        <?php echo $results[$i]['isCorrect'] ? '✓ Đúng' : '✗ Sai'; ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="question-text"><?php echo esc($q['question']); ?></div>
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
                <label class="option <?php if ($results !== null && in_array($letter, $results[$i]['user'])) echo 'selected'; ?>">
                    <input type="<?php echo $multi ? 'checkbox' : 'radio'; ?>" name="<?php echo $name; ?>" value="<?php echo esc($value); ?>" <?php echo $checked; ?> <?php if ($results !== null) echo 'disabled'; ?>>
                    <span class="option-letter"><?php echo esc($letter); ?></span>
                    <span class="option-text"><?php echo esc($text); ?></span>
                    <?php if ($results !== null && in_array($letter, $results[$i]['correct'])): ?>
                        <span class="correct-mark">✓</span>
                    <?php endif; ?>
                </label>
                <?php endforeach; ?>
            </div>

        </div>
        <?php endforeach; ?>

        <div class="actions">
            <button type="submit">Nộp bài</button>
            <button type="button" onclick="location.reload()">Làm lại</button>
        </div>
    </form>
</div>
</body>
</html>
