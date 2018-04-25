@extends($view_base_prefix . '/layouts/child')
@section('css')
<link href="{{asset_site($base_resource, 'plugin', 'summernote/summernote.css')}}" rel="stylesheet">
<link href="{{asset_site($base_resource, 'plugin', 'summernote/summernote-bs3.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="ibox-content">
        <form id="modifyArticle" method="post" class="form-horizontal" action="{{route("lbb.content.article.modify")}}">
            <input type="hidden" name="article_id" value="{{$article['article_id'] or ''}}">
            <div class="form-group">
                <label class="col-sm-2 control-label">标题</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="article_title" value="{{$article['article_title'] or ''}}" autocomplete="off" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章类别</label>
                <div class="col-sm-10">
                    <select class="form-control m-b" name="article_category" autocomplete="off" required>
                        <option value="notice" @if ($article['article_category'] == 'notic') selected @endif>公告</option>
                        <option value="aboutus" @if ($article['article_category'] == 'aboutus') selected @endif>关于我们</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">文章内容</label>
                <div class="col-sm-10">
                    <div id="summernote_content">{!! $article['article_content'] or '' !!}</div>
                    <textarea id="content" placeholder="这里输入内容" name="article_content" style="display: none;" class="form-control" autocomplete="off"></textarea>
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
@endsection

@section('js')
<script src="{{asset_site($base_resource, 'plugin', 'summernote/summernote.min.js')}}"></script>
<script src="{{asset_site($base_resource, 'plugin', 'summernote/summernote-zh-CN.js')}}"></script>
<script src="{{asset_site($base_resource, 'js', 'content.min.js')}}"></script>
<script src="{{asset_site($base_resource, 'plugin', 'validform/js/validform.min.js')}}"></script>
<script>
$(function(){
    $("#summernote_content").summernote({
        height: 200,
        focus:true,
        lang: "zh-CN",
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
        ]
    });
    $("#modifyArticle").Validform({
        tiptype:function(msg, o, cssctl){
            if (o.type != 2) {
                parent.parent._toastr('warning', '警告提示', msg);
                return false;
            }
        },
        ajaxPost:true,
        beforeSubmit:function(form){
            var add_article_load = layer.load();
            $("#content").val($('#summernote_content').code());
            $.ajax({
                type    : $(form).attr('method'),
                url     : $(form).attr('action'),
                data    : $(form).serialize(),
                dataType: 'json',
                success : function(data){
                    layer.close(add_article_load);
                    if (data.code == 1) {
                        parent.parent._toastr('success', '成功提示', data.msg);
                        parent.window.location.reload();
                    } else {
                        parent.parent._toastr('error', '错误提示', data.msg);
                        return;
                    }
                },
                error:function(){
                    layer.close(add_article_load);
                    parent.parent._toastr('error', '错误提示', '网络错误');
                }
            });
            return false;
        }
    });
});
</script>
@endsection