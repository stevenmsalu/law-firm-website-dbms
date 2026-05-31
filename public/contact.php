<?php
$pageTitle = 'Zimba & Partners Law Firm | Contact';
$activePage = 'contact';
include '../includes/header.php';
?>

<main>
      <section class="page-hero">
        <div class="container">
          <h1>Contact Us</h1>
          <p>Share your legal question and we will respond within one business day.</p>
        </div>
      </section>

      <section class="section">
        <div class="container two-column">
          <div>
            <h2>Visit or Call</h2>
            <p>
              We welcome appointments in person, by video conference, or by phone.
              Please use the contact form to schedule a consultation.
            </p>
            <p>
              <strong>Office Address</strong><br />
              22 Independence Avenue<br />
              Lusaka Central Business District<br />
              Lusaka, Zambia 10101
            </p>
            <p>
              <strong>Phone</strong>: +260 211 123456<br />
              <strong>Email</strong>: info@zimbapartners.co.zm
            </p>
          </div>

          <div>
            <h2>Send a Message</h2>
            <form id="contact-form" novalidate>
              <div class="form-group">
                <label for="name">Full Name<span class="required">*</span></label>
                <input type="text" id="name" name="name" required />
                <p class="error-message" data-for="name"></p>
              </div>

              <div class="form-group">
                <label for="email">Email<span class="required">*</span></label>
                <input type="email" id="email" name="email" required />
                <p class="error-message" data-for="email"></p>
              </div>

              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" />
                <p class="error-message" data-for="phone"></p>
              </div>

              <div class="form-group">
                <label for="subject">Subject<span class="required">*</span></label>
                <input type="text" id="subject" name="subject" required />
                <p class="error-message" data-for="subject"></p>
              </div>

              <div class="form-group">
                <label for="message">Message<span class="required">*</span></label>
                <textarea id="message" name="message" rows="5" required></textarea>
                <p class="error-message" data-for="message"></p>
              </div>

              <div class="form-group">
                <button type="submit" class="btn btn-full">Submit</button>
              </div>

              <p id="form-success" class="form-success"></p>
            </form>
          </div>
        </div>
      </section>
    </main>

<?php include '../includes/footer.php'; ?>
