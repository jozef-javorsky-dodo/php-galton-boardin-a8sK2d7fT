php
<?php

const DEFAULT_ROWS = 9;
const DEFAULT_BALLS = 256;
const MAX_BALLS = 10000;
const MAX_ROWS = 50;

$rows = getRows();
$balls = getBalls();
$bins = simulateGaltonBoard($rows, $balls);

echo "Distribution of balls:\n";
foreach (range(0, $rows) as $i) {
    echo "Bin $i: " . str_repeat("~", $bins[$i]) . "\n";
}

$totalBalls = array_sum($bins);
if ($totalBalls > 0) {
    $stats = calculateStatistics($bins, $totalBalls);
    echo "\nMean: " . number_format($stats['mean'], 2) . "\n";
    echo "Variance: " . number_format($stats['variance'], 2) . "\n";
    echo "Standard Deviation: " . number_format($stats['stdDev'], 2) . "\n";
}

function simulateGaltonBoard(int $rows, int $balls): array
{
    $bins = array_fill(0, $rows + 1, 0);
    for ($i = 0; $i < $balls; $i++) {
        $ballPosition = 0;
        for ($j = 0; $j < $rows; $j++) {
            $ballPosition += rand(0, 1) ? 1 : 0;
        }
        $bins[$ballPosition]++;
    }
    return $bins;
}

function calculateStatistics(array $bins, int $totalBalls): array
{
    $mean = 0;
    foreach ($bins as $i => $count) {
        $mean += $i * $count;
    }
    $mean /= $totalBalls;

    $variance = 0;
    foreach ($bins as $i => $count) {
        $variance += $count * pow($i - $mean, 2);
    }
    $variance /= $totalBalls;

    return [
        'mean' => $mean,
        'variance' => $variance,
        'stdDev' => sqrt($variance),
    ];
}

function getRows(): int
{
    $rows = filter_var(readline("Enter the number of rows: "), FILTER_VALIDATE_INT);
    if ($rows === false || $rows <= 0 || $rows > MAX_ROWS) {
        echo "Invalid input for rows. Using default value: " . DEFAULT_ROWS . "\n";
        return DEFAULT_ROWS;
    }
    return $rows;
}

function getBalls(): int
{
    $balls = filter_var(readline("Enter the number of balls: "), FILTER_VALIDATE_INT);
    if ($balls === false || $balls <= 0 || $balls > MAX_BALLS) {
        echo "Invalid input for balls. Using default value: " . DEFAULT_BALLS . "\n";
        return DEFAULT_BALLS;
    }
    return $balls;
}
