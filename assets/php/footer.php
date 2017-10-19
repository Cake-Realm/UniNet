		<!--=== Footer Version 1 ===-->
		<div class="footer-v1">
			<div class="footer">
				<div class="container">
					<div class="row">
						<!-- About -->
						<div class="col-md-3 md-margin-bottom-40">
							<a href="index.html"><img id="logo-footer" class="footer-logo" src="/assets/img/logo2-default.png" alt=""></a>
							<p>About University network dolor sit amet, consectetur adipiscing elit. Maecenas eget nisl id libero tincidunt sodales.</p>
							<p>Duis eleifend fermentum ante ut aliquam. Cras mi risus, dignissim sed adipiscing ut, placerat non arcu.</p>
						</div><!--/col-md-3-->
						<!-- End About -->

						<!-- Latest -->
						<div class="col-md-6 md-margin-bottom-40">
							<div class="posts">
								<div class="headline"><h2>Latest Questions</h2></div>
								<ul class="list-unstyled latest-list">
									<?php
									foreach (get_latest_questions(3) as $question)
									{
										$cModule = get_module_by_id($question["moduleId"]);
									?>
									<li>
										<a href="/modules/<?php echo str_replace(" ", "-", strtolower($cModule["name"])); ?>/<?php echo clean_text_for_url($question["question"]);?>/q<?php echo $question["questionId"]; ?>/p1"><?php echo $question["question"]; ?></a>
										<small><?php echo time_to_ago($question["timestamp"]); ?>...</small>
									</li>
									<?php
									}
									?>
								</ul>
							</div>
						</div><!--/col-md-6-->
						<!-- End Latest -->

						<!-- Link List -->
						<div class="col-md-3 md-margin-bottom-40">
							<div class="headline"><h2>Useful Links</h2></div>
							<ul class="list-unstyled link-list">
								<li><a href="#">About us</a><i class="fa fa-angle-right"></i></li>
								<li><a href="#">Something</a><i class="fa fa-angle-right"></i></li>
								<li><a href="#">Something</a><i class="fa fa-angle-right"></i></li>
								<li><a href="#">Something</a><i class="fa fa-angle-right"></i></li>
								<li><a href="#">Something</a><i class="fa fa-angle-right"></i></li>
							</ul>
						</div><!--/col-md-3-->
						<!-- End Link List -->
					</div>
				</div>
			</div><!--/footer-->

			<div class="copyright">
				<div class="container">
					<div class="row">
						<div class="col-md-6">
							<p>
								<?php echo date("Y"); ?> &copy; All Rights Reserved.
								<a href="/policy/privacy">Privacy Policy</a> | <a href="/policy/terms">Terms of Service</a>
							</p>
						</div>

						<!-- Social Links -->
						<div class="col-md-6">
							<ul class="footer-socials list-inline">
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Facebook">
										<i class="fa fa-facebook"></i>
									</a>
								</li>
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Skype">
										<i class="fa fa-skype"></i>
									</a>
								</li>
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Google Plus">
										<i class="fa fa-google-plus"></i>
									</a>
								</li>
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Linkedin">
										<i class="fa fa-linkedin"></i>
									</a>
								</li>
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Pinterest">
										<i class="fa fa-pinterest"></i>
									</a>
								</li>
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Twitter">
										<i class="fa fa-twitter"></i>
									</a>
								</li>
								<li>
									<a href="#" class="tooltips" data-toggle="tooltip" data-placement="top" title="" data-original-title="Dribbble">
										<i class="fa fa-dribbble"></i>
									</a>
								</li>
							</ul>
						</div>
						<!-- End Social Links -->
					</div>
				</div>
			</div><!--/copyright-->
		</div>
		<!--=== End Footer Version 1 ===-->