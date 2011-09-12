<?php
include('bootstrap.php');

$document_title = 'SquadWar';

$cutoff_date = date("Y-m-d H:i:s", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . " -1 month"));
$rec = news_api::new_search_record();
$rec->set_SquadWar(1);
$rec->set_newer_than($cutoff_date);
$rec->set_sort_by('date_posted');
$rec->set_sort_dir('DESC');
$res = news_api::search($rec);
$main_news = $res->get_results();

include(BASE_PATH.'doc_top.php');

include(BASE_PATH.'menu/main.php');

include(BASE_PATH.'doc_mid.php');

// MAIN PAGE INFO
?>

					<div class="newstitle">Recent SquadWar Headlines</div>
					
					<div class="copy">
						<?php if(!empty($main_news)): ?>
						<ul>
							<?php
							$count = 0;
							foreach($main_news as $news_item):
								if(++$count > 5):
									break;
								endif; ?>
								<li><a href="index.php#news<?php echo $news_item->get_id(); ?>"><?php echo $news_item->get_title(); ?></a></li>
							<?php endforeach; ?>
						</ul>
						<?php else: ?>
						<br />
						<?php endif; ?>
					</div>
					
					<div class="copy">
						<a href="oldnews.php">SquadWar News Archive</a>
					</div>
					<br />
				
				<?php // END MAIN PAGE INFO ?>
<?php include(BASE_PATH.'doc_bot.php'); ?>
