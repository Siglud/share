{include file="header.tpl" title=$header->get_title() description=$header->get_description() keywords=$header->get_keywords()}
<div class="body"><div class="inner">
	<div class="bangumiList">
		<div class="hd">
			<h2>番组列表</h2>
			<a href="#" class="toggle">展开</a>
		</div>
		<div class="bd">
			<div class="list">
				<strong class="label">星期一</strong>
				<a href="">海贼王</a>
				<a href="">刀剑神域</a>
				<a href="">日常系的异能战斗</a>
				<a href="">魔神之骨</a>
				<a href="">TRINITY SEVEN 七人的魔法使</a>
				<a href="">高达创战者TRY</a>
				<a href="">Hi☆sCoool!世嘉少女</a>
			</div>
			<div class="list today">
				<strong class="label">星期一</strong>
				<a href="">海贼王</a>
				<a href="">刀剑神域</a>
				<a href="">日常系的异能战斗</a>
				<a href="">魔神之骨</a>
				<a href="">TRINITY SEVEN 七人的魔法使</a>
			</div>
			<div class="list">
				<strong class="label">星期一</strong>
				<a href="">海贼王</a>
				<a href="">刀剑神域</a>
				<a href="">日常系的异能战斗</a>
				<a href="">魔神之骨</a>
				<a href="">TRINITY SEVEN 七人的魔法使</a>
				<a href="">高达创战者TRY</a>
				<a href="">Hi☆sCoool!世嘉少女</a>
				<a href="">Tales of Zestiria~导师的黎明~（完）</a>
			</div>
		</div>
	</div>
	<div class="latest">
		<div class="hd">
			<h2>最新种子</h2>
			<p class="quickLink">
				<a href="#cate1">动画</a>
				<a href="#cate2">漫画</a>
				<a href="">动漫合集</a>
				<a href="">音乐</a>
				<a href="">日剧</a>
				<a href="">RAW</a>
				<a href="">其他</a>
			</p>
		</div>
		<div id="cate1" class="section">
			<h3>动画<a href="">[查看全部]</a></h3>
			<div class="torrentList">
				{section name=share_info loop=$anime_list}
				<div class="item">
					<div class="title">
						<a href="{$anime_list[share_info]->getShareInfo()->get_detail_link()}"><em class="top">{if $anime_list[share_info]->getShareInfo()->get_is_top() == true}[置顶]{/if}</em><span class="group">{$anime_list[share_info]->getShareInfo()->get_share_name()->get_html_escape_text()}</a>
					</div>
					<div class="data">
						<span class="seedingNum">{$anime_list[share_info]->getDownloadInfo()->get_seeder_number()}</span>
						<span class="downloadingNum">{$anime_list[share_info]->getDownloadInfo()->get_leechers_number()}</span>
						<span class="doneNum">1</span>
					</div>
					<div class="meta">
						<span class="time">{$anime_list[share_info]->getShareInfo()->get_add_time()->get_man_time()}</span> by <a href="" class="userName">{$anime_list[share_info]->getUserInfo()->getUserName()->get_orig_text()}</a>
						<span class="fileSize">{$anime_list[share_info]->getShareInfo()->get_file_size()}</span>
						<!-- <a href="" class="cate">动画</a> -->
					</div>
					<div class="opt">
						<a href="{$anime_list[share_info]->getShareInfo()->get_magnet_link()}">磁力链下载</a>
					</div>
				</div>
				{/section}
			</div>
		</div>
		<div id="cate2" class="section">
			<h3>漫画<a href="">[查看全部]</a></h3>
			<div class="torrentList">
				<div class="item">
					<div class="title">
						<a href=""><em class="top">[置顶]</em><span class="group">[萌物百科字幕组]</span>【萌物百科字幕社】★[四月是你的谎言_Shigatsu wa Kimi no Uso][14]</a>
					</div>
					<div class="data">
						<span class="seedingNum">150</span>
						<span class="downloadingNum">130</span>
						<span class="doneNum">1</span>
					</div>
					<div class="meta">
						<span class="time">00-00-00 00:00</span> by <a href="" class="userName">基友一生一起走</a>
						<span class="fileSize">10.50MB</span>
						<!-- <a href="" class="cate">动画</a> -->
					</div>
					<div class="opt">
						<a href="">磁力链下载</a>
					</div>
				</div>
				<div class="item">
					<div class="title">
						<a href="">[Leopard-Raws]飆速宅男GRANDE ROAD第二期(飙速宅男第二季) Yowamushi Pedal - Grande Road - 15 RAW (TX 1280x720 x264 AAC).mp4</a>
					</div>
					<div class="data">
						<span class="seedingNum">20</span>
						<span class="downloadingNum">1000</span>
						<span class="doneNum">12345</span>
					</div>
					<div class="meta">
						<span class="time">00-00-00 00:00</span> by <a href="" class="userName">基友一生一起走</a>
						<span class="fileSize">10.50MB</span>
						<!-- <a href="" class="cate">动画</a> -->
					</div>
					<div class="opt">
						<a href="">磁力链下载</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div></div>
{include file='footer.tpl'}