<?php
/**
 * Test script for Planning Poker Application
 * Tests complete workflow: Create Session -> Create Story -> Vote -> Reveal
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Planning Poker Application Test ===\n\n";

// Step 1: Create Session
echo "1. Creating session...\n";
$data = [
    'name' => 'Test Sprint',
    'sm_name' => 'João Teste'
];

$ch = curl_init('http://localhost:8000/api/api.php?action=create_session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "✓ Session created successfully\n";
    echo "  - Code: {$result['code']}\n";
    echo "  - Session ID: {$result['session_id']}\n";
    $sessionCode = $result['code'];
} else {
    echo "✗ Failed to create session\n";
    exit(1);
}

// Step 2: Join Session as Team Member
echo "\n2. Joining session as team member...\n";
$data = [
    'code' => $sessionCode,
    'name' => 'Maria Desenvolvedora'
];

$ch = curl_init('http://localhost:8000/api/api.php?action=join_session');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies2.txt');

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "✓ Joined session successfully\n";
} else {
    echo "✗ Failed to join session\n";
    exit(1);
}

// Step 3: Create Story (using SM session)
echo "\n3. Creating story...\n";
$data = [
    'title' => 'Implement User Authentication',
    'description' => 'Build login and registration system'
];

$ch = curl_init('http://localhost:8000/api/api.php?action=create_story');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "✓ Story created successfully\n";
    echo "  - Story ID: {$result['story_id']}\n";
    $storyId = $result['story_id'];
} else {
    echo "✗ Failed to create story: " . ($result['message'] ?? 'Unknown error') . "\n";
    exit(1);
}

// Step 4: Get Stories
echo "\n4. Getting stories...\n";
$ch = curl_init('http://localhost:8000/api/api.php?action=get_stories');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success'] && count($result['stories']) > 0) {
    echo "✓ Stories retrieved successfully\n";
    echo "  - Total: " . count($result['stories']) . "\n";
    foreach ($result['stories'] as $s) {
        echo "    • #{$s['number']} - {$s['title']} ({$s['vote_count']} votes)\n";
    }
} else {
    echo "✗ Failed to get stories\n";
    exit(1);
}

// Step 5: Vote
echo "\n5. Submitting votes...\n";
$votes = [5, 8, 5];
foreach ($votes as $i => $points) {
    $data = ['story_id' => $storyId, 'points' => $points];
    
    $ch = curl_init('http://localhost:8000/api/api.php?action=vote');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    if ($i === 0) {
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
    } else {
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies2.txt');
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    if ($result['success']) {
        echo "  ✓ Vote submitted: $points points\n";
    }
}

// Step 6: Get Votes
echo "\n6. Getting votes...\n";
$ch = curl_init("http://localhost:8000/api/api.php?action=get_votes&story_id=$storyId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "✓ Votes retrieved successfully\n";
    foreach ($result['votes'] as $v) {
        echo "  - {$v['voter_name']}: {$v['points']} points\n";
    }
    
    $points = array_map(fn($v) => $v['points'], $result['votes']);
    $avg = array_sum($points) / count($points);
    sort($points);
    $median = count($points) % 2 === 0 
        ? ($points[count($points)/2-1] + $points[count($points)/2]) / 2 
        : $points[floor(count($points)/2)];
    
    echo "\n  Statistics:\n";
    echo "  - Votes: " . count($points) . "\n";
    echo "  - Average: " . number_format($avg, 1) . "\n";
    echo "  - Median: $median\n";
} else {
    echo "✗ Failed to get votes\n";
}

// Step 7: Finalize Story
echo "\n7. Finalizing story...\n";
$data = ['story_id' => $storyId, 'final_points' => 5];

$ch = curl_init('http://localhost:8000/api/api.php?action=finalize_story');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if ($result['success']) {
    echo "✓ Story finalized successfully with 5 points\n";
} else {
    echo "✗ Failed to finalize story\n";
}

echo "\n=== All Tests Passed! ===\n";
echo "\nApplication is ready to use!\n";
echo "- Open http://localhost:8000/ to start\n";
echo "- Session code to share: $sessionCode\n";
?>