<?php
if (!isset($assetPathPrefix)) {
    $assetPathPrefix = '../';
}

$jsFile = dirname(__DIR__) . '/assets/js/script.js';
$jsVersion = file_exists($jsFile) ? (string) filemtime($jsFile) : '1';
?>
    <footer class="site-footer">
      <div class="container footer-inner">
        <div class="footer-brand">
          <span class="logo-mark">L&amp;P</span>
          <span class="footer-text">Lex &amp; Partners Law Firm</span>
        </div>
        <div class="footer-columns">
          <div>
            <h4>Office</h4>
            <p>
              1200 Justice Avenue<br />
              Suite 400<br />
              New York, NY 10001
            </p>
          </div>
          <div>
            <h4>Contact</h4>
            <p>
              Phone: (212) 555-0199<br />
              Email: info@lexpartners.com
            </p>
          </div>
          <div>
            <h4>Hours</h4>
            <p>
              Monday-Friday: 9:00-18:00<br />
              Emergency support 24/7
            </p>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; <span id="year"></span> Lex &amp; Partners. All rights reserved.</p>
        </div>
      </div>
    </footer>

    <script src="<?php echo htmlspecialchars($assetPathPrefix, ENT_QUOTES, 'UTF-8'); ?>assets/js/script.js?v=<?php echo htmlspecialchars($jsVersion, ENT_QUOTES, 'UTF-8'); ?>"></script>
  </body>
</html>

