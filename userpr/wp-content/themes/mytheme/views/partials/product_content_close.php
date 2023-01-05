
</div>
</div>
</div>
<div class="clearfix"> </div>
</div>

<div class="recommendation-section">
<div class="card">
	<div class="card-header">
		<h4 class="mb-0">
			<i class="fa fa-plus"></i>
			<button id="recommended-items-button" class="btn btn-block collapsed showing-list" data-toggle="collapse" data-target="#recommended-items" aria-expanded="true" aria-controls="collapseOne">
				این محصول را دوست نداشتید؟ محصولات پیشنهادی را هم ببینید
			</button>

		</h4>
	</div>
	<div class="collapse show" aria-labelledby="headingOne" data-parent="#recommendation-section">
		<div class="card-body collapse col-md-12" id="recommended-items">

			<?php
			global $this_product;
			$args1 = array(
				'post_type' => 'product',
				'orderby'   => 'rand',
				'posts_per_page' => 2,
				'post__not_in' => [$this_product]
			);

			$the_query1 = new WP_Query( $args1 );

			if ( $the_query1->have_posts() ) {
				while ( $the_query1->have_posts() ) {
					$the_query1->the_post();
					?>
					<div class="product-tab1 <?php echo 'prod3'; ?> product-info-list col-md-6" id="recommended-product-<?= get_the_ID(); ?>" data-product-id="<?= get_the_ID() ?>">
						<div class="col-md-4 product-tab1-grid">
							<div class="grid-arr">
								<div class="grid-arrival">
									<figure id="product-<?= get_the_ID(); ?>-list-img">
										<a href="<?= get_the_permalink() ?>" class="new-gri" data-toggle="modal"
										   data-target="#myModal1">
											<?php
											foreach ( product_product::thumbnails( get_the_ID() ) as $thumbnail ) {
												?>
												<div class="grid-img">
													<img src="<?= $thumbnail ?>" class="img-responsive" alt="">
												</div>
												<?php
											}
											?>
										</a>
									</figure>
								</div>
							</div>
							<div id="id-product-list-rate_<?= get_the_ID(); ?>" class="grid-arr">
								<div class="user-rate-container">
									<h5>امتیازات کاربران دیجی کالا</h5>
									<?php
									$important_attrs = product_attributes::get_important_attr_val( get_the_ID() );
									foreach ( $important_attrs as $important_attr ) {
										?>
										<div class="progress">
											<div class="progress-bar progress-bar-info"
											     style="width: <?= $important_attr['value'] ?>%;">
												<p class="prog-text text-shadow"><?php echo $important_attr['name'] . ': ' . $important_attr['main_value']; ?></p>
											</div>
										</div>
										<?php
									}
									?>

								</div>
							</div>
						</div>
						<div class="col-md-8 product-tab1-grid1 simpleCart_shelfItem">
							<div id="id-product-list-info_<?= get_the_ID(); ?>" class="women">
								<h6><a href="<?= get_the_permalink() ?>"><?= get_the_title() ?></a></h6>
								<p><?= substr( get_the_excerpt(), 0, 1200 ) ?> ...</p>
								<?php
								$price = product_product::price( get_the_ID() );
								?>
								<p>
									<del><?php if ( $price['org_price'] > $price['discount_price'] ) {
											echo $price['discount_price'];
										} ?></del>
									<em class="item_price"><?= $price['org_price'] ?> تومان</em></p>
								<button data-productlink="<?= get_permalink(); ?>" class="my-cart-b item_add show-recommendation-item">مشاهده این محصول</button>
							</div>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
			}
	?>

		</div>
	</div>
</div>
</div>


</div>
</div>
</div>
</div>

<script>
	$('.showing-list').click(function () {
		var iconTag=$(this).parent().find('i.fa');
		if (iconTag.hasClass('fa-plus')){
			iconTag.removeClass('fa-plus');
			iconTag.addClass('fa-minus');
		}
		else if (iconTag.hasClass('fa-minus')){
			iconTag.removeClass('fa-minus');
			iconTag.addClass('fa-plus');
		}
	});

</script>