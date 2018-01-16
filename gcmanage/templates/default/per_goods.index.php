<?php defined('GcWebShop') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['per_goods_custo'];?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['per_goods_custo'];?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="post" action="index.php?gct=per_goods&gp=per_goods" name="formSearch" id="formSearch">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <th><label for="search_per_name"><?php echo $lang['per_goods_search'];?></label></th>
                <td><input class="txt" name="search_per_name" id="search_per_name" type="text"></td>
                <td><a href="javascript:void(0);" id="sesubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
                </td>
                <td>
                    <ul class="tab-base" style="margin-top: -14px;">
                        <li><a href="index.php?gct=per_goods&gp=per_goods" id="show_all" class="current" style="cursor: pointer;"><span><?php echo $lang['per_goods_all'];?></span></a></li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
        </tr>
        <tr>
            <td>
                <ul>
                    <li><?php echo $lang['per_goods_explain'];?></li>
                </ul></td>
        </tr>
        </tbody>
    </table>

    <form method='post' action="index.php?gct=per_goods&gp=del_all" name="perForm">
        <div style="text-align:right;"><a class="btns" href="javascript:void(0);" id="ncexport"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
        <table class="table tb-type2">
            <thead>
            <tr class="thead">
                <th class="w24"></th>
                <th class="w24"></th>
                <th class="w48" style="width: 80px;"><?php echo $lang['per_goods_user'];?></th>
                <th class="w150"><?php echo $lang['per_goods_name'];?></th>
                <th class="w270" style="width: 200px;"><?php echo $lang['per_goods_func'];?></th>
                <th class="w270"><?php echo $lang['per_goods_img'];?></th>
                <th class="w72"><?php echo $lang['per_goods_area'];?></th>
                <th class="align-center"><?php echo $lang['per_goods_time'];?></th>
                <th class="w270 align-left" style="width: 150px;"><?php echo $lang['per_res'];?></th>
                <th class="w270 align-left" style="width: 150px;"><?php echo $lang['per_goods_status'];?></th>
                <th class="w72 align-center"><?php echo $lang['per_goods_re'];?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($output['result']) && is_array($output['result'])){ ?>
                <?php foreach($output['result'] as $k => $v){ ?>
                    <tr class="hover edit">
                        <td><input value="<?php echo $v['per_id']?>" class="checkitem" type="checkbox" name="del_per_id[]"></td>
                        <td><?php echo $v["per_id"];?></td>
                        <td class="sort"><?php echo $v["per_member_name"];?></td>
                        <td class="name"><?php echo $v["per_name"];?></td>
                        <td class="class se-all"><p style="width: 180px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;"><?php echo $v['per_func']?></p></td>
                        <td class="picture">
                            <div class="brand-picture">
                                <?php if($v['per_img']!=null&&$v['per_img']!=""&&strpos($v['per_img'],"|")===false){?>
                                    <img src='../../wap/images/<?php echo $v["per_img"] ?>' style="width: 55px;height: 100px;" class="black">
                                <?php }elseif($v['per_img']!=null&&$v['per_img']!=""&&strpos($v['per_img'],"|")!==false){?>
                                    <?php $pic=explode("|",$v["per_img"]);{?>
                                        <?php foreach($pic as $m=>$n){?>
                                            <img src='../../wap/images/<?php echo $n; ?>' style="width: 55px;height: 100px;" class="black">
                                        <?php }?>
                                    <?php }?>
                                <?php }?>
                             </div>
                        </td>
                        <td><?php echo $v['per_sclass']; ?></td>
                        <td class="align-center">
                            <?php echo $v['pub_time'];?>
                        </td>
                        <td class="class sc-all">
                            <?php if($v['per_res']==null){?>
                                <p style="width: 140px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;">暂无回复</p>
                            <?php }else{?>
                                <p style="width: 140px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;"><?php echo $v['per_res'];?></p>
                            <?php }?>
                        </td>
                        <td class="class sa-all"><p style="width: 140px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;"><?php echo $v['per_status'];?></p></td>

                        <td class="align-center">
                            <a href="javascript:void(0)" class="resp"><?php echo $lang['nc_re'];?></a>&nbsp;|&nbsp;
                            <a href="javascript:void(0)" class="dels">
                                <?php echo $lang['nc_del'];?>
                            </a>
                        </td>
                    </tr>
                    <?php }?>
            <?php }else { ?>
                <tr class="no_data">
                    <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php if(!empty($output['result']) && is_array($output['result'])){ ?>
                <tr colspan="15" class="tfoot">
                    <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
                    <td colspan="16"><label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
                        &nbsp;&nbsp;
                        <a href="JavaScript:void(0);" class="btn" onclick="document.perForm.submit()"><span><?php echo $lang['nc_del'];?></span></a>
                        <div class="pagination"> <?php echo $output['page'];?> </div></td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
    </form>
    <div class="clear"></div>
    <div id="black" style="width: 100%;height: 100%;top: 0%;left: 0%;margin: 0;position: fixed;z-index: 100;background:rgba(0,0,0,.8);display: none;overflow: hidden;"></div>
    <div class="tip-div" style="background: #d2dcf8;border: 1px black solid;height: 50%;width: 50%;right: 25%;top: 25%;padding: 10px;position: absolute;display: none;">
        <p style="word-break: break-all;word-wrap: break-word;font-size: 14px;text-indent: 10px;">
        </p>
        <button style="position: absolute;bottom: 2%;right: 50%;" id="s-btn">确认</button>
    </div>
</div>
<script>
    var p_value="";
    $(".resp").click(function(){
        if ($(this).parent().prev().find("input").length<1){
            var p_ele=$(this).parent().prev();
            p_value=$(this).parent().prev().text();
            p_ele.html("");
            var new_ele="<input type='text' value='"+p_value+"' class='new_inp' style='width: 200px;' maxlength='8'>";
            var btn_s="<a class='s_btn' href='javascript:void(0)'><?php echo $lang['nc_ok'];?></a>|";
            var btn_d="<a class='d_btn' href='javascript:void(0)'><?php echo $lang['nc_cancel'];?></a>";
            p_ele.append(new_ele);
            p_ele.append(btn_s);
            p_ele.append(btn_d);
        }
    });

    $(".d_btn").live("click",function(){
        $(this).parent().text(p_value);
    });
    $(".s_btn").live("click",function(){
        var uid=$(this).parent().parent().find("td:nth-child(2)").text();
//        alert(uid);return;
        var tes=$(this).parent().find("input").val();
        var _this=$(this);
        if($(this).parent().find("input").val()!=""){
            $.ajax({
                url:"index.php?gct=per_goods&gp=up_goods",
                type:"post",
                data:{tes:tes,uid:uid},
                dataType:"text",
                success:function(result){
                    alert("回复成功");
                    _this.parent().html($('<p style="width: 140px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;">'+result+'</p>'));
//                    _this.parent.html()
                },
                erorr:function(){
                    alert("更改失败");
                }
            });
        }
    });

    $(".dels").click(function(){
        var uid=$(this).parent().parent().find("td:nth-child(2)").text();
        var _this=$(this);
        $.ajax({
            url:"index.php?gct=per_goods&gp=del_goods",
            type:"post",
            data:{uid:uid},
            dataType:"text",
            success:function(result){
                alert("删除了第"+result+"条数据");
                _this.parent().parent().remove();
            },
            erorr:function(result){
                alert(删除失败);
            }
        });
    });

    $("#sesubmit").click(function(){
        if($("#search_per_name").val()!=""){
            document.formSearch.submit();
        }
    });

    $(".black").live("click",function(){
        if($("#black").css("display")=="none"){
            $("#black").show();
            $("body").css("overflow-y","hidden");
            var figure=$("<figure style='float: left;height: 100%; position: relative;margin: 0;' class='item'></figure>");
            var clone=$(this).clone();
            var width=parseInt($("#black").css("width"))*0.7;
            clone.css({"width":width+'px',"height":"auto","margin":"0 18%"});
            clone.appendTo(figure);
            figure.appendTo($("#black"));
        }
    });

    $("#black").click(function(){
        if($("#black").css("display")=="block"){
            $("#black").hide("fast");
            $("#black").html("");
            $("body").css("overflow-y","auto");
        }
    });

//    $(".se-all").live("click",function(){
//        alert("aaa");return;
//        $(".tip-div").css("display")=="none"?  $(".tip-div").css("display","block"): $(".tip-div").css("display":"none");
//    })
    $(".se-all>p").click(function(){
        if($(".tip-div").css("display")=="none"){
            var text=$(this).text();
            var p=$(".tip-div>p");
            p.text(text);
            $(".tip-div").css("display","block");
        }
    });
    $(".sa-all>p").live("click",function(){
        if($(".tip-div").css("display")=="none"){
            var text=$(this).text();
            var p=$(".tip-div>p");
            p.text(text);
            $(".tip-div").css("display","block");
        }
    });

    $("#s-btn").click(function(){
        $(".tip-div").css("display","none");
        $(".tip-div>p").text("");
    });

    var s_value="";
    $(".sc-all p").live("click",function(){
        var p_ele=$(this).parent();
        if ($(this).parent().find("input").length<1){
            s_value=$(this).text();
            $(this).parent().html("");
            var new_ele="<input type='text' value='"+s_value+"' maxlength='35' class='add_inp' style='width: 150px;'>";
            var btn_s="<a class='s_btn1' href='javascript:void(0)'><?php echo $lang['nc_ok'];?></a>|";
            var btn_d="<a class='d_btn1' href='javascript:void(0)'><?php echo $lang['nc_cancel'];?></a>";
            p_ele.append(new_ele);
            p_ele.append(btn_s);
            p_ele.append(btn_d);
        }
    });
    $(".d_btn1").live("click",function(){
        var pa=$(this).parent();
        $(this).parent().text("");
        pa.append('<p style="width: 140px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;">'+s_value+'</p>');
    });
    $(".s_btn1").live("click",function(){
        var uid=$(this).parent().parent().find("td:nth-child(2)").text();
        var tes=$(this).parent().find("input").val();
        var _this=$(this);
        if(tes!=""){
            $.ajax({
                url:"index.php?gct=per_goods&gp=up_res",
                type:"post",
                data:{tes:tes,uid:uid},
                dataType:"text",
                success:function(result){
                    alert("回复成功");
                    _this.parent().html($('<p style="width: 140px;overflow: hidden;text-overflow: ellipsis;cursor: pointer;">'+result+'</p>'));
//                    _this.parent.html()
                },
                erorr:function(){
                    alert("更改失败");
                }
            });
        }
    });



</script>