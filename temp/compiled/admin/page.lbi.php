<!-- $Id: page.lbi 14216 2008-03-10 02:27:21Z testyang $ -->
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js')); ?>

<div id="turn-page">
    <span class="page page_1"><?php echo $this->_var['lang']['total_records']; ?><em id="totalRecords"><?php echo $this->_var['record_count']; ?></em><?php echo $this->_var['lang']['total_pages']; ?></span>
    <span class="page page_2"><?php echo $this->_var['lang']['page_feiwei']; ?><em id="totalPages"><?php echo $this->_var['page_count']; ?></em><?php echo $this->_var['lang']['page_ye']; ?></span>
    <!--<span><?php echo $this->_var['lang']['page_current']; ?><em id="pageCurrent"><?php echo $this->_var['filter']['page']; ?></em></span>-->
    <span class="page page_3"><i><?php echo $this->_var['lang']['page_size']; ?></i><input type='text' size='3' id='pageSize' value="<?php echo $this->_var['filter']['page_size']; ?>" onkeypress="return listTable.changePageSize(event)" /></span>
    <span class="page mr10"><i><?php echo $this->_var['lang']['to']; ?></i><input type="text" size="3" value="<?php echo $this->_var['filter']['page']; ?>" onkeyup="listTable.gotoPage($(this).val())"></span>
    <span id="page-link">
        <a href="javascript:listTable.gotoPageFirst()" class="first" title="<?php echo $this->_var['lang']['page_first']; ?>"></a>
        <a href="javascript:listTable.gotoPagePrev()" class="prev" title="<?php echo $this->_var['lang']['page_prev']; ?>"></a>
        <select id="gotoPage" onchange="listTable.gotoPage(this.value)">
            <?php echo $this->smarty_create_pages(array('count'=>$this->_var['page_count'],'page'=>$this->_var['filter']['page'])); ?>
        </select>
        <a href="javascript:listTable.gotoPageNext()" class="next" title="<?php echo $this->_var['lang']['page_next']; ?>"></a>
        <a href="javascript:listTable.gotoPageLast()" class="last" title="<?php echo $this->_var['lang']['page_last']; ?>"></a>
    </span>
</div>


