<div style="max-width: 600px; margin: auto" class="page-content pb-0">

   <div data-card-height="cover" class="card">
      <div class="card-top notch-clear">
         <div class="d-flex">
            <a href="login" class="me-auto icon icon-m"><i class="font-14 fa fa-arrow-left color-theme"></i></a>
            <!-- <a href="#" data-toggle-theme class="show-on-theme-light ms-auto icon icon-m"><i class="font-12 fa fa-moon color-theme"></i></a>
            <a href="#" data-toggle-theme class="show-on-theme-dark ms-auto icon icon-m"><i class="font-12 fa fa-lightbulb color-yellow-dark"></i></a> -->
         </div>
      </div>
      <div class="card-center">
         <div class="ps-5 pe-5">
            <!-- <div style="padding-bottom: 20px" class="text-center">
               <img class="text-center" src="images/ggcloud.png" alt="">
            </div> -->
            <h1 class="text-center font-200 font-40 mb-4">Sign Up</h1>
            <!-- <p class="text-center font-12">Register new member</p> -->

            <form action="<?= base_url('register/aksi_register'); ?>" method="POST">

               <div class="input-style no-borders has-icon">
                  <i class="fa fa-user"></i>
                  <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
               </div>
               <!-- <small>* Hanya berupa karakter, nomor dan garis bawah ('_')</small> -->
               <?php if (null !== $this->session->flashdata('email')) {
                  $email = $this->session->flashdata('email');
               } else {
                  $email = '';
               } ?>
               <div class="input-style no-borders has-icon mt-2">
                  <i class="fa fa-at"></i>
                  <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= $email ?>" required>
               </div>
               <div class="input-style no-borders has-icon mt-2">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control" placeholder="Choose a Password" name="password1" minlength="8" required>
               </div>
               <div class="input-style no-borders has-icon mt-2">
                  <i class="fa fa-lock"></i>
                  <input type="password" class="form-control" placeholder="Confirm your Password" name="password2" minlength="8" required>
               </div>

               <div style="margin: auto; width: 60%">
                  <div class="g-recaptcha mb-2" data-sitekey="6Lf72FUpAAAAAB15KrmicPBHlE7AtktemGLWzyyq"></div>
               </div>

               <div class="form-check icon-check mb-2 mt-4">
                  <input class="form-check-input" type="checkbox" value="" id="check4" required>
                  <label class="form-check-label" for="check4">
                     <small>Dengan ini saya menyetujui <a href="" data-menu="modal-tos">T.O.S</a> yang berlaku selama menggunakan layanan</small>
                  </label>
                  <i class="icon-check-1 far fa-circle color-gray-dark font-16"></i>
                  <i class="icon-check-2 far fa-check-circle font-16 color-highlight-purple"></i>
               </div>

               <button style="width: 100%" type="submit" name="signup" class="btn btn-full btn-m rounded-sm text-uppercase font-200 btn-grad">Create Account</button>

               <div class="text-center mb-5 mt-5">
                  <a href="login" class="font-12">Already Registered? Sign in Here</a>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- End of Page Content-->

