<ul class="nav nav-tabs" role="tablist">
	<? 	
		foreach ($this->menu_array() as $menu_page => $menu_value)
		{ ?>
			<li class="<?=$this->is_active_page($menu_page);?>">
				<a href="admin.php?page=<?=$menu_page;?>"><?=$menu_value;?></a>
			</li>
	<?	} ?>
</ul>