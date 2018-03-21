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
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.category')}}'"><i class="fa fa-refresh"></i></a></div>
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
var statusJson = {"on":"正常","off":"禁用"};
$(document).ready(function() {
	$("#table_list_2").jqGrid({
        url: "{{route('lbb.category.list', ['param'=>'type'])}}",
		editurl: "{{route('lbb.category.modify')}}",
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
		colNames: ["序号", "理财币种", "币种URL", "状态", "创建时间"],
		colModel: [
			{name:"category_id",index: "category_id",width: 60,sorttype: "int",editable:true,align: "center",search: true,hidden:true},
			
			{name:"category_name",index:"category_name",align: "center",editable:true,width: 90,search: true},
			{name:"category_url",index:"category_url",align: "center",editable:true,width: 90,search: true},
            
            {name:"category_status",index:"category_status",align: "center",editable:true,edittype:'custom',editrules:{edithidden:true,required:true,minValue:0},
				editoptions:{
					custom_value: function(elem, oper, value){return getFreightElementValue(elem, oper, value, 'radio')},
					custom_element: function(value, editOptions){return createFreightEditElement(value, editOptions, statusJson, 'radio', 'single')}
				},
				formatter:function(cellvalue, options, rowObject){
					return arrFormat(cellvalue, statusJson, 'single');
				},
				unformat:function(cellvalue, options){
					return arrUnformat(cellvalue, statusJson, 'single');
				},
				width: 90,search: true},
			
			{name:"created_at",index:"created_at",align: "center",editable:false,width: 90,search: true},
		],
		pager: "#pager_list_2",
		viewrecords: true,
        pgbuttons:true,
		hidegrid: false
	}).navGrid('#pager_list_2', {edit: true, add: true, del: false, search:false,searchtext:''},{
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
	});
});
//设置宽度
jQuery("#table_list_2").setGridWidth($("#jqGridRow").innerWidth(), true);
</script>
@endsection