<?php
require 'connect_to_database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $ids = $data['ids'] ?? [];

    if (empty($ids)) {
        echo json_encode(['success' => false, 'error' => 'Ei valittuja projekteja']);
        exit;
    }

    if ($action === 'reserve') {
        $reservedTo = $data['reserved_to'] ?? '';
        if (empty($reservedTo)) {
            echo json_encode(['success' => false, 'error' => 'Ei nimeä']);
            exit;
        }
        $success = reserveProject($ids, $reservedTo);
        echo json_encode(['success' => $success]);
        exit;
    }
    if ($action === 'unreserve') {
        $success = unreserveProject($ids);
        echo json_encode(['success' => $success]);
        exit;
    }
    echo json_encode(['success' => false, 'error' => 'Tuntematon toiminto']);
    exit;
}
function reserveProject(array $ids, string $reservedTo): bool
{
    $connection = connectToDatabase();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = 'is' . str_repeat('i', count($ids));
    $params = array_merge([1, $reservedTo], $ids);

    $stmt = $connection->prepare("UPDATE PROJECT_DATA SET PROJECT_RESERVED = ?, RESERVED_TO = ? WHERE PROJECT_ID IN ($placeholders)");

    $stmt->bind_param($types, ...$params);
    $result = $stmt->execute();
    $stmt->close();
    $connection->close();
    return $result;
}


function unreserveProject(array $ids): bool
{
    $connection = connectToDatabase();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));

    $stmt = $connection->prepare("UPDATE PROJECT_DATA SET PROJECT_RESERVED = 0, RESERVED_TO = '' WHERE PROJECT_ID IN ($placeholders)");

    $stmt->bind_param($types, ...$ids);
    $result = $stmt->execute();
    $stmt->close();
    $connection->close();
    return $result;
}
