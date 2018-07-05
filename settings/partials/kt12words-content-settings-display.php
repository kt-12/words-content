<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://kt12.in
 * @since      1.0.0
 *
 * @package    Words_Content
 * @subpackage Words_Content/settings/partials
 */

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h1><?php esc_html_e( "Word's Content Plugin Settings", 'kt12words-content' ); ?></h1>
	<hr/>
	<div class="row">
		<div class="col-md-6">
			<form method="post" action="options.php">
				<?php
				// add_settings_section callback is displayed here. For every new section we need to call settings_fields.
				settings_fields( 'regex-word-highlight-settings' );
				// all the add_settings_field callbacks is displayed here.
				do_settings_sections( 'kt12words-content-options' );
				// Add the submit button to serialize the options.
				submit_button();
				?>
			</form>
		</div>
		<div class="col-md-5">
			<!-- codepen -->
			<div class="container">
				<div class="row justify-content-center">
					<div class="col col-md-12">
						<div class="rotate-container">
							<div class="card card-front text-center pulse">
								<div class="card-header">
									<p class="card-title" style="line-height: normal;">Hello! I'm Karthik Thayyil</p>
								</div>
								<div class="card-background"></div>
								<div class="card-block"><?php echo get_avatar( 'me@kt12.in', 78 ); ?>
									<p><strong><a href="https://profiles.wordpress.org/thekt12" target="_blank">WordPress Core Contributor</a> v<a href="https://wordpress.org/news/2017/11/tipton/" target="_blank">4.9</a><br/><a href="https://www.linkedin.com/in/karthik-thayyil-55210621/#ember5199" target="_blank">WordPress Developer and Full-Time Freelancer</a></strong></p>
									<hr>
									<small><strong>3+ years exp</strong> | <strong>50+ WordPress Projects</strong> | <strong>20+ unique clients</strong></small>
									<hr>
									<p><span style="color: black; font-weight: 500;">Are you looking for an experienced WordPress Developer?</span> I have an offer for you.</p>
									<p>Get your work done to your satisfaction and success at <del style="color:red">$25 USD/hour</del> <strong style="  color: #5c24af; font-size: 18px;">$15 USD/hour</strong> for the first 100 hours of our collaboration<small><strong> (valid till May 2018)</strong></small>.</p>
									<p> Drop a mail at <strong><a href="mailto:me@kt12.in" target="_blank">me@kt12.in</a></strong> with your requirement, or just wave a Hello!</p>
									<p><small><strong>To know more about me flip this card</strong></small></p>
									<button class="btn btn-outline-primary btn-rotate">Click Here to Flip ⥂</button>
								</div>
							</div>
							<div class="card card-back text-center pulse">
								<div class="card-header">
									<h3>About Me</h3>
								</div>
								<div class="card-block">
									<p style="margin-top: 10px">I started my career with a WordPress Services and Products Company</strong><small><a href="https://www.linkedin.com/in/karthik-thayyil-55210621/#ember5199"> [LinkedIn profile to know more]</a></small> and worked there for three and half years. Within this short period, I was able to deliver more than a 50+ project and few WordPress products to client/company satisfaction. Providing quality secure solutions within clients budget is what I have inherited from the company.</p>
									<p>Currently, I have left the company and got into full-time freelancing, so I can make time for my researches and studies.</p>
									<h4> WordPress solutions that I can provide:</h4>
									<ul class="exp">
										<li> WooCommerce and WooCommerce ad-ons solutions</li>
										<li> Event Espresso 3/4, WPLMS and other major Plugins and Themes Solution</li>
										<li> Customization of any plugin or theme (only via ad-on plugin or child theme creation).</li>
										<li> Custom Solutions&mdash; Plugins and Themes</li>
										<li> Solution and strategy to speed up the sites rendering and also to keep it secure.</li>
										<li> REST and Mobile App Solution for the Website using WordPress and Vue.js</li>
										<li> I also do other frameworks like Laravel etc and even Vue.js, Node.js (though not limited to this)</li>
										<li> I also do IoT&mdash; Nodemcu, Arduino and even Rasberry Pi</li>
									</ul>
									<h4>If not professionally, we can still be in touch. I am open to any suggestion and advice.</h4>
									<h3>Connect:</h3>
									<ul class="social-links list-unstyled d-flex justify-content-center">
										<li><a href="https://www.facebook.com/kikk.scouter" title="Facebook Profile"  target="_blank"><i class="fa fa-facebook"></i></a></li>
										<li><a href="https://twitter.com/karthvks" title="Twitter Profile"  target="_blank"><i class="fa fa-twitter"></i></a></li>
										<li><a href="https://www.linkedin.com/in/karthik-thayyil-55210621/" title="LinkedIn Profile"  target="_blank"><i class="fa fa-linkedin"></i></a></li>
										<li><a href="mailto:me@kt12.in" title="Mail to me@kt12.in"  target="_blank"><i class="fa fa-envelope"></i></a></li>
									</ul>
									<ul class="social-links list-unstyled d-flex justify-content-center">
										<li><a href="https://profiles.wordpress.org/thekt12" title="WordPress.org Profile"  target="_blank"><i class="fa fa-wordpress"></i></a></li>
										<li><a href="https://kt12.in" title="My Website" target="_blank"><i class="fa fa-globe"></i></a></li>
										<li><a href="https://github.com/kt-12" title="GitHub Profile" target="_blank"><i class="fa fa-github"></i></a></li>
									</ul>

									<button class="btn btn-outline-primary btn-rotate">   <i class="fa fa-long-arrow-left"> </i>&nbsp; Flip Back </button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- codepen -->
		</div>
	</div>
</div>
