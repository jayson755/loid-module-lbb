@extends($view_base_prefix . '/layouts/child')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-sm-12" style="height: 25px;">
                        <div class="ibox-tools">
                            <div class="adm-fa-hover"><a href="javascript:window.location.href='{{route('lbb.content.banner.set')}}'"><i class="fa fa-refresh"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <form id="setBanner" method="post" class="form-horizontal" action="{{route('lbb.content.banner.setsave')}}">
                <div class="col-sm-12 d-img-show">
                    @foreach ($list as $banner)
                        <div class="file-box d-img-area" style="width:300px;">
                            <div class="file">
                                <span class="corner"></span>
                                <div class="image">
                                    <img alt="image" class="img-responsive" style="height:100%;" src="{{$banner['banner_url']}}">
                                </div>
                                <div class="file-name">
                                    <small>
                                        <div class="input-group m-b"><span class="input-group-addon">link</span>
                                            <input class="form-control" name="banner[{{$banner['banner_id']}}]" value="{{$banner['banner_link']}}" type="text" autocomplete="off">
                                        </div>
                                    </small>
                                    <div class="col-sm-offset-2">
                                        <button class="btn btn-white d-img-div-close" type="button">删除</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach    
                    <div class="file-box" style="width:300px;" id="diy_file_upload">
                        <div class="file">
                            <input type="file" name="imgs" class="d-file-input" _data_action="{{route('manage.upload')}}" _data_fileDistrict="banner" _data_after_fuc="upload_after">
                            <span class="d-file-icon"><i class="fa fa-upload big-icon"></i></span>
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
@endsection

@section('js')
<script type='text/javascript' src="{{asset_site($base_resource, 'plugin', 'validform/js/validform.min.js')}}"></script>
<script type="text/javascript">
function upload_after(obj){
    if (obj.url) {
        var time = Date.parse( new Date() ).toString().substr(0,10);
        var html = '<div class="file-box d-img-area" style="width:300px;">'
                    +'<div class="file">'
                        +'<span class="corner"></span>'
                        +'<div class="image">'
                            +'<img alt="image" class="img-responsive" style="height:100%;" src="'+obj.url+'">'
                            +'<input type="hidden" name="url['+time+']" value="'+obj.url+'">'
                        +'</div>'
                        +'<div class="file-name">'
                            +'<small>'
                                +'<div class="input-group m-b"><span class="input-group-addon">link</span>'
                                    +'<input class="form-control" name="link['+time+']" value="" type="text" autocomplete="off">'
                                +'</div>'
                            +'</small>'
                            +'<div class="col-sm-offset-2">'
                                +'<button class="btn btn-white d-img-div-close" type="button">删除</button>'
                            +'</div>'
                        +'</div>'
                    +'</div>'
                +'</div>';
        $("#diy_file_upload").before(html);
    } else {
        parent.parent._toastr('error', '错误提示', obj.msg);
    }
   
}
$(function(){
    $("div.d-img-show").on('click', '.d-img-div-close', function(){
    
        $(this).parents('.d-img-area').fadeOut('slow', function(){
            $(this).remove();
        });
    });
    $('#diy_file_upload').on('change', '.d-file-input', function(){
        var self = $(this);
        parent.parent._inputchanges(self, window);
    });
    
    $("#setBanner").Validform({
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