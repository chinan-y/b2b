<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page_one">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['goods_source'];?></h3>
            <ul class="tab-base">
                <li><span><?php echo $lang['source_add'];?></span></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form  id="source_form" method="post" action="index.php?gct=source&gp=addSource">
           <table>
            <tbody>
			  <tr>
				<td class="required" colspan="2"><label class="validation" for="s_name"><?php echo $lang['goods_source_code'] ;?></label></td>
				<td class="vatop rowform"><input class="txt" name="source_code"  type="text"></td>
			  </tr>
              <tr>
                <td class="required" colspan="2"><label class="validation" for="s_name"><?php echo $lang['source_name'];?></label></td>
                <td class="vatop rowform"><input class="txt" name="source_name" id="s_name" type="text"></td>
              </tr>
              <tr>
                  <td class="required" colspan="2"><label class="validation" for="s_name"><?php echo $lang['description']; ?></label></td>
                  <td class="vatop rowform"><input class="txt" name="description" id="s_name" type="text"></td>
              </tr>
            </tbody>
           </table>
        <input type="submit" value="<?php echo $lang['nc_subs'];?>">
    </form>
	
	<form method='post' id="picForm" name="picForm">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gct" value="source" />
    <input type="hidden" name="gp" value="list" />
    <table class="tb-type2">
      <thead>
        <tr class="thead">
          <th class="w24"></th>
          <th class="w72 center"><?php echo $lang['source_code']; ?></th>
          <th class="w270"><?php echo $lang['source_name'];?></th>
          <th class="w830"><?php echo $lang['description'];?></th>
          <th class="w72"><?php echo $lang['goods_source_handle'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['source_list']) && is_array($output['source_list'])){ ?>
        <?php foreach($output['source_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td><input value="<?php echo $v['source_code']?>" class="checkitem" type="checkbox" name="source_code[]"></td>
          <td class="name"><?php echo $v['source_code'];?></td>
          <td class="name"><?php echo $v['source_name'];?></td>
          <td class="name"><?php echo $v['description'];?></td>
		  <td class="align-center"><a href="javascript:void(0)" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){location.href='index.php?gct=source&gp=del_source&source_code=<?php echo $v['source_code'];?>';}else{return false;}"><?php echo $lang['nc_del'];?></a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['source_list']) && is_array($output['source_list'])){ ?>
        <tr colspan="15" class="tfoot">
          <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){$('#picForm').submit()}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<style type="text/css">
    .page_one fixed-bar{margin-left: 20px;}
    .page_one table {
        margin: 35px;
        font-family: verdana,arial,sans-serif;
        font-size:15px;
        color:#333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
        border: none;
    }
    .page_one table tr td{}
    .page_one input{ margin-left:35px;}
    .page_one table th {
        border-width: 1px;
        padding: 15px;
        font-size: 15px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        word-wrap : break-word ;
    }
    .page_one table td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
        word-break : break-all;
    }
</style>
