<div class="hd">
    <h2>番组列表</h2>
    <a href="#" class="toggle">展开</a>
</div>
<div class="bd">
    {section name=bangumi_list loop=$play_date}
    <div class="list{if $play_date == today} today{/if}">
        <strong class="label">{$bangumi_info->get_play_time_word()}
        {section name=$bangumi_list[$play_date] loop=$detail}
        <a href="{$bangumi_list[$play_date][$detail]->get_title()->get_search_url()}">{$bangumi_list[$play_date][$detail]->get_title()->get_html_escape_text()}</a>
        {/section}
    </div>
    {/section}
</div>