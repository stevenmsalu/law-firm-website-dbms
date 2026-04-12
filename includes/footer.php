<?php
if (!isset($assetPathPrefix)) {
    $assetPathPrefix = '../';
}

$jsFile = dirname(__DIR__) . '/assets/js/script.js';
$jsVersion = file_exists($jsFile) ? (string) filemtime($jsFile) : '1';

if (empty($skipFooterBody)) {
    require __DIR__ . '/footer_body.php';
}
?>
<script src="<?php echo htmlspecialchars($assetPathPrefix, ENT_QUOTES, 'UTF-8'); ?>assets/js/script.js?v=<?php echo htmlspecialchars($jsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
