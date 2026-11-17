<?php
require __DIR__ . '/../src/bootstrap.php';
require_patient();

$pid = (int)$_SESSION['patient_id'];
$errors = [];
$maxFileSize = 5 * 1024 * 1024;
$photoDirectory = dirname(__DIR__) . '/storage/patient-photos';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $upload = $_FILES['passport_photo'] ?? null;

    if (!$upload || !isset($upload['error']) || is_array($upload['error'])) {
        $errors[] = 'Choose a passport photo to upload.';
    } elseif ($upload['error'] !== UPLOAD_ERR_OK) {
        $errors[] = match ($upload['error']) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'The photo must be 5 MB or smaller.',
            UPLOAD_ERR_NO_FILE => 'Choose a passport photo to upload.',
            UPLOAD_ERR_PARTIAL => 'The photo upload was incomplete. Please try again.',
            default => 'The photo could not be uploaded. Please try again.',
        };
    } elseif (!isset($upload['tmp_name']) || !is_string($upload['tmp_name']) || !is_uploaded_file($upload['tmp_name'])) {
        $errors[] = 'The uploaded photo could not be verified. Please try again.';
    } elseif ($upload['size'] > $maxFileSize) {
        $errors[] = 'The photo must be 5 MB or smaller.';
    } else {
        $imageInfo = getimagesize($upload['tmp_name']);
        $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']);
        $imageTypes = [
            'image/jpeg' => ['extension' => 'jpg', 'type' => IMAGETYPE_JPEG],
            'image/png' => ['extension' => 'png', 'type' => IMAGETYPE_PNG],
            'image/webp' => ['extension' => 'webp', 'type' => IMAGETYPE_WEBP],
        ];

        if (!$imageInfo || !isset($imageTypes[$mimeType]) || $imageInfo[2] !== $imageTypes[$mimeType]['type']) {
            $errors[] = 'Use a valid JPG, PNG, or WebP image.';
        } elseif ($imageInfo[0] > 6000 || $imageInfo[1] > 6000 || $imageInfo[0] * $imageInfo[1] > 20000000) {
            $errors[] = 'The image dimensions are too large. Choose a smaller photo.';
        } else {
            try {
                if (!is_dir($photoDirectory) && !mkdir($photoDirectory, 0750, true) && !is_dir($photoDirectory)) {
                    throw new RuntimeException('Could not create the private photo storage directory.');
                }

                $photoName = bin2hex(random_bytes(16)) . '.' . $imageTypes[$mimeType]['extension'];
                $photoPath = $photoDirectory . DIRECTORY_SEPARATOR . $photoName;

                if (!move_uploaded_file($upload['tmp_name'], $photoPath)) {
                    throw new RuntimeException('Could not save the uploaded photo. Please try again.');
                }
            } catch (RuntimeException $e) {
                $errors[] = $e->getMessage();
            }

            if (!$errors) {
                $previousSt = $db->prepare('SELECT passport_photo FROM patients WHERE patient_id=?');
                $previousSt->execute([$pid]);
                $previousPhoto = $previousSt->fetchColumn();

                try {
                    $update = $db->prepare('UPDATE patients SET passport_photo=? WHERE patient_id=?');
                    $update->execute([$photoName, $pid]);
                } catch (Throwable $e) {
                    if (is_file($photoPath) && !unlink($photoPath)) {
                        error_log('Could not remove an unreferenced patient photo after a database error.');
                    }
                    throw $e;
                }

                if (is_string($previousPhoto) && preg_match('/\A[a-f0-9]{32}\.(?:jpg|png|webp)\z/', $previousPhoto)) {
                    $previousPath = $photoDirectory . DIRECTORY_SEPARATOR . $previousPhoto;
                    if (is_file($previousPath) && !unlink($previousPath)) {
                        error_log('Could not remove a replaced patient photo.');
                    }
                }

                flash('Your passport photo has been uploaded.', 'success');
                redirect('/dashboard.php');
            }
        }
    }
}

$title = 'Upload passport photo';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Passport photo</h3>
    <p class="text-muted">Upload a JPG, PNG, or WebP image up to 5 MB.</p>
    <?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
    <form id="passport-photo-form" method="post" enctype="multipart/form-data" class="card card-body">
      <?= csrf_field() ?>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?= $maxFileSize ?>">
      <label class="form-label" for="passport_photo">Choose your photo</label>
      <input class="form-control mb-3" id="passport_photo" type="file" name="passport_photo"
             accept="image/jpeg,image/png,image/webp" required>
      <div class="d-flex gap-2">
        <button class="btn btn-success" type="submit">Upload photo</button>
        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/dashboard.php">Cancel</a>
      </div>
    </form>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>

