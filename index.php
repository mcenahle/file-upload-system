<?php
$uploadOk = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $fileName = $_FILES['file']['name'];
        $fileTmp  = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['zip', 'rar'];

        if (in_array($fileExt, $allowed)) {
            if ($fileSize <= 100 * 1024 * 1024) {
                $uploadDir = "uploads/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $targetFile = $uploadDir . basename($fileName);
                if (move_uploaded_file($fileTmp, $targetFile)) {
                    $uploadOk = true;
                    $message = "文件上传成功：" . htmlspecialchars($fileName) . "。请关闭网页。";
                } else {
                    $message = "文件上传失败。如果问题重复出现，请复现错误并录屏，并询问网站管理者（葛琛昊）。";
                }
            } else {
                $message = "文件太大，最大只允许 100MB。";
            }
        } else {
            $message = "只允许上传 .zip 和 .rar 文件。";
        }
    } else {
        $message = "请选择要上传的文件。";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <title>文件上传系统</title>
    <link href="https://zy.mcenahle.org.cn/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://zy.mcenahle.org.cn/bootstrap/css/bootstrap-icons.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://zy.mcenahle.org.cn/ipv6-support-css/ipv6.css">
    <link rel="stylesheet" href="https://zy.mcenahle.org.cn/RemixIcon_Fonts/remixicon.css">
    <meta name="robots" content="noindex, nofollow">
    <style>
        a,
        a:hover,
        a:focus {
            text-decoration: none !important;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">文件上传系统</h3>
                        <p class="mb-4 text-center text-muted">仅支持 <b>.zip</b> 和 <b>.rar</b> 文件，最大 100 MB</p>
                        <div class="card">
                            <div class="card-header">消息</div>
                            <div class="card-body">
                                <ol>
                                    <li style="color:red;">请上传一个压缩包，压缩包内要包含原图和已经修过的图（是为一组），要一组到三组，即3-6张图片。压缩包命名不作规则，但要<b>包含你的名字</b>。</li>
                                    <li>由于钱旻璐已经提交过一次了，因此可以不用再次提交。</li>
                                    <li><i class="bi bi-clock-history"></i> 截止日期：12月23日（下周二）22:00。届时系统将关闭。在系统关闭前，可以在 <a href="/query"><u>这里</u> <i class="ri-arrow-right-up-long-line"></i></a> 查询你的文件是否已经成功提交到系统。</li>
                                </ol>
                            </div>
                        </div>
                        <br>
                        <?php if ($message): ?>
                        <div class="alert alert-<?= $uploadOk ? 'success' : 'danger' ?> text-center" role="alert">
                            <?= $message ?>
                        </div>
                        <?php endif; ?>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fileInput" class="form-label">选择文件</label>
                                <input class="form-control" type="file" id="fileInput" name="file" accept=".zip,.rar" required>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary mb-2" type="submit"><i class="bi bi-cloud-arrow-up"></i> 上传文件 <i class="ri-arrow-right-up-long-line"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <footer class="text-center mt-4 text-secondary small">
                    &copy; <?php echo date('Y'); ?> upload.mcenahle.org.cn。Powered by Mcenahle（葛琛昊）。
                    <br>
                    <p style="color:green;">本站支持<span id="ipv6"><a href="https://ipw.cn/ipv6webcheck/?site=upload.mcenahle.org.cn" target="_blank"><u>IPv6</u> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" width="12" height="12" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                    <g>
                                        <line style="fill:none;stroke:currentColor;stroke-width:8;stroke-miterlimit:10;" x1="37.552" y1="62.437" x2="90.841" y2="9.158"></line>
                                        <polygon style="stroke:currentColor;stroke-width:3;stroke-miterlimit:10;fill:currentColor;" points="53.551,6.274 93.726,6.274 93.726,46.449"></polygon>
                                        <path style="fill:none;stroke:currentColor;stroke-width:8;stroke-linejoin:round;stroke-miterlimit:10;" d="M34.666,24.065h-20.91
        c-2.736,0-4.953,2.218-4.953,4.953v57.226c0,2.736,2.218,4.953,4.953,4.953h57.226c2.736,0,4.953-2.218,4.953-4.953V65.338">
                                        </path>
                                    </g>
                                </svg></a></span>访问。</p>
                    <p><a href="https://beian.miit.gov.cn/" target="_blank"><u>沪ICP备2025116360号-1</u> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" width="12" height="12" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                <g>
                                    <line style="fill:none;stroke:currentColor;stroke-width:8;stroke-miterlimit:10;" x1="37.552" y1="62.437" x2="90.841" y2="9.158"></line>
                                    <polygon style="stroke:currentColor;stroke-width:3;stroke-miterlimit:10;fill:currentColor;" points="53.551,6.274 93.726,6.274 93.726,46.449"></polygon>
                                    <path style="fill:none;stroke:currentColor;stroke-width:8;stroke-linejoin:round;stroke-miterlimit:10;" d="M34.666,24.065h-20.91
        c-2.736,0-4.953,2.218-4.953,4.953v57.226c0,2.736,2.218,4.953,4.953,4.953h57.226c2.736,0,4.953-2.218,4.953-4.953V65.338">
                                    </path>
                                </g>
                            </svg></a><br><a href="https://www.beian.gov.cn/portal/registerSystemInfo?recordcode=31015102000182" target="_blank"><u>沪公网安备31015102000182号</u> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" width="12" height="12" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                                <g>
                                    <line style="fill:none;stroke:currentColor;stroke-width:8;stroke-miterlimit:10;" x1="37.552" y1="62.437" x2="90.841" y2="9.158"></line>
                                    <polygon style="stroke:currentColor;stroke-width:3;stroke-miterlimit:10;fill:currentColor;" points="53.551,6.274 93.726,6.274 93.726,46.449"></polygon>
                                    <path style="fill:none;stroke:currentColor;stroke-width:8;stroke-linejoin:round;stroke-miterlimit:10;" d="M34.666,24.065h-20.91
        c-2.736,0-4.953,2.218-4.953,4.953v57.226c0,2.736,2.218,4.953,4.953,4.953h57.226c2.736,0,4.953-2.218,4.953-4.953V65.338">
                                    </path>
                                </g>
                            </svg></a></p>
                </footer>
            </div>
        </div>
    </div>
    <script src="https://zy.mcenahle.org.cn/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>