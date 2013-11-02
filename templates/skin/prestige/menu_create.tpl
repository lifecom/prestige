{if $sEvent!='edit'}
<div class="menu-creat">

<script type="text/javascript">
	jQuery(function($){
		var trigger = $('#dropdown-create-trigger');
		var menu 	= $('#dropdown-create-menu');
		var pos 	= trigger.position();
	
	
		// Dropdown
		menu.css({ 'left': pos.left - 5 });
	
		trigger.click(function(){
			menu.slideToggle(); 
			return false;
		});
		
		
		// Hide menu
		$(document).click(function(){
			menu.slideUp();
		});
	
		$('body').on("click", "#dropdown-create-trigger, #dropdown-create-menu", function(e) {
			e.stopPropagation();
		});
	});
</script>


<div class="dropdown-create">
	{strip}
		<h2 class="page-header">{$aLang.block_create} <a href="#" class="dropdown-create-trigger" id="dropdown-create-trigger">
			{if $sMenuItemSelect=='topic'}
				{$aLang.topic_menu_add}
			{elseif $sMenuItemSelect=='blog'}
				{$aLang.blog_menu_create}
			{else}
				{hook run='menu_create_item_select' sMenuItemSelect=$sMenuItemSelect}
			{/if}
		</a></h2>
	{/strip}
	
	<ul class="dropdown-menu" id="dropdown-create-menu" style="display: none">
		<li {if $sMenuItemSelect=='topic'}class="active"{/if}><a href="{router page='topic'}add/">{$aLang.topic_menu_add}</a></li>
		<li {if $sMenuItemSelect=='blog'}class="active"{/if}><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
		{hook run='menu_create_item' sMenuItemSelect=$sMenuItemSelect}
	</ul>
</div>

{hook run='menu_create' sMenuItemSelect=$sMenuItemSelect sMenuSubItemSelect=$sMenuSubItemSelect}

</div>
{/if}