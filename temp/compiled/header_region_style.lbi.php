<?php if (! $this->_var['is_insert']): ?>
<div class="city-choice" id="city-choice" data-ectype="dorpdown">
	<div class="dsc-choie dsc-cm" ectype="dsc-choie">
		<i class="iconfont icon-map-marker"></i>
		<span class="ui-areamini-text" data-id="1" title="<?php echo $this->_var['region_name']; ?>"><?php echo $this->_var['region_name']; ?></span>
	</div>
	<div class="dorpdown-layer" ectype="dsc-choie-content">
        <?php endif; ?>
        <?php if ($this->_var['is_insert']): ?>
        <?php if ($this->_var['pin_region_list']): ?>
		<div class="ui-areamini-content-wrap" id="ui-content-wrap">
			<div class="hot">
            	<?php $_from = $this->_var['region_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <a href="javascript:get_district_list(<?php echo $this->_var['list']['region_id']; ?>, 0);"  <?php if ($this->_var['city_top'] == $this->_var['list']['region_id']): ?>style="background-color:#eee; color:#f42424;"<?php endif; ?>><?php echo $this->_var['list']['region_name']; ?></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="search-first-letter">
				<?php $_from = $this->_var['pin_region_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('letter', 'pin');if (count($_from)):
    foreach ($_from AS $this->_var['letter'] => $this->_var['pin']):
?>
				<a href="javascript:void(0);" data-letter="<?php echo $this->_var['letter']; ?>"><?php echo $this->_var['letter']; ?></a>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="scrollBody" id="scrollBody">
				<div class="all-list" id="scrollMap">
					<ul id="ul">
						<?php $_from = $this->_var['pin_region_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('letter', 'pin_region');$this->_foreach['reg'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['reg']['total'] > 0):
    foreach ($_from AS $this->_var['letter'] => $this->_var['pin_region']):
        $this->_foreach['reg']['iteration']++;
?>
						<li data-id="<?php echo $this->_foreach['reg']['iteration']; ?>" data-name="<?php echo $this->_var['letter']; ?>">
							<em><?php echo $this->_var['letter']; ?></em>
							<div class="itme-city">
								<?php $_from = $this->_var['pin_region']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region');if (count($_from)):
    foreach ($_from AS $this->_var['region']):
?>
								<?php if ($this->_var['region']['is_has']): ?>
								<a href="javascript:get_district_list(<?php echo $this->_var['region']['region_id']; ?>, 0);" <?php if ($this->_var['city_top'] == $this->_var['region']['region_id']): ?>class="city_selected"<?php endif; ?>><?php echo $this->_var['region']['region_name']; ?></a>
								<?php else: ?>
								<a href="javascript:void(0);" class="is_district"><?php echo $this->_var['region']['region_name']; ?></a>
								<?php endif; ?>
								<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							</div>
						</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
				<div class="scrollBar" id="scrollBar">
                	<p id="city_bar"></p>
                </div>
				<input name="area_phpName" type="hidden" id="phpName" value="<?php echo $this->_var['area_phpName']; ?>">
			</div>
		</div>
        <?php endif; ?>
		<script type="text/javascript">
        $(function(){
        $("#site-nav").jScroll();
        });
        </script>
        <?php endif; ?>
        <?php if (! $this->_var['is_insert']): ?>
	</div>
</div>
<?php endif; ?>
