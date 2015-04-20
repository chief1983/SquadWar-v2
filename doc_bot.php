<?php /*
   Copyright (C) Volition, Inc. 2005.  All rights reserved.

   All source code herein is the property of Volition, Inc. You may not sell 
   or otherwise commercially exploit the source or things you created based on the 
   source.
*/
if(!defined('BODYONLY')):
// start doc bot ?>
				
			</td></tr></table>

			<?php if(isset($main_news)): ?>
				<br />
				<?php if(count($main_news)): ?>
					<?php foreach($main_news as $news_item): ?>

						<a name="news<?php echo $news_item->get_newsID(); ?>"></a>
						<table width="95%" cellpadding="0" cellspacing="0" border="0" class="center">
							<tr>
								<td colspan="2" valign="top" bgcolor="#172A1B">
									<div class="newstitle"><?php echo date('n.j.y g:i A', strtotime($news_item->get_date_posted('Y-m-d H:i:s'))); ?> - <?php echo $news_item->get_title(); ?><br /></div>							
								</td>
							</tr>
							<tr>
								<td width="17" valign="top">&nbsp;</td>
								<td valign="top">
								<br />
									<div class="newsitem"><?php echo $news_item->get_news_item(); ?></div>
								</td>
							</tr>
						</table>
							<br />

					<?php endforeach; ?>
				<br />
				<?php else: ?>
				<div class="full_width_container center">
					<div class="copy">
						Sorry, but there is no news for FreeSpace 2 at this time.
					</div>
				</div>
				<br />
				<?php endif; ?>
			<?php endif; ?>

				</td>
			</tr>
			<tr>
				<td style="background-image:url(<?php echo RELATIVEPATH; ?>images/main/bkg_left_bot.gif);" align="left" valign="top">
					<img src="<?php echo RELATIVEPATH; ?>images/main/footer_left.gif" width="110" height="18" alt="" border="0" /><br />
				</td>
				<td style="background-image:url(<?php echo RELATIVEPATH; ?>images/main/bkg_bar_bot.gif);" valign="top">
					<img src="<?php echo RELATIVEPATH; ?>images/main/footer_right.gif" width="141" height="18" alt="" border="0" align="right" /><br clear="all" />
				</td>
			</tr>
			</table>

<?php endif;
echo implode("\n", util::get_footer())."\n"; ?>
</body>
</html>

<?php // end doc bot ?>
