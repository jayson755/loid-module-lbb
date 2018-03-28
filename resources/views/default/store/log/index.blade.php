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
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.store.log')}}'"><i class="fa fa-refresh"></i></a></div>
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
var categoryJson = $.parseJSON('{!! $categoryJson !!}');
var flagJson = $.parseJSON('{!! $flagJson !!}');
$(document).ready(function() {
	$("#table_list_2").jqGrid({
        url: "{{route('lbb.store.log.list', ['param'=>'type'])}}",
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
		colNames: ["序号", "用户", "币种类型", "变动类型", "变动数量", "变动数量", "来源", "时间"],
		colModel: [
			{name:"log_id",index: "log_id",width: 60,sorttype: "int",editable:false,align: "center",search: true,hidden:true},
			{name:"user_id",index:"user_id",align: "center",editable:false,width: 90,
                editoptions:{value:selectInit()},
                formatter:function(cellvalue, options, rowObject){return arrFormat(cellvalue, userJson, 'complex');},
                unformat:function(cellvalue, options){return arrUnformat(cellvalue, userJson, 'complex');},
                search: true,
                stype:'select',
                searchoptions:{sopt:["eq"],value:getArrVal(userJson, 'complex')},
            },
            {name:"store_category",index:"store_category",align: "center",editable:false,width: 90,
                editoptions:{value:selectInit()},
                formatter:function(cellvalue, options, rowObject){return arrFormat(cellvalue, categoryJson, 'complex');},
                unformat:function(cellvalue, options){return arrUnformat(cellvalue, categoryJson, 'complex');},
                search: true,
                stype:'select',
                searchoptions:{sopt:["eq"],value:getArrVal(categoryJson, 'complex')},
            },
            {name:"flag",index:"flag",align: "center",editable:true,edittype:'custom',editrules:{edithidden:true,required:true,minValue:0},
				editoptions:{
					custom_value: function(elem, oper, value){return getFreightElementValue(elem, oper, value, 'radio')},
					custom_element: function(value, editOptions){return createFreightEditElement(value, editOptions, flagJson, 'radio', 'single')}
				},
				formatter:function(cellvalue, options, rowObject){
					return arrFormat(cellvalue, flagJson, 'single');
				},
				unformat:function(cellvalue, options){
					return arrUnformat(cellvalue, flagJson, 'single');
				},
				width: 90,search: true,stype:'select',searchoptions:{sopt:["eq"],value:getArrVal(flagJson, 'single')}},
            {name:"store_num",index:"store_num",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"]}},
            {name:"last_num",index:"last_num",align: "center",editable:false,width: 90,search: true,searchoptions:{sopt:["eq"]}},
            {name:"origin_user_id",index:"origin_user_id",align: "center",editable:false,width: 90,
                editoptions:{value:selectInit()},
                formatter:function(cellvalue, options, rowObject){return arrFormat(cellvalue, userJson, 'complex');},
                unformat:function(cellvalue, options){return arrUnformat(cellvalue, userJson, 'complex');},
                search: true,
                stype:'select',
                searchoptions:{sopt:["eq"],value:getArrVal(userJson, 'complex')},
            },
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