@extends($view_base_prefix . '/layouts/child')
@section('css')
<link href="{{asset_site($base_resource, 'plugin', 'jqgrid/css/ui.jqgrid.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-12" style="height: 25px;">
                        <div class="ibox-tools">
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.content.article')}}'"><i class="fa fa-refresh"></i></a></div>
                        </div>
                    </div>
                </div>
                <div class="row" id="jqGridRow">
                    <div class="jqGrid_wrapper">
                        <table id="table_list_2"></table>
                        <div id="pager_list_2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type="text/javascript" src="{{asset_site($base_resource, 'plugin', 'jqgrid/js/jquery.jqGrid.min.js')}}"></script>
<script type="text/javascript" src="{{asset_site($base_resource, 'plugin', 'jqgrid/js/i18n/grid.locale-cn.js')}}"></script>
<script type="text/javascript" src="{{asset_site($base_resource, 'js', 'jqgrid-custom.js')}}"></script>
<script type="text/javascript">
var categoryJson = $.parseJSON('{!! $categoryJson !!}');
$(document).ready(function() {
	$("#table_list_2").jqGrid({
        url: "{{route('lbb.content.article.list', ['param'=>'type'])}}",
		datatype: "json",
		height:$(window).height() - 210,
		autowidth: true,
		shrinkToFit: true,
		rowNum: {{$rows}},
		rowList: [10, 20, 30],
        sortname: 'created_at',
        sortorder: 'desc',
		loadComplete : function(xhr){ //请求成功事件
			try{
				$('.ui-jqdialog-titlebar-close').click();
			}catch(e){}
		},
		colNames: ["序号", "标题", "分类", "创建时间"],
		colModel: [
			{name:"article_id",index: "article_id",width: 60,sorttype: "int",editable:true,align: "center",search: false,hidden:true},
			{name:"article_title",index:"article_title",align: "center",editable:true,width: 90,search: false},
            
            {name:"article_category",index:"article_category",align: "center",editable:true,edittype:'custom',editrules:{edithidden:true,required:true,minValue:0},
				editoptions:{
					custom_value: function(elem, oper, value){return getFreightElementValue(elem, oper, value, 'radio')},
					custom_element: function(value, editOptions){return createFreightEditElement(value, editOptions, categoryJson, 'radio', 'single')}
				},
				formatter:function(cellvalue, options, rowObject){
					return arrFormat(cellvalue, categoryJson, 'single');
				},
				unformat:function(cellvalue, options){
					return arrUnformat(cellvalue, categoryJson, 'single');
				},
				width: 90, search: true,stype:'select',searchoptions:{sopt:["eq"],value:getArrVal(categoryJson, 'single')}},
            
			{name:"created_at",index:"created_at",align: "center",editable:false,width: 90,search: false},
		],
		pager: "#pager_list_2",
		viewrecords: true,
        pgbuttons:true,
		hidegrid: false
	}).navGrid('#pager_list_2', {edit: false, add: false, del: false, search:true,searchtext:''},{
		editCaption : "修改",
		top:50,
		left:($(document).innerWidth() - 400) / 5 * 2,
		width:500,
		jqModal : true,  
		reloadAfterSubmit : true,  
		afterShowForm : function(form) {},  
		afterSubmit: function(response, postdata) {
			if (response.responseJSON.code == 0) {
				return [false, response.responseJSON.msg];
			} else {
                window.top._toastr(response.responseJSON.msg);
				return [true, '', ''];
			}
		}
	},{
		addCaption : "新增",
		top:50,
		left:($(document).innerWidth() - 400) / 5 * 2,
		width:500,
		modal:true,
		jqModal: true,
		reloadAfterSubmit : true,
		afterShowForm : function(form) {
			addporp(form);
		},  
		afterSubmit: function(response, postdata) {
        console.log(response);
			if (response.responseJSON.code == 0) {
				return [false, response.responseJSON.msg];
			} else {
                window.top._toastr(response.responseJSON.msg);
				return [true, '', ''];
			}
		}
	},{
		caption: "删除",  
		msg: "确定删除所选记录？",  
		bSubmit: "删除",  
		bCancel: "取消",
		left:($(document).innerWidth() - 400) / 5 * 2,
		afterSubmit: function(response, postdata) {
			if (response.responseJSON.code == 0) {
				return [false, response.responseJSON.msg];
			} else {
				return [true, '', ''];
			}
		}
	},{
		caption : "搜索",
		top:50,
		left:($(document).innerWidth() - 400) / 5 * 2,
		width:500,
		multipleSearch:true
	}).navButtonAdd('#pager_list_2', {
		caption:"",
		buttonicon:"fa fa-plus",
		position: "last",
		title:"添加内容",
		cursor: "pointer",
		onClickButton:function(){
            popup('{{route("lbb.content.article.modify")}}', '添加');
		}
	}).navButtonAdd('#pager_list_2', {
		caption:"",
		buttonicon:"fa fa-edit",
		position: "last",
		title:"修改内容",
		cursor: "pointer",
		onClickButton:function(){
            var id = $("#table_list_2").jqGrid('getGridParam','selrow');
            if (!id) {
                window.top._toastr('请选择记录!', 'error', '错误提示');return false;
            } else {
                var rowData = $("#table_list_2").jqGrid("getRowData", id);
                popup('{{route("lbb.content.article.modify")}}?article_id='+rowData.article_id, '修改');
				
            }
            
		}
	}).navButtonAdd('#pager_list_2', {
		caption:"",
		buttonicon:"fa fa-trash",
		position: "last",
		title:"删除内容",
		cursor: "pointer",
		onClickButton:function(){
            var id = $("#table_list_2").jqGrid('getGridParam','selrow');
            if (!id) {
                window.top._toastr('请选择记录!', 'error', '错误提示');return false;
            } else {
                var rowData = $("#table_list_2").jqGrid("getRowData", id);
				layer.confirm('确定要删除该条内容吗？', {btn : [ '确定', '取消' ]}, function(index) {
					var recharge = layer.load(0, {shade: [0.1, '#393D49']});
					$.ajax({
						type:'post',
						data:{'article_id':rowData.article_id},
						dataType:'json',
						url:'{{route('lbb.content.article.del')}}',
						success:function(json){
							layer.close(recharge);
							if (json.code) {
								layer.closeAll();
								window.top._toastr('内容删除成功!', 'success', '成功提示');
								window.location.reload();
							} else {
								window.top._toastr(json.msg, 'error', '错误提示');return false;
							}
						},
						error:function(){
							layer.closeAll();
							window.top._toastr('网络错误!', 'error', '错误提示');return false;
						}
					});
				});
            }
		}
	});
});
//设置宽度
jQuery("#table_list_2").setGridWidth($("#jqGridRow").innerWidth(), true);
</script>
@endsection