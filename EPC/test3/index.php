<?php
/**
 * Скрипт для поиска файлов в директории datafiles.
 * Выводит имена файлов, состоящих из букв и цифр латинского алфавита и имеющих расширение .ixt
 * P.S Добавил папку datafiles с различными расширениями и различным алфавитом и знаками, чтобы было видно, что сортировка работает корректно.
 */

$directory = 'datafiles';

/**
 * Поиск файлов с использованием регулярного выражения.
 * 
 * @param string $directory Директория для поиска файлов.
 * @return array Список файлов, удовлетворяющих условиям.
 */
function findFiles($directory)
{
    $files = scandir($directory);
    $result = [];

    foreach ($files as $file) {
        // Регулярное выражение, требующее наличие хотя бы одной цифры и хотя бы одной буквы
        if (preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9]+\.ixt$/', $file)) {
            $result[] = $file;
        }
    }

    // Сортировка имен файлов по алфавиту
    sort($result);

    return $result;
}

// Выводим найденные файлы
$files = findFiles($directory);
echo "Найденные файлы: <br>";
// Выводим найденные файлы построчно
foreach ($files as $file) {
    echo $file . "<br>";
}