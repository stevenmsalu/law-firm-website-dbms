<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Zimba & Partners';
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

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en"<?php echo $htmlClass !== '' ? ' class="' . htmlspecialchars($htmlClass, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($assetPathPrefix, ENT_QUOTES, 'UTF-8'); ?>assets/img/favicon/favicon.png" />
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetPathPrefix, ENT_QUOTES, 'UTF-8'); ?>assets/css/style.css?v=<?php echo htmlspecialchars($cssVersion, ENT_QUOTES, 'UTF-8'); ?>" />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-inner">
        <a href="<?php echo htmlspecialchars($publicPathPrefix, ENT_QUOTES, 'UTF-8'); ?>index.php" class="logo">
          <span class="logo-mark">Z&amp;P</span>
          <span class="logo-text">
            Zimba &amp; Partners
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
            <?php if (!empty($_SESSION['user_id']) && !empty($_SESSION['name'])): ?>
              <!-- User is logged in - show user menu -->
              <li class="user-menu">
                <div class="user-dropdown">
                  <button class="btn btn-small user-menu-btn" aria-expanded="false">
                    <?php 
                    $userName = $_SESSION['name'];
                    $firstName = explode(' ', $userName)[0];
                    echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); 
                    ?>
                    <span class="dropdown-arrow">▼</span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a href="<?php 
                      // Determine dashboard link based on role
                      $dashboardLink = '';
                      if ($_SESSION['role'] === 'admin') {
                        $dashboardLink = $assetPathPrefix . 'dashboard/admin/index.php';
                      } elseif ($_SESSION['role'] === 'lawyer') {
                        $dashboardLink = $assetPathPrefix . 'dashboard/lawyer.php';
                      } elseif ($_SESSION['role'] === 'client') {
                        $dashboardLink = $assetPathPrefix . 'dashboard/client.php';
                      }
                      echo htmlspecialchars($dashboardLink, ENT_QUOTES, 'UTF-8');
                    ?>">Dashboard</a></li>
                    <li><a href="<?php echo htmlspecialchars($authPathPrefix, ENT_QUOTES, 'UTF-8'); ?>logout.php">Logout</a></li>
                  </ul>
                </div>
              </li>
            <?php else: ?>
              <!-- User is not logged in - show login button -->
              <li>
                <a href="<?php echo htmlspecialchars($authPathPrefix, ENT_QUOTES, 'UTF-8'); ?>login.php" 
                  class="btn btn-small members-link">
                  Members Only
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>