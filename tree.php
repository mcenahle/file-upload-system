<?php
$siteRootUrl = 'https://upload.mcenahle.org.cn/'; // 修改为你的根目录
function renderTree($dir, $relativePath = '', $isRoot = false) {
    global $siteRootUrl;
    $files = scandir($dir);
    echo '<ul class="list-unstyled ms-2">';
    foreach ($files as $value) {
        if ($value == '.' || $value == '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $value;
        $displayPath = ltrim($relativePath . '/' . $value, '/');
        if (is_dir($path)) {
            echo '<li class="folder">';
            echo '<span class="toggle-folder" style="cursor:pointer;"><i class="bi bi-folder"></i> ' . htmlspecialchars($value) . '</span>';
            echo '<div class="children" style="display:none;">';
            renderTree($path, $displayPath, false);
            echo '</div>';
            echo '</li>';
        } else if (!$isRoot) {
            // 注意添加 data-url 属性（完整URL，自动拼接）
            $fullUrl = $siteRootUrl . $displayPath;
            echo '<li class="file"><i class="bi bi-file-earmark"></i> ' . htmlspecialchars($value) .
                ' ' .
                '</li>';
        }
    }
    echo '</ul>';
}
if (isset($_GET['preview'])) {
    $file = $_GET['preview'];
    $safe_file = realpath(__DIR__ . '/' . $file);
    if (strpos($safe_file, __DIR__) !== 0 || !is_file($safe_file)) {
        http_response_code(403);
        exit("无权访问！");
    }
    $ext = strtolower(pathinfo($safe_file, PATHINFO_EXTENSION));
    $allowed = ['txt', 'html', 'css', 'js', 'php', 'md', 'log', 'json', 'xml', 'csv'];
    if (in_array($ext, $allowed)) {
        header('Content-Type: text/plain; charset=utf-8');
        echo file_get_contents($safe_file);
    } else {
        echo "暂不支持该类型文件在线预览";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <title>查看上传情况</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://zy.mcenahle.org.cn/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://zy.mcenahle.org.cn/bootstrap/css/bootstrap-icons.css" rel="stylesheet">
    <meta name="robots" content="noindex, nofollow">
    <style>
        body { background: #fafbfc; }
        .folder > .toggle-folder { color: #0d6efd; font-weight: bold; user-select: none; }
        .folder > .toggle-folder:hover { text-decoration: underline; }
        .file { color: #333; margin-left: -0.5em; }
        .folder { margin-left: -0.5em; }
        .children { margin-left: 1.8em; border-left: 1.5px solid #eee; }
        ul { margin-bottom: 0.1em; }
        .bi-folder2-open { color: #f5b041; }
        .bi-folder { color: #9bb0c1; }
        #searchInput { max-width: 380px; }
        .hide { display: none !important; }
        mark { background: #fff3cd; color: #333; }
        @font-face {
            font-family: 'JetBrainsMono-Regular';
            src: url('https://zy.mcenahle.org.cn/fonts/jetbrains-mono/webfonts/JetBrainsMono-Regular.woff2') format('woff2');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
#fileContent {
    white-space: pre-wrap;
    word-break: break-all;  /* 长单词、URL 也自动换行 */
    max-width: 100%;
    overflow-x: auto;
}

        #fileContent,
        li.folder {
    font-family: 'JetBrains Mono', 'Consolas', 'monospace';
    font-size: 1rem; /* 可选，调整字号 */
}
    </style>
</head>

<body>
    <div class="container my-4">
        <h2 class="mb-4">查看上传情况</h2>
        <p><a href="/">返回主页</a></p>
        <div class="input-group mb-3">
            <span class="input-group-text">全局搜索</span>
            <input type="text" id="searchInput" class="form-control" placeholder="输入文件夹/文件名（支持多层）">
        </div>
        <div id="tree">
            <?php renderTree(__DIR__, '', true); ?>
        </div>
    </div>

    <!-- 预览模态框 -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">文件内容预览</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                </div>
                <div class="modal-body">
                    <pre id="fileContent" style="white-space: pre-wrap;">加载中...</pre>
                </div>
                <div class="modal-footer">
                    <a href="#" target="_blank" id="gotoFileBtn" class="btn btn-primary" style="display:none;">跳转到该网页 <i class="bi bi-box-arrow-up-right"></i></a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // 文件夹展开/收起
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('#tree .toggle-folder').forEach(function (span) {
                span.addEventListener('click', function () {
                    const children = this.parentNode.querySelector('.children');
                    if (!children) return;
                    const visible = children.style.display !== 'none';
                    children.style.display = visible ? 'none' : 'block';
                    // 切换图标
                    const icon = this.querySelector('i');
                    icon.className = visible ? 'bi bi-folder' : 'bi bi-folder2-open';
                });
            });

            // 模态框预览
            var previewModal = document.getElementById('previewModal');
            previewModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var path = button.getAttribute('data-path');
                var modalTitle = previewModal.querySelector('.modal-title');
                var fileContent = document.getElementById('fileContent');
                var gotoFileBtn = document.getElementById('gotoFileBtn');
                modalTitle.textContent = path || '文件内容预览';
                fileContent.textContent = '加载中...';

                if (path && path.match(/\.(html?|HTML?)$/)) {
                    gotoFileBtn.style.display = '';
                    gotoFileBtn.href = path;
                } else {
                    gotoFileBtn.style.display = 'none';
                    gotoFileBtn.href = '#';
                }
                fetch('?preview=' + encodeURIComponent(path))
                    .then(r => r.text())
                    .then(txt => {
                        fileContent.textContent = txt;
                    });
            });

            // 全局搜索：递归匹配+展示
            document.getElementById('searchInput').addEventListener('input', function () {
                const keyword = this.value.trim().toLowerCase();
                // 遍历所有文件/文件夹，递归显示/隐藏
                function filterTree(ul) {
                    let found = false;
                    ul.querySelectorAll(':scope > li').forEach(function (li) {
                        let text = li.textContent.toLowerCase();
                        let match = !keyword || text.indexOf(keyword) !== -1;
                        let subFound = false;
                        // 有 children 则递归
                        let childrenDiv = li.querySelector(':scope > .children');
                        if (childrenDiv) {
                            subFound = filterTree(childrenDiv.querySelector('ul'));
                            // 若子孙命中，自动展开
                            if (subFound) {
                                childrenDiv.style.display = 'block';
                                let icon = li.querySelector('.toggle-folder i');
                                if (icon) icon.className = 'bi bi-folder2-open';
                            } else {
                                childrenDiv.style.display = 'none';
                                let icon = li.querySelector('.toggle-folder i');
                                if (icon) icon.className = 'bi bi-folder';
                            }
                        }
                        // 显示：本节点命中或有子节点命中
                        if (match || subFound) {
                            li.classList.remove('hide');
                            found = true;
                        } else {
                            li.classList.add('hide');
                        }
                        // 高亮关键词
                        let span = li.querySelector(':scope > .toggle-folder');
                        if (span) {
                            span.innerHTML = '<i class="' + (childrenDiv && childrenDiv.style.display !== 'none' ? 'bi bi-folder2-open' : 'bi bi-folder') + '"></i> ' +
                                (keyword ? highlightText(span.textContent, keyword) : span.textContent);
                        } else if (li.classList.contains('file')) {
                            let rawText = li.childNodes[1] ? li.childNodes[1].textContent : '';
                            if (keyword) {
                                li.childNodes[1].innerHTML = highlightText(rawText, keyword);
                            } else {
                                li.childNodes[1].textContent = rawText;
                            }
                        }
                    });
                    return found;
                }
                function highlightText(text, keyword) {
                    let reg = new RegExp('(' + keyword.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                    return text.replace(reg, '<mark>$1</mark>');
                }
                filterTree(document.querySelector('#tree > ul'));
            });
        });

        // 复制路径功能
    document.body.addEventListener('click', function (e) {
        if (e.target.closest('.copy-url-btn')) {
            let btn = e.target.closest('.copy-url-btn');
            let url = btn.getAttribute('data-url');
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(function () {
                    // 弹窗或视觉提示，可替换为更优雅的 toast
                    btn.innerHTML = '<i class="bi bi-clipboard-check"></i> 已复制！';
                    setTimeout(function () {
                        btn.innerHTML = '<i class="bi bi-clipboard"></i> 复制路径';
                    }, 1500);
                });
            } else {
                // 老浏览器降级
                let textarea = document.createElement('textarea');
                textarea.value = url;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                btn.innerHTML = '<i class="bi bi-clipboard-check"></i> 已复制！';
                setTimeout(function () {
                    btn.innerHTML = '<i class="bi bi-clipboard"></i> 复制路径';
                }, 1500);
            }
        }
    });
    </script>
</body>
</html>
