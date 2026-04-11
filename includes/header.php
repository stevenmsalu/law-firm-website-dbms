<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Lex & Partners Law Firm';
}
if (!isset($activePage)) {
    $activePage = '';
}
if (!isset($htmlClass)) {
    $htmlClass = '';
}
if (!isset($assetPathPrefix)) {
    $assetPathPrefix = '../';
}
if (!isset($publicPathPrefix)) {
    $publicPathPrefix = '';
}
if (!isset($authPathPrefix)) {
    $authPathPrefix = '../auth/';
}

$cssFile = dirname(__DIR__) . '/assets/css/style.css';
$cssVersion = file_exists($cssFile) ? (string) filemtime($cssFile) : '1';
?>
<!DOCTYPE html>
<html lang="en"<?php echo $htmlClass !== '' ? ' class="' . htmlspecialchars($htmlClass, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetPathPrefix, ENT_QUOTES, 'UTF-8'); ?>assets/css/style.css?v=<?php echo htmlspecialchars($cssVersion, ENT_QUOTES, 'UTF-8'); ?>" />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-inner">
        <a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>index.php" class="logo">
          <span class="logo-mark">L&amp;P</span>
          <span class="logo-text">
            Lex &amp; Partners
            <span class="logo-subtitle">Attorneys at Law</span>
          </span>
        </a>

        <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false">
          <span class="nav-toggle-line"></span>
          <span class="nav-toggle-line"></span>
          <span class="nav-toggle-line"></span>
        </button>

        <nav class="site-nav" aria-label="Main navigation">
          <ul>
            <li><a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>index.php" class="<?php echo $activePage === 'home' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>about.php" class="<?php echo $activePage === 'about' ? 'active' : ''; ?>">About</a></li>
            <li><a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>services.php" class="<?php echo $activePage === 'services' ? 'active' : ''; ?>">Services</a></li>
            <li><a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>lawyers.php" class="<?php echo $activePage === 'lawyers' ? 'active' : ''; ?>">Lawyers</a></li>
            <li><a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>contact.php" class="<?php echo $activePage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
            <li><a href="<?php echo htmlspecialchars($authPathPrefix, ENT_QUOTES, 'UTF-8'); ?>login.php" class="btn btn-small">Members Only</a></li>
          </ul>
        </nav>
      </div>
    </header>

