<?php
// 获取模糊查询结果（Ajax方式返回JSON）
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $query = trim($_GET['q'] ?? '');
    $result = [];

    // 禁止查询 zip 或 rar，防止枚举所有文件
    if ($query === '' || strtolower($query) === '.zip' || strtolower($query) === '.' || strtolower($query) === 'zip' || strtolower($query) === 'rar' || strtolower($query) === '24' || strtolower($query) === '广告' || strtolower($query) === '摄影') {
        header('Content-Type: application/json');
        echo json_encode(['error' => '禁止查询该关键字！']);
        exit;
    }

    $dir = 'uploads/';
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            if (stripos($file, $query) !== false) {
                $result[] = $file;
            }
        }
    }
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>模糊查询文件是否上传</title>
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
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body">
                        <h3 class="card-title mb-4 text-center">模糊查询文件是否上传</h3>
                        <form id="searchForm" onsubmit="return false;">
                            <div class="mb-3">
                                <label for="filename" class="form-label">请输入文件名关键字（支持模糊匹配）</label>
                                <input type="text" class="form-control" id="filename" name="filename" placeholder="如 abc" required>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i> 查询
                                </button>
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

    <!-- Modal -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="resultModalLabel">系统</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
          </div>
          <div class="modal-body" id="modalBody">
            <!-- 查询结果会插入这里 -->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://zy.mcenahle.org.cn/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    // 前端Ajax提交模糊查询
    document.getElementById('searchForm').addEventListener('submit', function () {
        const keyword = document.getElementById('filename').value.trim();
        if (!keyword) return;
        fetch('query.php?ajax=1&q=' + encodeURIComponent(keyword))
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.error) {
                    html = `<div class="alert alert-warning text-center mb-0"><i class="bi bi-exclamation-triangle-fill"></i> ${data.error}</div>`;
                } else if (data.length > 0) {
                    html += `<div class="alert alert-success text-center mb-3"><i class="bi bi-check-circle-fill"></i> 找到 ${data.length} 个相关文件：</div>`;
                    html += '<ul class="list-group">';
                    data.forEach(f => {
                        html += `<li class="list-group-item"><i class="bi bi-file-earmark-zip"></i> ${f}</li>`;
                    });
                    html += '</ul>';
                } else {
                    html = `<div class="alert alert-danger text-center mb-0"><i class="bi bi-x-circle-fill"></i> 未找到相关文件</div>`;
                }
                document.getElementById('modalBody').innerHTML = html;
                var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            });
    });
    </script>
</body>
</html>