<div id="modal-tos" class="menu menu-box-modal rounded-m" style="max-width: 600px; width: 100%">
   <div class="menu-title">
      <h1>
         TERMS OF SERVICE <button class="close-menu float-right me-4 opacity-50"><i class="fa fa-times"></i></button>
      </h1>
   </div>

   <div class="content mt-2 ms-4 me-4 pb-4">
      <div style="overflow: scroll; height: 80vh">
         <h2><strong>Service Rules</strong></h2>
         <p>Demi menjaga kenyamanan dan kemananan layanan, kami melarang tindakan-tindakan berikut, antara lain :</p>
         <ul>
            <li>Hacking</li>
            <li>Mining</li>
            <li>Rooting</li>
            <li>Installing Illegal Software</li>
            <li>Merusak sistem perangkat dengan sengaja</li>
            <li>Judi Online dan tindakan illegal yang melanggar hukum lainnya</li>
         </ul>

         <p>Segala tindakan pelanggaran aturan akan dikenakan banned tanpa pemberitahuan sebelumnya.</p>

         <h2><strong>Terms and Conditions</strong></h2>

         <p>These terms and conditions outline the rules and regulations for the use of GGCLOUD | Android Cloud Emulator's Website, located at ggcloud.id.</p>

         <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use GGCLOUD | Android Cloud Emulator if you do not agree to take all of the terms and conditions stated on this page.</p>

         <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "Your" refers to you, the person log on this website and compliant to the Company’s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same. Our Terms and Conditions were created with the help of the <a href="https://www.privacypolicyonline.com/terms-conditions-generator/">Terms & Conditions Generator</a>.</p>

         <h3><strong>Cookies</strong></h3>

         <p>We employ the use of cookies. By accessing GGCLOUD | Android Cloud Emulator, you agreed to use cookies in agreement with the GGCLOUD | Android Cloud Emulator's Privacy Policy.</p>

         <p>Most interactive websites use cookies to let us retrieve the user's details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>

         <h3><strong>License</strong></h3>

         <p>Unless otherwise stated, GGCLOUD | Android Cloud Emulator and/or its licensors own the intellectual property rights for all material on GGCLOUD | Android Cloud Emulator. All intellectual property rights are reserved. You may access this from GGCLOUD | Android Cloud Emulator for your own personal use subjected to restrictions set in these terms and conditions.</p>

         <p>You must not:</p>
         <ul>
            <li>Republish material from GGCLOUD | Android Cloud Emulator</li>
            <li>Sell, rent or sub-license material from GGCLOUD | Android Cloud Emulator</li>
            <li>Reproduce, duplicate or copy material from GGCLOUD | Android Cloud Emulator</li>
            <li>Redistribute content from GGCLOUD | Android Cloud Emulator</li>
         </ul>

         <p>This Agreement shall begin on the date hereof.</p>

         <p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. GGCLOUD | Android Cloud Emulator does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of GGCLOUD | Android Cloud Emulator,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, GGCLOUD | Android Cloud Emulator shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p>

         <p>GGCLOUD | Android Cloud Emulator reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</p>

         <p>You warrant and represent that:</p>

         <ul>
            <li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li>
            <li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li>
            <li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li>
            <li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li>
         </ul>

         <p>You hereby grant GGCLOUD | Android Cloud Emulator a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</p>

         <h3><strong>Hyperlinking to our Content</strong></h3>

         <p>The following organizations may link to our Website without prior written approval:</p>

         <ul>
            <li>Government agencies;</li>
            <li>Search engines;</li>
            <li>News organizations;</li>
            <li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li>
            <li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</li>
         </ul>

         <p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party's site.</p>

         <p>We may consider and approve other link requests from the following types of organizations:</p>

         <ul>
            <li>commonly-known consumer and/or business information sources;</li>
            <li>dot.com community sites;</li>
            <li>associations or other groups representing charities;</li>
            <li>online directory distributors;</li>
            <li>internet portals;</li>
            <li>accounting, law and consulting firms; and</li>
            <li>educational institutions and trade associations.</li>
         </ul>

         <p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of GGCLOUD | Android Cloud Emulator; and (d) the link is in the context of general resource information.</p>

         <p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party's site.</p>

         <p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to GGCLOUD | Android Cloud Emulator. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p>

         <p>Approved organizations may hyperlink to our Website as follows:</p>

         <ul>
            <li>By use of our corporate name; or</li>
            <li>By use of the uniform resource locator being linked to; or</li>
            <li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party's site.</li>
         </ul>

         <p>No use of GGCLOUD | Android Cloud Emulator's logo or other artwork will be allowed for linking absent a trademark license agreement.</p>

         <h3><strong>iFrames</strong></h3>

         <p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>

         <h3><strong>Content Liability</strong></h3>

         <p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>

         <h3><strong>Reservation of Rights</strong></h3>

         <p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it's linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>

         <h3><strong>Removal of links from our website</strong></h3>

         <p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>

         <p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>

         <h3><strong>Disclaimer</strong></h3>

         <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>

         <ul>
            <li>limit or exclude our or your liability for death or personal injury;</li>
            <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li>
            <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li>
            <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li>
         </ul>

         <p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>

         <p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>
      </div>
   </div>
</div>