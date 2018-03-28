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
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.store.withdrawing')}}'"><i class="fa fa-refresh"></i></a></div>
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
var statusJson = {"0":"未处理","1":"已处理"};
$(document).ready(function() {
	$("#table_list_2").jqGrid({
        url: "{{route('lbb.store.withdrawing.list', ['param'=>'type'])}}",
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
		colNames: ["序号", "用户", "币种", "提现地址", "提现数量", "申请时间", "处理状态"],
		colModel: [
			{name:"withdraw_id",index: "withdraw_id",width: 60,sorttype: "int",editable:false,align: "center",search: false,hidden:true},
			{name:"user",index:"user",align: "center",editable:true,width: 90,search: false},
			{name:"category",index:"store_category",align: "center",editable:false,search: false},
            {name:"withdraw_url",index:"withdraw_url",align: "center",editable:false,width: 90,search: false},
            {name:"withdraw_num",index:"withdraw_num",align: "center",editable:false,width: 90,search: false},
            {name:"created_at",index:"created_at",align: "center",editable:false,width: 90,search: false},
            {name:"withdraw_status",index:"withdraw_status",align: "center",editable:true,edittype:'custom',editrules:{edithidden:true,required:true,minValue:0},
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
				width: 90,search: true,stype:'select',searchoptions:{sopt:["eq"],value:getArrVal(statusJson, 'single')}},
		],
		pager: "#pager_list_2",
		viewrecords: true,
        pgbuttons:true,
		hidegrid: false
	}).navGrid('#pager_list_2', {edit: false, add: false, del: false, search:true,searchtext:''},{},{},{},{
		caption : "搜索",
		top:50,
		left:($(document).innerWidth() - 400) / 5 * 2,
		width:500,
		multipleSearch:true
	}).navButtonAdd('#pager_list_2', {
		caption:"",
		buttonicon:"fa fa-legal",
		position: "last",
		title:"处理提现申请",
		cursor: "pointer",
		onClickButton:function(){
            var id = $("#table_list_2").jqGrid('getGridParam','selrow');
            if (!id) {
				window.top._toastr('请选择记录!', 'error', '错误提示');return false;
            } else {
                var rowData = $("#table_list_2").jqGrid("getRowData", id);
				console.log(rowData);
                if (rowData.withdraw_status == 1) {
					window.top._toastr('该提现申请已处理!', 'error', '错误提示');return false;
                }
				
				layer.confirm('确定要处理该条申请吗？', {btn : [ '确定', '取消' ]}, function(index) {
					var recharge = layer.load(0, {shade: [0.1, '#393D49']});
					$.ajax({
						type:'post',
						data:{'withdraw_id':rowData.withdraw_id},
						dataType:'json',
						url:'{{route('lbb.store.withdrawing.dealwith')}}',
						success:function(json){
							layer.close(recharge);
							if (json.code) {
								layer.closeAll();
								window.top._toastr('申请处理成功!', 'success', '成功提示');
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