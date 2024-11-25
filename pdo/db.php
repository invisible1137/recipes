<?php

// файл db/db.php
require_once 'connection.php';

function getAll(string $table) {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM $table");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getById(string $table, string $id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getByProp(string $table, string $prop, string $value) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE $prop = :value");
    $stmt->execute(['value' => $value]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function create(string $table, array $values) {
    global $pdo;
    $columns = implode(', ', array_keys($values));
    $placeholders = ':' . implode(', :', array_keys($values));
    $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
    $stmt->execute($values);
    return $pdo->lastInsertId();
}

function update(string $table, array $values) {
    global $pdo;
    $id = $values['id'];
    unset($values['id']);
    $set = '';
    foreach ($values as $key => $value) {
        $set .= "$key = :$key, ";
    }
    $set = rtrim($set, ', ');
    $stmt = $pdo->prepare("UPDATE $table SET $set WHERE id = :id");
    $values['id'] = $id;
    $stmt->execute($values);
    return $id;
}

function delete(string $table, string $id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

