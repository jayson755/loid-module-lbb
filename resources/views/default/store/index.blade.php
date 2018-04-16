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
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.store')}}'"><i class="fa fa-refresh"></i></a></div>
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
$(document).ready(function() {
	$("#table_list_2").jqGrid({
        url: "{{route('lbb.store.list', ['param'=>'type'])}}",
		editurl:"{{route('lbb.store.list.modify')}}",
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
		colNames: ["序号", "用户", "币种", "库存数量"],
		colModel: [
			{name:"store_id",index: "store_id",width: 60,sorttype: "int",editable:true,align: "center",search: true,hidden:true},
			{name:"user",index:"user",align: "center",editable:false,width: 90,search: true},
			{name:"category",index:"store_category",align: "center",editable:false,search: true},
            {name:"store_num",index:"store_num",align: "center",editable:true,width: 90,search: true},
		],
		pager: "#pager_list_2",
		viewrecords: true,
        pgbuttons:true,
		hidegrid: false
	}).navGrid('#pager_list_2', {edit: true, add: false, del: false, search:false,searchtext:''},{
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
	},{},{},{});
});
//设置宽度
jQuery("#table_list_2").setGridWidth($("#jqGridRow").innerWidth(), true);
</script>
@endsection