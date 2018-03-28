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
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.user')}}'"><i class="fa fa-refresh"></i></a></div>
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
        url: "{{route('lbb.user.list', ['param'=>'type'])}}",
		editurl: "{{route('lbb.user.modify')}}",
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
		colNames: ["序号", "帐号", "预留手机号", "推广来源", "密码", "创建时间"],
		colModel: [
			{name:"lbb_user_id",index: "lbb_user_id",width: 60,sorttype: "int",editable:true,align: "center",search: true,hidden:true},
			
			{name:"lbb_user_account",index:"lbb_user_account",align: "center",editable:true,width: 90,search: true},
			
			{name:"lbb_user_mobile",index:"lbb_user_mobile",align: "center",editable:true,edittype:'text',editrules:{edithidden:true,required:true},width: 90,search: true},
            
            {name:"origin",index:"lbb_user_origin",align: "center",editable:true,width: 90,search: true},
            
			{name:"lbb_user_pwd",index:"lbb_user_pwd",align: "center",editable:true,edittype:'password',editrules:{edithidden:true},width: 90,search: false, hidden:true},
			
			{name:"created_at",index:"created_at",align: "center",editable:false,width: 90,search: true},
		],
		pager: "#pager_list_2",
		viewrecords: true,
        pgbuttons:true,
		hidegrid: false
	}).navGrid('#pager_list_2', {edit: false, add: false, del: false, search:false,searchtext:''},{
		editCaption : "修改",
		top:50,
		left:($(document).innerWidth() - 400) / 5 * 2,
		width:500,
		jqModal : true,  
		reloadAfterSubmit : true,  
		afterShowForm : function(form) {},  
		afterShowForm : function(form) {
            $("#lbb_user_account").attr('disabled', true);
			$("#lbb_user_pwd").attr('placeholder', '不修改密码不输入');
		},  
		afterSubmit: function(response, postdata) {
			if (response.responseJSON.code == 0) {
				return [false, response.responseJSON.msg];
			} else {
				window.top._toastr(response.responseJSON.msg);
				return [true, '', ''];
			}
		}
	});
});
//设置宽度
jQuery("#table_list_2").setGridWidth($("#jqGridRow").innerWidth(), true);
</script>
@endsection