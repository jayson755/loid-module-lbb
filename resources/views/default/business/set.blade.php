@extends($view_base_prefix . '/layouts/child')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-12" style="height: 25px;">
                        <div class="ibox-tools">
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.business.set')}}'"><i class="fa fa-refresh"></i></a></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="ibox-content">
                        <form id="setSettingsConfig" method="post" class="form-horizontal" action="{{route('lbb.business.setsave')}}">
                            <h3>余额利息</h3>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">余额利息（天）</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="balance_rate" value="{{$business['balance_rate']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <h3>定存宝利息</h3>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">时长{{$business['financial_limit'][1]['date']}}天</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="financial_limit[1][rate]" value="{{$business['financial_limit'][1]['rate']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">时长{{$business['financial_limit'][2]['date']}}天</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="financial_limit[2][rate]" value="{{$business['financial_limit'][2]['rate']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">时长{{$business['financial_limit'][3]['date']}}天</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="financial_limit[3][rate]" value="{{$business['financial_limit'][3]['rate']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <h3>推广收益</h3>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">一代推广收益</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="promote[proportion][level_1]" value="{{$business['promote']['proportion']['level_1']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">二代推广收益</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="promote[proportion][level_2]" value="{{$business['promote']['proportion']['level_2']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">三代推广收益</label>
                                <div class="col-sm-10">
                                    <div class="input-group m-b"><span class="input-group-addon">%</span>
                                        <input class="form-control" name="promote[proportion][level_3]" value="{{$business['promote']['proportion']['level_3']}}" type="text" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script type='text/javascript' src="{{asset_site($base_resource, 'plugin', 'validform/js/validform.min.js')}}"></script>
<script type="text/javascript">
$(function(){
    $("#setSettingsConfig").Validform({
        tiptype:function(msg, o, cssctl){
            if (o.type != 2) {
                parent._toastr('warning', '警告提示', msg);
                return false;
            }
        },
        ajaxPost:true,
        beforeSubmit:function(form){
            var add_article_load = layer.load();
            $.ajax({
                type    : $(form).attr('method'),
                url     : $(form).attr('action'),
                data    : $(form).serialize(),
                dataType: 'json',
                success : function(data){
                    layer.close(add_article_load);
                    if (data.code == 1) {
                        parent._toastr('success', '成功提示', data.msg);
                        window.location.reload();
                    } else {
                        parent.parent._toastr('error', '错误提示', data.msg);
                        return;
                    }
                },
                error:function(){
                    layer.close(add_article_load);
                    parent._toastr('error', '错误提示', '网络错误');
                }
            });
            return false;
        }
    });
});
</script>
@endsection