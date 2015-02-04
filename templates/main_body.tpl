{include file="header.tpl" title=$header->get_title() description=$header->get_description() keywords=$header->get_keywords()}
<div class="container">
	<table class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th></th>
			<th>发布时间</th>
			<th>分类</th>
			<th>名称</th>
			<th>文件大小</th>
			<th>种子</th>
			<th>下载</th>
			<th>发布者</th>
		</tr>
		</thead>
		<tbody>
		{section name=share_info loop=$anime_list}
		<tr>
			<td><a href="{$anime_list[share_info]->getShareInfo()->get_magnet_link()}"></a></td>
			<td>{$anime_list[share_info]->getShareInfo()->get_add_time()->get_man_time()}</td>
			<td>{$anime_list[share_info]->getCategoryInfo()->get_category_name()->get_html_escape_text()}</td>
			<td>{$anime_list[share_info]->getShareInfo()->get_share_name()->get_html_escape_text()}</td>
			<td>{$anime_list[share_info]->getShareInfo()->get_file_size()}</td>
			<td>{$anime_list[share_info]->getDownloadInfo()->get_seeder_number()}</td>
			<td>{$anime_list[share_info]->getDownloadInfo()->get_leechers_number()}</td>
			<td><a href="/uploader-{$anime_list[share_info]->getUserInfo()->getUserId()}-1-{$anime_list[share_info]->getUserInfo()->getUserName()->get_url_encode_text()}.html" target="_blank">{$anime_list[share_info]->getUserInfo()->getUserName()->get_html_escape_text()}</a></td>
		</tr>
		{/section}
		</tbody>
	</table>
</div>
{include file='footer.tpl'}