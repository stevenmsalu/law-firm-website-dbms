<?php
$pageTitle = 'Zimba & Partners | Home';
$activePage = 'home';
$htmlClass = 'home-snap';
include '../includes/header.php';
?>

<main>
      <section class="hero" style="min-height: calc(100vh - 72px);">
        <div class="container hero-inner">
          <div class="hero-content">
            <h1>Trusted Legal Counsel for Modern Businesses &amp; Families</h1>
            <p>
              At Zimba &amp; Partners, we combine deep legal expertise with
              practical business insight to protect what matters most to you.
            </p>
            <div class="hero-actions">
              <a href="contact.php" class="btn">Book a Consultation</a>
              <a href="services.php" class="btn btn-outline">Explore Our Services</a>
            </div>
            <div class="hero-meta">
              <div>
                <span class="hero-meta-label">25+ Years</span>
                <span class="hero-meta-text">of combined experience</span>
              </div>
              <div>
                <span class="hero-meta-label">500+ Cases</span>
                <span class="hero-meta-text">successfully resolved</span>
              </div>
              <div>
                <span class="hero-meta-label">24/7</span>
                <span class="hero-meta-text">client support</span>
              </div>
            </div>
          </div>

          <div class="hero-card">
            <h2>Schedule a Free 30-Minute Call</h2>
            <p>Share your legal concern and we’ll guide your next steps.</p>
            <ul class="hero-list">
              <li>Business &amp; corporate advisory</li>
              <li>Family and estate planning</li>
              <li>Litigation &amp; dispute resolution</li>
            </ul>
            <a href="contact.php" class="btn btn-full">Get Started</a>
          </div>
        </div>
      </section>

      <section class="section" id="home-services">
        <div class="container">
          <header class="section-header">
            <h2>Core Practice Areas</h2>
            <p>Strategic legal counsel tailored to your unique needs.</p>
          </header>

          <div class="card-grid">
            <article class="card">
              <h3>Corporate &amp; Commercial Law</h3>
              <p>
                From company formation to complex transactions, we help businesses
                navigate risk, compliance, and growth.
              </p>
              <a href="services.php#corporate" class="card-link">Learn more</a>
            </article>

            <article class="card">
              <h3>Dispute Resolution &amp; Litigation</h3>
              <p>
                Skilled advocacy in negotiations, mediation, and courtroom
                proceedings to protect your interests.
              </p>
              <a href="services.php#litigation" class="card-link">Learn more</a>
            </article>

            <article class="card">
              <h3>Family &amp; Estate Planning</h3>
              <p>
                Sensitive, practical guidance for wills, trusts, divorce, and
                long-term planning.
              </p>
              <a href="services.php#family" class="card-link">Learn more</a>
            </article>
          </div>
        </div>
      </section>

      <div class="home-snap-pane home-snap-pane--last">
        <section class="section section-alt final-block">
          <div class="container two-column">
            <div>
              <header class="section-header align-left">
                <h2>Why Clients Choose Zimba &amp; Partners</h2>
              </header>
              <ul class="checklist">
                <li>Client-first, relationship-driven approach</li>
                <li>Clear, transparent communication at every stage</li>
                <li>Business-minded strategies, not just legal theory</li>
                <li>Discreet handling of sensitive matters</li>
              </ul>
            </div>
            <div class="stats-panel">
              <div>
                <span class="stat-number">98%</span>
                <span class="stat-label">Client satisfaction</span>
              </div>
              <div>
                <span class="stat-number">3</span>
                <span class="stat-label">Countries served</span>
              </div>
              <div>
                <span class="stat-number">24h</span>
                <span class="stat-label">Average response time</span>
              </div>
            </div>
          </div>
        </section>

        <?php include __DIR__ . '/../includes/footer_body.php'; ?>
      </div>
    </main>

<?php
$skipFooterBody = true;
include '../includes/footer.php';
unset($skipFooterBody);
?>






