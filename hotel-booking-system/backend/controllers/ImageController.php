<?php

class ImageController
{
    public static function index(array $params): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM hotel_images WHERE hotel_id = ? ORDER BY sort_order, id');
        $stmt->execute([(int) $params['id']]);
        Response::success(['images' => $stmt->fetchAll()]);
    }

    public static function store(array $params): void
    {
        $hotelId = (int) $params['id'];
        $app = require __DIR__ . '/../config/app.php';
        $uploadDir = $app['upload_path'];
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $pdo = Database::connection();
        $saved = [];

        if (!empty($_FILES['images'])) {
            $files = $_FILES['images'];
            $count = is_array($files['name']) ? count($files['name']) : 1;
            for ($i = 0; $i < $count; $i++) {
                $name = is_array($files['name']) ? $files['name'][$i] : $files['name'];
                $tmp = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
                $error = is_array($files['error']) ? $files['error'][$i] : $files['error'];
                if ($error !== UPLOAD_ERR_OK) {
                    continue;
                }
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                    continue;
                }
                $filename = 'hotel_' . $hotelId . '_' . uniqid() . '.' . $ext;
                $path = $uploadDir . '/' . $filename;
                if (move_uploaded_file($tmp, $path)) {
                    $url = $app['upload_url'] . '/' . $filename;
                    $pdo->prepare(
                        'INSERT INTO hotel_images (hotel_id, image_path, sort_order) VALUES (?, ?, ?)'
                    )->execute([$hotelId, $url, $i]);
                    $saved[] = $url;
                }
            }
        }

        $data = Helpers::input();
        if (!empty($data['image_url'])) {
            $pdo->prepare('INSERT INTO hotel_images (hotel_id, image_path) VALUES (?, ?)')
                ->execute([$hotelId, $data['image_url']]);
            $saved[] = $data['image_url'];
        }

        Response::success(['images' => $saved], 'Images uploaded', 201);
    }

    public static function destroy(array $params): void
    {
        $pdo = Database::connection();
        $pdo->prepare('DELETE FROM hotel_images WHERE id = ?')->execute([(int) $params['id']]);
        Response::success(null, 'Image deleted');
    }
}
