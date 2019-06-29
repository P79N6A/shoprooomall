<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>


<body class="iframe_body">
    <div class="warpper">
        <div class="title"><?php echo $this->_var['lang']['seo_setting']; ?> -- <?php if ($this->_var['is_index'] == "index"): ?><?php echo $this->_var['lang']['seo_index']; ?><?php elseif ($this->_var['is_group'] == "group"): ?><?php echo $this->_var['lang']['seo_group']; ?><?php elseif ($this->_var['is_brand'] == "brand"): ?><?php echo $this->_var['lang']['seo_brand']; ?><?php elseif ($this->_var['is_exchage'] == "exchage"): ?><?php echo $this->_var['lang']['seo_exchage']; ?><?php elseif ($this->_var['is_article'] == "article"): ?><?php echo $this->_var['lang']['seo_article']; ?><?php elseif ($this->_var['is_store'] == "store"): ?><?php echo $this->_var['lang']['seo_store']; ?><?php elseif ($this->_var['is_goods'] == "goods"): ?><?php echo $this->_var['lang']['seo_goods']; ?><?php elseif ($this->_var['is_goods_cat'] == "goods_cat"): ?><?php echo $this->_var['lang']['seo_goods_cat']; ?><?php endif; ?></div>
        <div class="content">
            <div class="tabs_info">
                <ul>
                    <li <?php if ($this->_var['menu_select']['current'] == 'index'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=index"><?php echo $this->_var['lang']['seo_index']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'group'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=group"><?php echo $this->_var['lang']['seo_group']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'brand'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=brand"><?php echo $this->_var['lang']['seo_brand']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'exchage'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=exchage"><?php echo $this->_var['lang']['seo_exchage']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'article'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=article"><?php echo $this->_var['lang']['seo_article']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'store'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=store"><?php echo $this->_var['lang']['seo_store']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'goods'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=goods"><?php echo $this->_var['lang']['seo_goods']; ?></a></li>
					<li <?php if ($this->_var['menu_select']['current'] == 'goods_cat'): ?>class="curr"<?php endif; ?>><a href="seo.php?act=goods_cat"><?php echo $this->_var['lang']['seo_goods_cat']; ?></a></li>
                </ul>
            </div>
            <div class="explanation" id="explanation">
                <div class="ex_tit"><i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span></div>
                <ul>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['info']['0']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['info']['1']; ?></li>
					<li><?php echo $this->_var['lang']['operation_prompt_content']['info']['2']; ?></li>
					<li><?php echo $this->_var['lang']['operation_prompt_content']['info']['3']; ?></li>
					<li><?php echo $this->_var['lang']['operation_prompt_content']['info']['4']; ?></li>
					<li><?php echo $this->_var['lang']['operation_prompt_content']['info']['5']; ?></li>
					<li><?php echo $this->_var['lang']['operation_prompt_content']['info']['6']; ?></li>
                </ul>
            </div>
            <div class="flexilist">
            	<div class="mian-info sco-mian-info">
                <?php endif; ?>
                	<?php if ($this->_var['is_index'] == "index"): ?>
                    <div class="switch_info">
                        <form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_index']; ?></h3></div>
                                <div class="step_content">
                                    <div class="item">
                                        <div class="label">title：</div>
                                        <div class="label_value"><input type="text" name="seo[index][title]" value="<?php echo $this->_var['seo']['index']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                    </div>
                                    <div class="item">
                                        <div class="label">keywords：</div>
                                        <div class="label_value"><input type="text" name="seo[index][keywords]" value="<?php echo $this->_var['seo']['index']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                    </div>
                                    <div class="item">
                                        <div class="label">description：</div>
                                        <div class="label_value"><input type="text" name="seo[index][description]" value="<?php echo $this->_var['seo']['index']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                    </div>
                                    <div class="item">
                                        <div class="label">&nbsp;</div>
                                        <div class="label_value info_btn">
                                            <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                        </div>
                                    </div>
                                    <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                </span>
                                </div>
                            </div>
                        </form>
                    </div>
					<?php endif; ?>
                    
                    <?php if ($this->_var['is_group'] == "group"): ?>
                    <div class="switch_info">
                        <form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_group']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[group][title]" value="<?php echo $this->_var['seo']['group']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[group][keywords]" value="<?php echo $this->_var['seo']['group']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[group][description]" value="<?php echo $this->_var['seo']['group']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                </span>
                            </div>
                            <div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_group_goods']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[group_content][title]" value="<?php echo $this->_var['seo']['group_content']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[group_content][keywords]" value="<?php echo $this->_var['seo']['group_content']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[group_content][description]" value="<?php echo $this->_var['seo']['group_content']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{name}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['is_brand'] == "brand"): ?>
                    <div class="switch_info">
                    	<form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_brand']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[brand_list][title]" value="<?php echo $this->_var['seo']['brand_list']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[brand_list][keywords]" value="<?php echo $this->_var['seo']['brand_list']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[brand_list][description]" value="<?php echo $this->_var['seo']['brand_list']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                </span>
                            </div>
                            <div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_brand_list']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[brand][title]" value="<?php echo $this->_var['seo']['brand']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[brand][keywords]" value="<?php echo $this->_var['seo']['brand']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[brand][description]" value="<?php echo $this->_var['seo']['brand']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                    <a href="javascript:void(0);">{name}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['is_exchage'] == "exchage"): ?>
                    <div class="switch_info">
                    	<form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_exchage']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[change][title]" value="<?php echo $this->_var['seo']['change']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[change][keywords]" value="<?php echo $this->_var['seo']['change']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[change][description]" value="<?php echo $this->_var['seo']['change']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                </span>
                            </div>
                            <div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_exchage_goods']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[change_content][title]" value="<?php echo $this->_var['seo']['change_content']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[change_content][keywords]" value="<?php echo $this->_var['seo']['change_content']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[change_content][description]" value="<?php echo $this->_var['seo']['change_content']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                    <a href="javascript:void(0);">{name}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['is_article'] == "article"): ?>
                    <div class="switch_info">
                    	<form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_article_cat']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[article][title]" value="<?php echo $this->_var['seo']['article']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[article][keywords]" value="<?php echo $this->_var['seo']['article']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[article][description]" value="<?php echo $this->_var['seo']['article']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                    <a href="javascript:void(0);">{name}</a>
                                    <a href="javascript:void(0);">{article_class}</a>
                                </span>
                            </div>
                            <div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_article_content']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[article_content][title]" value="<?php echo $this->_var['seo']['article_content']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[article_content][keywords]" value="<?php echo $this->_var['seo']['article_content']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[article_content][description]" value="<?php echo $this->_var['seo']['article_content']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                    <a href="javascript:void(0);">{name}</a>
                                    <a href="javascript:void(0);">{article_class}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['is_store'] == "store"): ?>
                    <div class="switch_info">
                    	<form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_store']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[shop][title]" value="<?php echo $this->_var['seo']['shop']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[shop][keywords]" value="<?php echo $this->_var['seo']['shop']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[shop][description]" value="<?php echo $this->_var['seo']['shop']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                    <a href="javascript:void(0);">{shopname}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['is_goods'] == "goods"): ?>
                    <div class="switch_info">
                    	<form action="seo.php?act=setting" method="post">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_goods']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="seo[goods][title]" value="<?php echo $this->_var['seo']['goods']['title']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="seo[goods][keywords]" value="<?php echo $this->_var['seo']['goods']['keywords']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="seo[goods][description]" value="<?php echo $this->_var['seo']['goods']['description']; ?>" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" value="<?php echo $this->_var['lang']['submit']; ?>" ectype="btnSubmit" class="button">	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{key}</a>
                                    <a href="javascript:void(0);">{description}</a>
                                    <a href="javascript:void(0);">{name}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['is_goods_cat'] == "goods_cat"): ?>
                    <div class="switch_info">
                    	<form action="seo.php?act=cate_setting" method="post" id="theForm">
                        	<div class="step_title"><i class="ui-step"></i><h3><?php echo $this->_var['lang']['seo_goods_cat']; ?></h3></div>
                            <div class="step_content">
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['03_category_manage']; ?>：</div>
                                    <div class="label_value">
                                    	<div class="search_select">
                                            <div class="categorySelect">
                                                <div class="selection">
                                                    <input type="text" name="category_name" id="category_name" class="text w250 valid" value="<?php echo $this->_var['lang']['select_cat']; ?>" autocomplete="off" readonly data-filter="cat_name" />
                                                    <input type="hidden" name="category_id" id="category_id" value="" data-filter="cat_id" />
                                                </div>
                                                <div class="select-container" style="display:none;">
                                                    <?php echo $this->fetch('library/filter_category.lbi'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label">title：</div>
                                    <div class="label_value"><input type="text" name="cate_title" value=""  id="cate_title" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">keywords：</div>
                                    <div class="label_value"><input type="text" name="cate_keywords" value="" id="cate_keywords" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">description：</div>
                                    <div class="label_value"><input type="text" name="cate_description" value="" id="cate_description" class="text" ectype='text' autocomplete="off" /></div>
                                </div>
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input type="submit" name="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button mr10" id="submitBtn" />	
                                    </div>
                                </div>
                                <span style="display:none;" ectype="hidetag">
                                    <a href="javascript:void(0);">{sitename}</a>
                                    <a href="javascript:void(0);">{name}</a>
                                </span>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    <div class="tag_tips" ectype="tagtips">
                    	<div class="tagtips_tit"><?php echo $this->_var['lang']['tagtips_tit']; ?></div>
                        <div class="tagtips_con" ectype="tagtips_con"></div>
                    </div>
                <?php if ($this->_var['full_page']): ?>    
                </div>
            </div>
        </div>
    </div>
	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <script type="text/javascript">
	$(function(){
    	$("*[ectype='text']").on("focus",function(){
			var p = $(this).position();
			var x = p.left + 370;
			var y = p.top;
			var name = $(this).attr("name");
			
			var html = $(this).parents(".step_content").find("*[ectype='hidetag']").html();
			
			$("*[ectype='tagtips_con']").html(html);
			
			$("*[ectype='tagtips']").css({'position':'absolute','left':x,'top':y,'display':'block'});
			$("*[ectype='tagtips']").data("name",name);
		});
		
		$(document).on("click","*[ectype='tagtips_con'] a",function(){
			var name = $(this).parents("*[ectype='tagtips']").data("name");
			var text = $(this).html();
			var val = $("input[name='"+name+"']").val();
			if(val.indexOf(text) < 0){
				$("input[name='"+name+"']").val(val+text);
			}
		});
	});
    </script>

	<?php if ($this->_var['is_goods_cat'] == "goods_cat"): ?>
    <script type="text/javascript">
    $(function(){
        //表单验证
        $("#submitBtn").click(function(){
            if($("#theForm").valid()){
                $("#theForm").submit();
            }
        });
    
        $('#theForm').validate({
            errorPlacement:function(error, element){
                var error_div = element.parents('div.label_value').find('div.form_prompt');
                element.parents('div.label_value').find(".notic").hide();
                error_div.append(error);
            },
            ignore:'.ignore',
            rules:{
                category_id :{
                    required : true
                }
            },
            messages:{
                category_id:{
                     required : '<i class="icon icon-exclamation-sign"></i><?php echo $this->_var['lang']['select_cat']; ?>'
                }
            }           
        });
    });
    </script>
<?php endif; ?>
</body>
</html>
<?php endif; ?>