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
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.user.financial')}}'"><i class="fa fa-refresh"></i></a></div>
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
var userJson = $.parseJSON('{!! $userJson !!}');
var statusJson = {"on":"正常","off":"到期回本"};
$(document).ready(function() {
	$("#table_list_2").jqGrid({
        url: "{{route('lbb.user.financial.list', ['param'=>'type'])}}",
		editurl: "",
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
		colNames: ["序号", "用户", "分类（币种）", "理财类型（天数）", "购买数量", "预计收益", "状态", "生效日期", "结束日期", "购买时间"],
		colModel: [
			{name:"id",index: "id",width: 60,sorttype: "int",editable:true,align: "center",search: true,hidden:true},
            {name:"user_id",index:"user_id",align: "center",editable:false,width: 90,
                editoptions:{value:selectInit()},
                formatter:function(cellvalue, options, rowObject){return arrFormat(cellvalue, userJson, 'complex');},
                unformat:function(cellvalue, options){return arrUnformat(cellvalue, userJson, 'complex');},
                search: true,
                stype:'select',
                searchoptions:{sopt:["eq"],value:getArrVal(userJson, 'complex')},
            },
            {name:"category_name",index:"category_name",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"]}},
            {name:"limit_date",index:"limit_date",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"]}},
            {name:"num",index:"num",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"]}},
            {name:"financial_num",index:"financial_num",align: "center",editable:false,width: 90,search: false},
            
            {name:"financial_status",index:"financial_status",align: "center",editable:true,edittype:'custom',editrules:{edithidden:true,required:true,minValue:0},
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
            {name:"effective_date",index:"effective_date",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"],dataInit:dataInit}},
            {name:"closed_date",index:"closed_date",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"],dataInit:dataInit}},
            {name:"created_at",index:"created_at",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"],dataInit:dataInit}},
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
	});
});
//设置宽度
jQuery("#table_list_2").setGridWidth($("#jqGridRow").innerWidth(), true);
</script>
@endsection